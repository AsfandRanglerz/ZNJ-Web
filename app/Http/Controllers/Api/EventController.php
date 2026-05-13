<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\User;
use Stripe\Customer;
use App\Models\Event;
use App\Models\Venue;
use App\Mail\JoinEvent;
use App\Models\Payment;
use App\Models\EventVenue;
use Endroid\QrCode\QrCode;
use App\Models\EventTicket;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Endroid\QrCode\Color\Color;
use App\Models\EntertainerDetail;
use App\Models\EventEntertainers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\EventFeatureAdsPackage;
use Illuminate\Support\Facades\Session;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // Get entertainers
    public function entertainer_tallents()
    {
        $data = EntertainerDetail::with('User')->get();
        return $this->sendSuccess('Entertainers with Talent', compact('data'));
    }
    // Create Event
   public function createEvent(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'cover_image' => 'required',
            'about_event' => 'required',
            'event_type' => 'required',
            'date' => 'required|date',
            'from' => 'required',
            'to' => 'required',
            'joining_type' => 'required',
            'price' => 'required',
            'seats' => 'required',
            'description' => 'required',
            'hiring_entertainers_status' => 'required',
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        // CHECK 1: Is venue already APPROVED for another event on this date?
        if (isset($request->venues_id)) {
            $venueAlreadyApproved = EventVenue::where('venues_id', $request->venues_id)
                ->where('status', 'Approved')
                ->whereHas('event', function ($q) use ($request) {
                    $q->where('date', $request->date);
                })
                ->exists();
            
            if ($venueAlreadyApproved) {
                return $this->sendError('This venue is already booked on ' . $request->date . '. You cannot create an event on this date.');
            }
        }

        // CHECK 2: Is same user creating multiple events on same date?
        $existingEvent = Event::where('user_id', auth()->id())
            ->where('date', $request->date)
            ->first();
        
        if ($existingEvent) {
            return $this->sendError('You already have an event on ' . $request->date . '. You cannot create multiple events on the same date.');
        }

        $data = $request->only(['title', 'location', 'about_event', 'event_type', 'date', 'from', 'to', 'joining_type', 'price', 'description', 'seats']);
        $data['user_id'] = auth()->id();
        
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $file->move(public_path('images'), $filename);
            $data['cover_image'] = 'public/images/' . $filename;
        }
        
        $event = Event::create($data);
        
        // Save entertainers
        if (isset($request->entertainer_details_id) && is_array($request->entertainer_details_id)) {
            foreach ($request->entertainer_details_id as $entertainerId) {
                $event_entertainer = new EventEntertainers();
                $event_entertainer->event_id = $event->id;
                $event_entertainer->entertainer_details_id = $entertainerId;
                $event_entertainer->status = 'Pending';
                $event_entertainer->save();
            }
        }
        
        // Save venue
        if (isset($request->venues_id)) {
            $event_venue = new EventVenue();
            $event_venue->event_id = $event->id;
            $event_venue->venues_id = $request->venues_id;
            $event_venue->status = 'Pending';
            $event_venue->save();
        }
        
        // Send notification to venue provider
        $eventCreator = Event::with('User')->find($event->id);
        
        if (isset($request->venues_id)) {
            $venue_notify = Venue::with('User', 'venueCategory')->find($request->venues_id);
            if ($venue_notify && $venue_notify->User && $venue_notify->User->role == 'venue_provider') {
                $notification = new Notification();
                $notification->user_id = $venue_notify->User->id;
                $notification->title = 'Hiring Request';
                $notification->type = 'request';
                $notification->body = $eventCreator->User->name . ' wants to book your venue (' . $venue_notify->title . ') for his event';
                $notification->event_id = $event->id;
                $notification->venue_id = $venue_notify->id;
                $notification->save();
                
                // Send FCM notification
                if ($venue_notify->User->fcm_token) {
                    $this->sendFCMNotification(
                        $venue_notify->User->fcm_token,
                        'Hiring Request',
                        $eventCreator->User->name . ' wants to book your venue (' . $venue_notify->title . ') for his event',
                        [
                            'id' => $notification->id,
                            'event_id' => $event->id,
                            'venue_id' => $venue_notify->id,
                            'user_id' => $venue_notify->User->id,
                            'type' => 'request',
                        ]
                    );
                }
            }
        }
        
        // Send notification to entertainers
        if (isset($request->entertainer_details_id) && is_array($request->entertainer_details_id)) {
            $entertainers = EntertainerDetail::with('User', 'talentCategory')->whereIn('id', $request->entertainer_details_id)->get();
            foreach ($entertainers as $entertainer) {
                if ($entertainer->User && $entertainer->User->role == 'entertainer') {
                    $notification = new Notification();
                    $notification->user_id = $entertainer->User->id;
                    $notification->title = 'Hiring Request';
                    $notification->type = 'request';
                    $notification->body = $eventCreator->User->name . ' wants to hire you in his event as a ' . ($entertainer->talentCategory->category ?? 'entertainer');
                    $notification->event_id = $event->id;
                    $notification->entertainer_details_id = $entertainer->id;
                    $notification->save();
                    
                    // Send FCM notification
                    if ($entertainer->User->fcm_token) {
                        $this->sendFCMNotification(
                            $entertainer->User->fcm_token,
                            'Hiring Request',
                            $eventCreator->User->name . ' wants to hire you in his event as a ' . ($entertainer->talentCategory->category ?? 'entertainer'),
                            [
                                'id' => $notification->id,
                                'event_id' => $event->id,
                                'entertainer_details_id' => $entertainer->id,
                                'user_id' => $entertainer->User->id,
                                'type' => 'request',
                            ]
                        );
                    }
                }
            }
        }
        
        $result = Event::with('entertainerDetails', 'eventVenues')->find($event->id);
        return $this->sendSuccess('Event created Successfully', $result);
        
    } catch (\Exception $e) {
        return $this->sendError('Error: ' . $e->getMessage() . ' on line ' . $e->getLine());
    }
}

// Helper function for FCM notification
// private function sendFCMNotification($fcm_token, $title, $body, $dataArray)
// {
//     if (empty($fcm_token)) {
//         return false;
//     }
    
//     $SERVER_API_KEY = 'AAAA1U9GgKM:APA91bE_zPJEiZ6IBj_RcFwN_xzOSB7v2osZC9DcWSoYi7nPaDUdPOZRndvEnBiq8U4RgcXaNTIQUUl6-jr5FsHRCWTXUEmbjkbk5myWI_7YYif7Rj9uCqdAwPUoAEmExXkeRVeemhNo';
    
//     $data = [
//         'to' => $fcm_token,
//         'notification' => [
//             'title' => $title,
//             'body' => $body,
//         ],
//         'data' => [
//             'RequestData' => $dataArray,
//         ],
//         'content_available' => true,
//     ];
    
//     $dataString = json_encode($data);
//     $headers = [
//         'Authorization: key=' . $SERVER_API_KEY,
//         'Content-Type: application/json',
//     ];
    
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//     $response = curl_exec($ch);
//     curl_close($ch);
    
//     return $response;
// }
    // Get events
    public function getEvents()
    {
        $lon = Auth::user()->longitude;
        $lat = Auth::user()->latitude;
        if ($lat) {
            $venue_event = Venue::with(['events' => function ($query) {
                $query->where('date', '>=', now()->format('Y-m-d'))
                ->where('delete_request',null);
                $query->with('User');
            }])
                ->select(
                    "venues.id",
                    "venues.address",
                    DB::raw("6371 * acos(cos(radians(" . $lat . "))
                         * cos(radians(venues.latitude))
                        * cos(radians(venues.longitude) - radians(" . $lon . "))
                        + sin(radians(" . $lat . "))
                       * sin(radians(venues.latitude))) AS distance")
                )
                ->orderBy('distance', 'ASC')
                ->limit(10)
                ->get();
                return $this->sendSuccess('Events', compact('venue_event'));

                } else {
                    $event = Event::with('User', 'entertainerDetails', 'eventVenues')
                        ->where('date', '>=', now()->format('Y-m-d'))
                        ->where('delete_request',null)
                        ->get();

                    return $this->sendSuccess('Events', compact('event'));
                }
    }
    // Get user events
    public function userEvents()
    {
        $user_event = Event::with('User', 'reviews.user')->where('user_id', auth()->id())->where('delete_request',null)->orderBy('id','DESC')->get();
        // $user_event = Event::join('users', 'events.user_id', '=', 'users.id')->where('user_id', auth()->id())->get(['events.*', 'users.name','users.image']);
        return $this->sendSuccess('user events', compact('user_event'));
    }
    // get events
    public function getEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $event = Event::find($request->event_id)->get();
        return $this->sendSuccess('Event', compact('event'));
    }
    // get event entertainer
    public function getEventEntertainers($id)
    {
        $data = EventEntertainers::where('event_id', $id)->get();
        return $this->sendSuccess('event entertainers', compact('data'));
    }
    // update event
    // public function updateEvent(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required',
    //         // 'cover_image' => 'required',
    //         // 'location' => 'required',
    //         'about_event' => 'required',
    //         'event_type' => 'required',
    //         'date' => 'required',
    //         'from' => 'required',
    //         'to' => 'required',
    //         'joining_type' => 'required',
    //         'price' => 'required',
    //         'seats' => 'required',
    //         'description' => 'required',
    //         'hiring_entertainers_status' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendError($validator->errors()->first());
    //     }
    //     $data = $request->only(['title', 'location', 'about_event', 'seats', 'event_type', 'date', 'from', 'to', 'joining_type', 'price', 'description']);
    //     $data['user_id'] = auth()->id();
    //     if ($request->hasfile('cover_image')) {
    //         $file = $request->file('cover_image');
    //         $extension = $file->getClientOriginalExtension(); // getting image extension
    //         $filename = time() . '.' . $extension;
    //         $file->move(public_path('images'), $filename);
    //         $data['cover_image'] = 'public/images/' . $filename;
    //     }
    //     $event = Event::find($id)->update($data);
    //     // EventEntertainers::where('event_id', $id)->delete();

    //     EventEntertainers::where('event_id', $id)->where('status', 'Pending')->delete();
    //     if (isset($request->entertainer_details_id)) {
    //         // EventEntertainers::whereIn('entertainer_details_id','!=',$request->entertainer_details_id)->where('event_id', $id)->get();
    //         $entertainers =  EventEntertainers::with('entertainer.User', 'entertainer.talentCategory')->whereNotIn('entertainer_details_id', $request->entertainer_details_id)->where('event_id', $id)->get();
    //         foreach ($entertainers as $user) {
    //             if ($user->entertainer->User->role == 'entertainer') {

    //                 $old_notification_enter = Notification::where('entertainer_details_id', $user->entertainer->id)->where('event_id', $id)->delete();
    //             }
    //         }

    //         for ($i = 0; $i < count($request->entertainer_details_id); $i++) {
    //             $old_event  = EventEntertainers::where('event_id', $id)->where('status', '<>', 'Pending')->where('entertainer_details_id', $request->entertainer_details_id[$i])->first();
    //             if (isset($old_event)) {
    //             } else {
    //                 $event_entertainer = new EventEntertainers;
    //                 $event_entertainer->event_id = $id;
    //                 $event_entertainer->entertainer_details_id = $request->entertainer_details_id[$i];
    //                 $event_entertainer->save();
    //             }
    //         }
    //     }
    //     // if (isset($request->venues_id)) {
    //     //     EventVenue::where('event_id', $id)->update([
    //     //         'venues_id' => $request->venues_id,
    //     //     ]);
    //     // }
    //     if (isset($request->venues_id)) {
    //         Notification::where('event_id', $id)->where('venue_id', '<>', $request->venues_id)->delete();
    //         // EventVenue::where('event_id', $id)->update([
    //         //     'venues_id' => $request->venues_id,
    //         // ]);
    //         $eventVenue = EventVenue::where('event_id', $id)->where('venues_id', $request->venues_id)->where('status', '<>', 'Pending')->first();
    //         if (isset($eventVenue)) {
    //         } else {
    //             EventVenue::where('event_id', $id)->where('status', 'Pending')->delete();
    //             $data = new EventVenue();
    //             $data->event_id = $id;
    //             $data->venues_id = $request->venues_id;
    //             $data->save();
    //         }
    //     }
    //     // Send notification to venue provider
    //     $eventCreator = Event::with('User')->find($id);
    //     $venue_notify = Venue::with('User', 'venueCategory')->find($request->venues_id);
    //     if ($venue_notify->User->role == 'venue_provider') {
    //         $old_notification = Notification::where('event_id', $id)->where('venue_id', $request->venues_id)->first();
    //         if (isset($old_notification)) {
    //         } else {
    //             $notification = new Notification();
    //             $notification->user_id = $venue_notify->User->id;
    //             $notification->title = 'Hiring Request';
    //             $notification->type = 'request';
    //             $notification->body = $eventCreator->User->name . ' ' . 'wants to book your venue (' . $venue_notify->title . ') for his event';
    //             $notification->event_id = $id;
    //             $notification->venue_id = $venue_notify->id;
    //             $notification->save();
    //             $body = array(
    //                 'id' => $notification->id,
    //                 'event_id' => $id,
    //                 'venue_id' => $venue_notify->id,
    //                 'user_id'  => $venue_notify->User->id,
    //                 'type'     => 'request',
    //             );
    //             $data = [
    //                 'to' => $venue_notify->User->fcm_token,
    //                 'notification' => [
    //                     'title' => "Hiring Request",
    //                     'body' => $eventCreator->User->name . ' ' . 'wants to book your venue (' . $venue_notify->title . ') for his event',
    //                 ],
    //                 'data' => [
    //                     'RequestData' => $body,
    //                 ],
    //                 'content_available' => true,
    //             ];
    //             // $SERVER_API_KEY = 'AAAAGAYvVyg:APA91bHn703e-8w6gHludk4Wd8Uj1HjFXYp6933n-ZQx-a8qM_Hu86nJh-XlVv7CBUXikcOICEN1TW4sswuAjjeD7RWaCwttgE3R26ZvLGdwkIgHR9HigoxyZusqQucp-i5vdjyqWww8';
    //             $SERVER_API_KEY = 'AAAA1U9GgKM:APA91bE_zPJEiZ6IBj_RcFwN_xzOSB7v2osZC9DcWSoYi7nPaDUdPOZRndvEnBiq8U4RgcXaNTIQUUl6-jr5FsHRCWTXUEmbjkbk5myWI_7YYif7Rj9uCqdAwPUoAEmExXkeRVeemhNo';

    //             $dataString = json_encode($data);
    //             $headers = [
    //                 'Authorization: key=' . $SERVER_API_KEY,
    //                 'Content-Type: application/json',
    //             ];

    //             $ch = curl_init();
    //             curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    //             curl_setopt($ch, CURLOPT_POST, true);
    //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //             curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    //             $response = curl_exec($ch);
    //             // dd($response);
    //         }
    //     }
    //     // Send notification to entertainers

    //     $eventCreator = Event::with('User')->find($id);
    //     if (isset($request->entertainer_details_id)) {
    //         $entertainers = EntertainerDetail::with('User', 'talentCategory')->whereIn('id', $request->entertainer_details_id)->get();
    //         foreach ($entertainers as $user) {
    //             $old_notification_enter = Notification::where('entertainer_details_id', $user->id)->where('event_id', $id)->first();
    //             if (isset($old_notification_enter)) {
    //             } else {
    //                 if ($user->User->role == 'entertainer') {
    //                     $notification = new Notification();
    //                     $notification->user_id = $user->User->id;
    //                     $notification->title = 'Hiring Request';
    //                     $notification->type = 'request';
    //                     $notification->body = $eventCreator->User->name . ' ' . 'wants to hire you in his event as a' . ' ' . $user->talentCategory->category;
    //                     $notification->event_id = $id;
    //                     $notification->entertainer_details_id = $user->id;
    //                     $notification->save();
    //                     $body = array(
    //                         'id' => $notification->id,
    //                         'event_id' => $id,
    //                         'entertainer_details_id' => $user->id,
    //                         'user_id' => $user->User->id,
    //                         'type'     => 'request',
    //                     );
    //                     $data = [
    //                         'to' => $user->User->fcm_token,
    //                         'notification' => [
    //                             'title' => "Hiring Request",
    //                             'body' =>  $eventCreator->User->name . ' ' . 'wants to hire you in his event as a' . ' ' . $user->talentCategory->category,
    //                         ],
    //                         'data' => [
    //                             'RequestData' => $body,
    //                         ],
    //                         'content_available' => true,
    //                     ];
    //                 }

    //                 // $SERVER_API_KEY = 'AAAAGAYvVyg:APA91bHn703e-8w6gHludk4Wd8Uj1HjFXYp6933n-ZQx-a8qM_Hu86nJh-XlVv7CBUXikcOICEN1TW4sswuAjjeD7RWaCwttgE3R26ZvLGdwkIgHR9HigoxyZusqQucp-i5vdjyqWww8';
    //                 $SERVER_API_KEY = 'AAAA1U9GgKM:APA91bE_zPJEiZ6IBj_RcFwN_xzOSB7v2osZC9DcWSoYi7nPaDUdPOZRndvEnBiq8U4RgcXaNTIQUUl6-jr5FsHRCWTXUEmbjkbk5myWI_7YYif7Rj9uCqdAwPUoAEmExXkeRVeemhNo';


    //                 $dataString = json_encode($data);
    //                 $headers = [
    //                     'Authorization: key=' . $SERVER_API_KEY,
    //                     'Content-Type: application/json',
    //                 ];

    //                 $ch = curl_init();
    //                 curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    //                 curl_setopt($ch, CURLOPT_POST, true);
    //                 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //                 curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    //                 $response = curl_exec($ch);
    //                 // dd($response);
    //             }
    //         }
    //     }
    //     $data = Event::with('entertainerDetails', 'eventVenues')->find($id);
    //     return $this->sendSuccess('Event updated Successfully', compact('data'));
    // }
// public function updateEvent(Request $request, $id)
// {
//     $event = Event::find($id);
    
//     // Check if user is event creator
//     if ($event->user_id != auth()->id()) {
//         return $this->sendError('You are not authorized to update this event');
//     }
    
//     // Check if ANY pending exists (venue ya koi entertainer)
//     $hasPendingVenue = EventVenue::where('event_id', $id)->where('status', 'Pending')->exists();
//     $hasPendingEntertainer = EventEntertainers::where('event_id', $id)->where('status', 'Pending')->exists();
    
//     // Agar koi bhi pending hai to full update allowed
//     $hasAnyPending = $hasPendingVenue || $hasPendingEntertainer;
    
//     // Agar koi pending nahi hai (sab approved hai) to sirf cover image
//     if (!$hasAnyPending) {
//         // Sirf cover image update allowed
//         if ($request->hasfile('cover_image')) {
//             $file = $request->file('cover_image');
//             $filename = time() . '.' . $file->getClientOriginalExtension();
//             $file->move(public_path('images'), $filename);
//             $event->cover_image = 'public/images/' . $filename;
//             $event->save();
            
//             $data = Event::with('entertainerDetails', 'eventVenues')->find($id);
//             return $this->sendSuccess('Cover image updated successfully', $data);
//         } else {
//             return $this->sendError('Only cover image can be updated as all requests are approved');
//         }
//     }
    
//     // Yahan aao to koi pending hai - FULL UPDATE ALLOWED
//     $validator = Validator::make($request->all(), [
//         'title' => 'required',
//         'about_event' => 'required',
//         'event_type' => 'required',
//         'date' => 'required',
//         'from' => 'required',
//         'to' => 'required',
//         'joining_type' => 'required',
//         'price' => 'required',
//         'seats' => 'required',
//         'description' => 'required',
//         'hiring_entertainers_status' => 'nullable',
//     ]);
    
//     if ($validator->fails()) {
//         return $this->sendError($validator->errors()->first());
//     }
    
//     // Update event data
//     $data = $request->only(['title', 'location', 'about_event', 'seats', 'event_type', 'date', 'from', 'to', 'joining_type', 'price', 'description']);
//     $data['user_id'] = auth()->id();
    
//     if ($request->hasfile('cover_image')) {
//         $file = $request->file('cover_image');
//         $filename = time() . '.' . $file->getClientOriginalExtension();
//         $file->move(public_path('images'), $filename);
//         $data['cover_image'] = 'public/images/' . $filename;
//     }
    
//     $event->update($data);
    
//     // Update entertainers - Delete all pending and add new
//     EventEntertainers::where('event_id', $id)->where('status', 'Pending')->delete();
    
//     if (isset($request->entertainer_details_id)) {
//         for ($i = 0; $i < count($request->entertainer_details_id); $i++) {
//             // Check if already exists with Approved status
//             $existing = EventEntertainers::where('event_id', $id)
//                 ->where('entertainer_details_id', $request->entertainer_details_id[$i])
//                 ->where('status', 'Approved')
//                 ->first();
            
//             if (!$existing) {
//                 $event_entertainer = new EventEntertainers();
//                 $event_entertainer->event_id = $id;
//                 $event_entertainer->entertainer_details_id = $request->entertainer_details_id[$i];
//                 $event_entertainer->status = 'Pending';
//                 $event_entertainer->save();
//             }
//         }
//     }
    
//     // Update venue - Delete pending and add new
//     if (isset($request->venues_id)) {
//         // Check if venue already Approved
//         $venueApproved = EventVenue::where('event_id', $id)
//             ->where('venues_id', $request->venues_id)
//             ->where('status', 'Approved')
//             ->first();
        
//         if (!$venueApproved) {
//             // Delete old pending venue
//             EventVenue::where('event_id', $id)->where('status', 'Pending')->delete();
            
//             $eventVenue = new EventVenue();
//             $eventVenue->event_id = $id;
//             $eventVenue->venues_id = $request->venues_id;
//             $eventVenue->status = 'Pending';
//             $eventVenue->save();
//         }
//     }
    
//     $result = Event::with('entertainerDetails', 'eventVenues')->find($id);
//     return $this->sendSuccess('Event updated successfully', $result);
// }
public function updateEvent(Request $request, $id)
{
    try {
        $event = Event::find($id);
        
        if (!$event) {
            return $this->sendError('Event not found');
        }
        
        if ($event->user_id != auth()->id()) {
            return $this->sendError('You are not authorized to update this event');
        }
        
        // Check if ANY non-approved exists (Pending OR Rejected)
        $hasNonApprovedVenue = EventVenue::where('event_id', $id)
            ->where('status', '!=', 'Approved')
            ->exists();
            
        $hasNonApprovedEntertainer = EventEntertainers::where('event_id', $id)
            ->where('status', '!=', 'Approved')
            ->exists();
        
        // Check if all are approved (no pending and no rejected)
        $allApproved = (!$hasNonApprovedVenue && !$hasNonApprovedEntertainer);
        
        // Get approved records (cannot be changed)
        $approvedVenue = EventVenue::where('event_id', $id)->where('status', 'Approved')->first();
        $approvedEntertainerIds = EventEntertainers::where('event_id', $id)
            ->where('status', 'Approved')
            ->pluck('entertainer_details_id')
            ->toArray();
        
        // Track what got updated
        $updatedItems = [];
        
        // If all approved - only cover image allowed
        if ($allApproved) {
            // Only cover image update allowed
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $filename);
                $event->cover_image = 'public/images/' . $filename;
                $event->save();
                $updatedItems[] = 'Cover image';
            }
            
            if ($request->has('venues_id') || $request->has('entertainer_details_id')) {
                return $this->sendError('Only cover image can be updated as all requests are approved');
            }
            
            if (empty($updatedItems)) {
                return $this->sendError('No data provided for update.');
            }
            
            $result = Event::with('entertainerDetails', 'eventVenues')->find($id);
            return $this->sendSuccess('Cover image updated successfully', $result);
        }
        
        // ----- YAHAN TAB AAYE JAB KOI PENDING YA REJECTED HAI -----
        
        // 1. Update cover image (always allowed)
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $event->cover_image = 'public/images/' . $filename;
            $event->save();
            $updatedItems[] = 'Cover image';
        }
        
        // 2. Update venue provider (only if venue is not approved)
        if ($request->has('venues_id') && $hasNonApprovedVenue) {
            // Check if this specific venue is approved (locked)
            $isApproved = ($approvedVenue && $approvedVenue->venues_id == $request->venues_id);
            
            if (!$isApproved) {
                // Delete all non-approved venues (Pending + Rejected)
                EventVenue::where('event_id', $id)
                    ->where('status', '!=', 'Approved')
                    ->delete();
                
                // Add new venue with Pending status
                $eventVenue = new EventVenue();
                $eventVenue->event_id = $id;
                $eventVenue->venues_id = $request->venues_id;
                $eventVenue->status = 'Pending';
                $eventVenue->save();
                $updatedItems[] = 'Venue provider';
            }
        }
        
        // 3. Update entertainers (only non-approved ones can be changed)
        if ($request->has('entertainer_details_id') && $hasNonApprovedEntertainer) {
            $requestedEntertainers = is_array($request->entertainer_details_id) 
                ? $request->entertainer_details_id 
                : [$request->entertainer_details_id];
            
            // Filter out approved entertainers (they cannot be changed)
            $changeableEntertainers = array_diff($requestedEntertainers, $approvedEntertainerIds);
            
            if (!empty($changeableEntertainers)) {
                // Delete all non-approved entertainers (Pending + Rejected)
                EventEntertainers::where('event_id', $id)
                    ->where('status', '!=', 'Approved')
                    ->delete();
                
                // Add new entertainers with Pending status
                foreach ($changeableEntertainers as $entertainerId) {
                    $eventEntertainer = new EventEntertainers();
                    $eventEntertainer->event_id = $id;
                    $eventEntertainer->entertainer_details_id = $entertainerId;
                    $eventEntertainer->status = 'Pending';
                    $eventEntertainer->save();
                }
                $updatedItems[] = 'Entertainers';
            }
        }
        
        // Also allow updating event basic info if user wants (title, date, price etc.)
        // This is optional - if you want to allow updating event details
        if ($request->has('title') || $request->has('date') || $request->has('price') || $request->has('seats')) {
            $updateData = [];
            if ($request->has('title')) $updateData['title'] = $request->title;
            if ($request->has('about_event')) $updateData['about_event'] = $request->about_event;
            if ($request->has('event_type')) $updateData['event_type'] = $request->event_type;
            if ($request->has('date')) $updateData['date'] = $request->date;
            if ($request->has('from')) $updateData['from'] = $request->from;
            if ($request->has('to')) $updateData['to'] = $request->to;
            if ($request->has('joining_type')) $updateData['joining_type'] = $request->joining_type;
            if ($request->has('price')) $updateData['price'] = $request->price;
            if ($request->has('seats')) $updateData['seats'] = $request->seats;
            if ($request->has('description')) $updateData['description'] = $request->description;
            if ($request->has('location')) $updateData['location'] = $request->location;
            
            if (!empty($updateData)) {
                $event->update($updateData);
                $updatedItems[] = 'Event details';
            }
        }
        
        if (empty($updatedItems)) {
            return $this->sendError('No data provided for update or only approved items cannot be changed');
        }
        
        // Build success message
        $message = implode(', ', $updatedItems) . ' updated successfully';
        
        $result = Event::with('entertainerDetails', 'eventVenues')->find($id);
        return $this->sendSuccess($message, $result);
        
    } catch (\Exception $e) {
        return $this->sendError('Error: ' . $e->getMessage() . ' on line ' . $e->getLine());
    }
}

// Helper function to send FCM notification
private function sendFCMNotification($fcm_token, $title, $body, $dataArray)
{
    if (empty($fcm_token)) {
        return false;
    }
    
    $SERVER_API_KEY = 'AAAA1U9GgKM:APA91bE_zPJEiZ6IBj_RcFwN_xzOSB7v2osZC9DcWSoYi7nPaDUdPOZRndvEnBiq8U4RgcXaNTIQUUl6-jr5FsHRCWTXUEmbjkbk5myWI_7YYif7Rj9uCqdAwPUoAEmExXkeRVeemhNo';
    
    $data = [
        'to' => $fcm_token,
        'notification' => [
            'title' => $title,
            'body' => $body,
        ],
        'data' => [
            'RequestData' => $dataArray,
        ],
        'content_available' => true,
    ];
    
    $dataString = json_encode($data);
    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}
    // delete event
    public function delete_event($id)
    {
        Event::find($id)->delete();
        $data = Event::with('User')->where('user_id', Auth::id())->orderBy('avg_rating', 'DESC')->get();
        return $this->sendSuccess('Event Delete Successfully', compact('data'));
    }
    // Get Event Feature Packages
    public function getEventFeaturePackages()
    {

        $data = EventFeatureAdsPackage::get();
        return $this->sendSuccess('Event Ads Packages', compact('data'));
    }
    // Event Select Package
    public function EventSelectPackage(Request $request)
    {
        Event::where('user_id', Auth::id())->update([
            'event_feature_ads_packages_id' => $request->id,
        ]);
        $data = Event::where('user_id', Auth::id())->first();
        return $this->sendSuccess('Event Featured Request Successfully', compact('data'));
    }
    // join Event
    // public function joinEvent(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'surname' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendError($validator->errors()->first());
    //     }
    //     $event = Event::find($request->event_id);
    //     $eventTickets = EventTicket::where('event_id', $request->event_id)->count();
    //     if ($event->seats <= $eventTickets) {
    //         return $this->sendError('Enough Tickets');
    //     } else {

    //         $last_record = EventTicket::orderby('id', 'DESC')->limit(1)->first();
    //         if (isset($last_record)) {
    //             $ticket = EventTicket::where('user_id', Auth::id())->where('event_id', $request->event_id)->first();
    //             if (isset($ticket)) {
    //                 return $this->sendError('You already have purchased ticket for this event');
    //             // } else {
    //             //     try {
    //             //         Stripe::setApiKey(env('STRIPE_SECRET'));
    //             //         // get sender data
    //             //         $sender = User::find(Auth::id());

    //             //         // Apply check on sender (customer or not)
    //             //         if ($sender->customer_id != Null) {
    //             //             Charge::create([
    //             //                 "amount" => 100 * $event->price,
    //             //                 "currency" => "usd",
    //             //                 "customer" => $sender->customer_id,
    //             //                 "description" => "Test payment from" . ' ' . $sender->name,
    //             //                 "shipping" => [
    //             //                     "name" => $sender->name,
    //             //                     "address" => [
    //             //                         "line1" => $sender->city,
    //             //                         "postal_code" => "98140",
    //             //                         "city" => $sender->city,
    //             //                         "state" => "PUNJAB",
    //             //                         "country" => $sender->country,
    //             //                     ],
    //             //                 ],
    //             //             ]);
    //             //         } else {
    //             //             $customer = Customer::create([
    //             //                 "address" => [
    //             //                     "line1" => $sender->city,
    //             //                     "postal_code" => "360001",
    //             //                     "city" => $sender->city,
    //             //                     "state" => "PUNJAB",
    //             //                     "country" => $sender->country,
    //             //                 ],
    //             //                 "email" => $sender->email,
    //             //                 "name" => $sender->name,
    //             //                 "source" => $request->token,
    //             //             ]);
    //             //             Charge::create([
    //             //                 "amount" => 100 * $event->price,
    //             //                 "currency" => "usd",
    //             //                 "customer" => $customer->id,
    //             //                 "description" => "Test payment from" . ' ' . $sender->name,
    //             //                 "shipping" => [
    //             //                     "name" => $sender->name,
    //             //                     "address" => [
    //             //                         "line1" => "510 Townsend St",
    //             //                         "postal_code" => "98140",
    //             //                         "city" => $sender->city,
    //             //                         "state" => "PUNJAB",
    //             //                         "country" => $sender->country,
    //             //                     ],
    //             //                 ],
    //             //             ]);
    //             //             // update customer id in sender record
    //             //             // dd($customer->id);
    //             //             User::find($request->sender_id)->update(['customer_id' => $customer->id]);
    //             //         }
    //             //         Session::flash('success', 'Payment successful!');
    //             //     } catch (ApiErrorException $e) {
    //             //         Session::flash('error', 'Payment failed: ' . $e->getMessage());
    //             //         return $this->sendError($e->getMessage());
    //             //     }
    //                 $data = $request->only(['name', 'surname', 'age', 'ticket_type', 'gender', 'phone', 'email']);
    //                 $data['user_id'] = auth()->id();
    //                 $data['event_id'] = $request->event_id;
    //                 do {
    //                 $serialno = mt_rand(1000, 9999);
    //                 } while (EventTicket::where('event_id', $request->event_id)->where('serial_no', $serialno)->exists());
    //                 $data['serial_no'] = $serialno;
    //                 $data['qr_code'] = Str::random(30);
    //                 if ($request->hasfile('photo')) {
    //                     $file = $request->file('photo');
    //                     $extension = $file->getClientOriginalExtension(); // getting image extension
    //                     $filename = time() . '.' . $extension;
    //                     $file->move(public_path('images'), $filename);
    //                     $data['photo'] = 'public/images/' . $filename;
    //                 }
    //                 $dataa = EventTicket::create($data);
    //                 Mail::to($dataa->User->email)->send(new JoinEvent($dataa));
    //                 $payment = new Payment();
    //                 $payment->sender_id = Auth::id();
    //                 $payment->event_id = $request->event_id;
    //                 $payment->ticket_id = $dataa->id;
    //                 $payment->type = $request->type;
    //                 $payment->save();
    //                 $data = EventTicket::find($dataa->id);
    //                 return $this->sendSuccess('Event Ticket created Successfully', compact('data'));
    //             }
    //         } else {
    //             $ticket = EventTicket::where('user_id', Auth::id())->where('event_id', $request->event_id)->first();
    //             if (isset($ticket)) {
    //                 return $this->sendError('You already have purchased ticket for this event');
    //             } else {


    //                 try {
    //                     Stripe::setApiKey(env('STRIPE_SECRET'));
    //                     // get sender data
    //                     $sender = User::find(Auth::id());

    //                     // Apply check on sender (customer or not)
    //                     if ($sender->customer_id != Null) {
    //                         Charge::create([
    //                             "amount" => 100 * $event->price,
    //                             "currency" => "usd",
    //                             "customer" => $sender->customer_id,
    //                             "description" => "Test payment from" . ' ' . $sender->name,
    //                             "shipping" => [
    //                                 "name" => $sender->name,
    //                                 "address" => [
    //                                     "line1" => $sender->city,
    //                                     "postal_code" => "98140",
    //                                     "city" => $sender->city,
    //                                     "state" => "PUNJAB",
    //                                     "country" => $sender->country,
    //                                 ],
    //                             ],
    //                         ]);
    //                     } else {
    //                         $customer = Customer::create([
    //                             "address" => [
    //                                 "line1" => $sender->city,
    //                                 "postal_code" => "360001",
    //                                 "city" => $sender->city,
    //                                 "state" => "PUNJAB",
    //                                 "country" => $sender->country,
    //                             ],
    //                             "email" => $sender->email,
    //                             "name" => $sender->name,
    //                             // "source" => $request->token,
    //                         ]);
    //                         Charge::create([
    //                             "amount" => 100 * $event->price,
    //                             "currency" => "usd",
    //                             "customer" => $customer->id,
    //                             "description" => "Test payment from" . ' ' . $sender->name,
    //                             "shipping" => [
    //                                 "name" => $sender->name,
    //                                 "address" => [
    //                                     "line1" => "510 Townsend St",
    //                                     "postal_code" => "98140",
    //                                     "city" => $sender->city,
    //                                     "state" => "PUNJAB",
    //                                     "country" => $sender->country,
    //                                 ],
    //                             ],
    //                         ]);
    //                         // update customer id in sender record
    //                         User::find($request->sender_id)->update(['customer_id' => $customer->id]);
    //                     }
    //                     Session::flash('success', 'Payment successful!');
    //                 } catch (ApiErrorException $e) {
    //                     Session::flash('error', 'Payment failed: ' . $e->getMessage());
    //                     return $this->sendError($e->getMessage());
    //                 }
    //                 $data = $request->only(['name', 'surname', 'age', 'ticket_type', 'gender', 'phone', 'email']);
    //                 $data['user_id'] = auth()->id();
    //                 $data['event_id'] = $request->event_id;
    //                 do {
    //                 $serialno = mt_rand(1000, 9999);
    //                 } while (EventTicket::where('event_id', $request->event_id)->where('serial_no', $serialno)->exists());
    //                 $data['serial_no'] = $serialno;
    //                 $data['qr_code'] = Str::random(30);
    //                 if ($request->hasfile('photo')) {
    //                     $file = $request->file('photo');
    //                     $extension = $file->getClientOriginalExtension(); // getting image extension
    //                     $filename = time() . '.' . $extension;
    //                     $file->move(public_path('images'), $filename);
    //                     $data['photo'] = 'public/images/' . $filename;
    //                 }
    //                 $dataa = EventTicket::create($data);
    //                 Mail::to($dataa->User->email)->send(new JoinEvent($dataa));
    //                 $payment = new Payment();
    //                 $payment->sender_id = Auth::id();
    //                 $payment->event_id = $request->event_id;
    //                 $payment->ticket_id = $dataa->id;
    //                 $payment->type = $request->type;
    //                 $payment->payment = $event->price;
    //                 $payment->save();
    //                 $data = EventTicket::find($dataa->id);
    //                 return $this->sendSuccess('Event Ticket created Successfully', compact('data'));
    //             }
    //         }
    //     }
    // }
    public function joinEvent(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'surname' => 'required',
    ]);

    if ($validator->fails()) {
        return $this->sendError($validator->errors()->first());
    }

    $event = Event::find($request->event_id);
    if (!$event) {
        return $this->sendError('Event not found');
    }

    $eventTickets = EventTicket::where('event_id', $request->event_id)->count();
    if ($event->seats <= $eventTickets) {
        return $this->sendError('No more tickets available for this event');
    }

    $existingTicket = EventTicket::where('user_id', Auth::id())
        ->where('event_id', $request->event_id)
        ->first();

    if ($existingTicket) {
        return $this->sendError('You already have a ticket for this event');
    }

    // ✅ Continue ticket creation
    $data = $request->only(['name', 'surname', 'age', 'ticket_type', 'gender', 'phone', 'email']);
    $data['user_id'] = Auth::id();
    $data['event_id'] = $request->event_id;

    do {
        $serialno = mt_rand(1000, 9999);
    } while (EventTicket::where('event_id', $request->event_id)->where('serial_no', $serialno)->exists());

    $data['serial_no'] = $serialno;
    

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        $data['photo'] = 'public/images/' . $filename;
    }

    // Create Event Ticket
    $ticket = EventTicket::create($data);

    $qrPath = public_path('qrcodes/');
    if (!file_exists($qrPath)) mkdir($qrPath, 0777, true);

    $qrToken = Str::random(32); // token to embed in QR
    $qrImageName = 'qr_' . $ticket->id . '.png';
    $qrFilePath = $qrPath . DIRECTORY_SEPARATOR . $qrImageName;

    $writer = new PngWriter();
    $qrCode = new QrCode(
        data: $qrToken,
        size: 500,
        margin: 10,
        foregroundColor: new Color(0, 0, 0),
        backgroundColor: new Color(255, 255, 255)
    );

    // Save QR as image
    $writer->write($qrCode)->saveToFile($qrFilePath);

    // Save relative path in DB
    $ticket->update([
        'qr_code' => 'qrcodes/' . $qrImageName,
        'qr_token' => $qrToken,
    ]);

    

    // Create Payment Record (even if no Stripe)
    $payment = new Payment();
    $payment->sender_id = Auth::id();
    $payment->event_id = $request->event_id;
    $payment->ticket_id = $ticket->id;
    $payment->type = $request->type ?? 'free'; // or 'paid'
    $payment->payment = $event->price ?? 0;
    $payment->save();

    // ✅ Return Data Properly
    return $this->sendSuccess('Event Ticket created successfully', [
        'ticket' => $ticket,
        'payment' => $payment,
    ]);
}

    // My Booking Event
    public function myBookingEvent()
    {

        $authUser = User::find(Auth::id());
        if ($authUser->role == 'recruiter') {
            $data = User::with('eventTicket.event')->find(Auth::id());
            return $this->sendSuccess('Booked event', compact('data'));
        } elseif ($authUser->role == 'entertainer') {
            $data = User::with('eventTicket.event')->find(Auth::id());
            $user = User::with('entertainerDetail.events', 'entertainerDetail.talentCategory')->find(Auth::id());
            return $this->sendSuccess('Booked event', compact('data', 'user'));
        } elseif ($authUser->role == 'venue_provider') {
            $data = User::with('eventTicket.event')->find(Auth::id());
            $user = User::with('venues.events', 'venues.venueCategory')->find(Auth::id());
            return $this->sendSuccess('Booked event', compact('data', 'user'));
        }
    }
    // My Booking
    public function myBooking()
    {
        $data = Event::with('entertainerDetails.talentCategory', 'entertainerDetails.reviews', 'eventVenues.venueCategory', 'eventVenues.reviews')->where('user_id', Auth::id())->get();
        if (isset($data)) {
            return $this->sendSuccess('My Booking', compact('data'));
        } else {
            return $this->sendError('Record Not Found !');
        }
    }
    //Get  event by (id)
public function event($id)
{
    $event = Event::with('User', 'entertainerDetails.talentCategory', 'eventVenues.venueCategory', 'eventVenues.venuePhoto', 'eventVenues.venuePricing', 'eventVenues.User')->find($id);
    
    // Check if ALL entertainers and venue providers are approved
    $allApproved = true;
    
    // Check entertainers
    foreach ($event->entertainerDetails as $entertainer) {
        if (isset($entertainer->pivot->status) && $entertainer->pivot->status != 'Approved') {
            $allApproved = false;
            break;
        }
    }
    
    // Check venues
    foreach ($event->eventVenues as $venue) {
        if (isset($venue->pivot->status) && $venue->pivot->status != 'Approved') {
            $allApproved = false;
            break;
        }
    }
    
    // Set status based on approval
    if ($allApproved && (count($event->entertainerDetails) > 0 || count($event->eventVenues) > 0)) {
        $event->owner_status = "Available";
        $event->status_message = "Your event is available for booking.";
    } else {
        $event->owner_status = "Approval Pending";
        $event->status_message = "Your event is pending. Whenever your entertainer and venue provider are approved, your event will be published.";
    }
    
    if (Auth::user()->role == "entertainer") {
        $paymentRecord = Payment::where('event_id', $id)->where('entertainer_id', Auth::id())->get();
        $payment = $paymentRecord->sum('payment');
    } elseif (Auth::user()->role == "venue_provider") {
        $paymentRecord = Payment::where('event_id', $id)->where('venue_id', Auth::id())->get();
        $payment = $paymentRecord->sum('payment');
    } else {
        $payment = Null;
    }
    
    return $this->sendSuccess('Events', compact('event', 'payment'));
}
    // my tickets
    public function myTicket()
    {
        $data =  EventTicket::with('event', 'user')->where('user_id', Auth::id())->get();
        return $this->sendSuccess('All Tickets', compact('data'));
    }
    // my history
    public function myHistory()
    {
        $a = Carbon::now()->format('Y-m-d');
        $event = Event::where('date', '<', $a)->pluck('id')->toarray();
        $data =  EventTicket::with('event')->whereIn('event_id', $event)->where('user_id', Auth::id())->get();
        return $this->sendSuccess('History', compact('data'));
    }
    // upcoming event
    public function upComingEvent()
    {
        $a = Carbon::now()->format('Y-m-d');
        $event = Event::where('date', '>=', $a)->pluck('id')->toarray();
        $data =  EventTicket::with('event')->whereIn('event_id', $event)->where('user_id', Auth::id())->get();
        return $this->sendSuccess('Up Coming Events', compact('data'));
    }

public function getEntertainerApprovedEvents($user_id,$status)
{
    // Check if user exists
    $user = User::find($user_id);
    
    if (!$user) {
        return $this->sendError('User not found');
    }
    if(empty($status)){
        return $this->sendError('Status is required. Please provide "Approved" or "Pending"');
    }
    if($status != 'Approved' && $status != 'Pending'){
        return $this->sendError('Invalid status. Only "Approved" and "Pending" are allowed');
    }
    
    // Check if user role is entertainer
    if ($user->role != 'entertainer') {
        return $this->sendError('User is not an entertainer');
    }
        $entertainers = EntertainerDetail::where('user_id', $user_id)
            ->latest()
            ->with(['talentCategory', 'reviews', 'entertainerEventPhotos', 'entertainerPricePackage'])
            ->get();

        if ($entertainers->isEmpty()) {
            return $this->sendError('No entertainer details found for this user');
        }

        // Step 2: Get all entertainer_detail IDs
        $entertainerIds = $entertainers->pluck('id');

        // Step 3: Get ALL approved events in ONE query
        $approvedEvents = EventEntertainers::whereIn('entertainer_details_id', $entertainerIds)
            ->where('status', $status)
            ->with(['event' => function ($query) {
                $query->with('User', 'eventVenues.venueCategory', 'eventVenues.venuePhoto');
            }])
            ->get()
            ->map(function ($item) {
                return [
                    'event_entertainer_id' => $item->id,
                    'event_id' => $item->event_id,
                    'event_title' => $item->event->title ?? 'N/A',
                    'event_date' => $item->event->date ?? 'N/A',
                    'event_location' => $item->event->location ?? 'N/A',
                    'venue_name' => optional($item->event->eventVenues->first())->venueCategory->title ?? 'N/A',
                    'status' => $item->status,
                    'approved_at' => $item->updated_at,
                    'event_details' => $item->event
                ];
            });

        // Optional: remove duplicate events (if same event appears multiple times)
        $approvedEvents = $approvedEvents->unique('event_id')->values();

        // Step 4: Final response
        return $this->sendSuccess('All events of entertainer', [
            'user_details' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_verify' => $user->is_verify,
                'is_login' => $user->is_login,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ],
            'entertainer_details_count' => $entertainers->count(),
            'events_count' => $approvedEvents->count(),
            'events' => $approvedEvents
        ]);
    }

    public function getVenueProviderApprovedEvents($user_id,$status)
    {
        // Check if user exists
        $user = User::find($user_id);
        
        if (!$user) {
            return $this->sendError('User not found');
        }
        
        if(empty($status)){
            return $this->sendError('Status is required. Please provide "Approved" or "Pending"');
        }
        if($status != 'Approved' && $status != 'Pending'){
            return $this->sendError('Invalid status. Only "Approved" and "Pending" are allowed');
        }
        // Check if user role is venue_provider
        if ($user->role != 'venue_provider') {
            return $this->sendError('User is not a venue provider');
        }
        
        // Get the single venue of this venue provider
        $venue = Venue::where('user_id', $user_id)->first();
        
        if (!$venue) {
            return $this->sendError('No venue found for this venue provider');
        }
        
        // Get all approved events for this venue
        $approvedEvents = EventVenue::where('venues_id', $venue->id)
            ->where('status', $status)
            ->with(['event' => function($query) {
                $query->with('User', 'entertainerDetails.talentCategory');
            }])
            ->get()
            ->map(function($item) {
                return [
                    'event_venue_id' => $item->id,
                    'event_id' => $item->event_id,
                    'event_title' => $item->event->title ?? 'N/A',
                    'event_date' => $item->event->date ?? 'N/A',
                    'event_location' => $item->event->location ?? 'N/A',
                    'entertainers_count' => $item->event->entertainerDetails->count() ?? 0,
                    'status' => $item->status,
                    'approved_at' => $item->updated_at,
                    'event_details' => $item->event
                ];
            });
        
        if ($approvedEvents->isEmpty()) {
            return $this->sendError('No approved events found for this venue');
        }
        
        return $this->sendSuccess('Venue provider approved events', [
            'user_id' => $user_id,
            'user_name' => $user->name,
            'venue_id' => $venue->id,
            'venue_title' => $venue->title,
            'venue_address' => $venue->address,
            'approved_events' => $approvedEvents
        ]);
    }
    // QR Code Scanned
    public function scanQr(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'qr_code' => 'required',
            'event_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $ticket = EventTicket::where('event_id', $request->event_id)->where('qr_code', $request->qr_code)->first();
        if (isset($ticket)) {
            if ($ticket->participated == 0) {
                $ticket->participated = 1;
                $ticket->save();
                $participant = EventTicket::where('event_id', $request->event_id)->where('participated', 1)->count();
                $total_Tickets = EventTicket::where('event_id', $request->event_id)->count();
                $remaining_tickets = EventTicket::where('event_id', $request->event_id)->where('participated', 0)->count();
                return $this->sendSuccess('Ticket scanned successfully', compact('participant', 'total_Tickets', 'remaining_tickets'));
            } else {
                $participant = EventTicket::where('event_id', $request->event_id)->where('participated', 1)->count();
                $total_Tickets = EventTicket::where('event_id', $request->event_id)->count();
                $remaining_tickets = EventTicket::where('event_id', $request->event_id)->where('participated', 0)->count();
                return $this->sendSuccess('Ticket already scanned', compact('participant', 'total_Tickets', 'remaining_tickets'));
            }
        } else {
            $participant = EventTicket::where('event_id', $request->event_id)->where('participated', 1)->count();
            $total_Tickets = EventTicket::where('event_id', $request->event_id)->count();
            $remaining_tickets = EventTicket::where('event_id', $request->event_id)->where('participated', 0)->count();
            return $this->sendSuccess('Ticket not found !', compact('participant', 'total_Tickets', 'remaining_tickets'));
        }
        // foreach ($tickets as $ticket) {
        //     $data = EventTicket::where('qr_code', $request->qr_code)->first();
        //     if (isset($data)) {
        //         if ($data->participated == 0) {
        //             $data->participated = 1;
        //             $data->save();
        //             $participant = EventTicket::where('event_id', $request->event_id)->where('participated', 1)->count();
        //             $total_Tickets = EventTicket::where('event_id', $request->event_id)->count();
        //             $remaining_tickets = EventTicket::where('event_id', $request->event_id)->where('participated', 0)->count();
        //             return $this->sendSuccess('QR code scanned successfully', compact('participant', 'total_Tickets', 'remaining_tickets'));
        //         } else {
        //             $participant = EventTicket::where('event_id', $request->event_id)->where('participated', 1)->count();
        //             $total_Tickets = EventTicket::where('event_id', $request->event_id)->count();
        //             $remaining_tickets = EventTicket::where('event_id', $request->event_id)->where('participated', 0)->count();
        //             return $this->sendSuccess('Already QR code scanned', compact('participant', 'total_Tickets', 'remaining_tickets'));
        //         }
        //     } else {
        //         $participant = EventTicket::where('event_id', $request->event_id)->where('participated', 1)->count();
        //         $total_Tickets = EventTicket::where('event_id', $request->event_id)->count();
        //         $remaining_tickets = EventTicket::where('event_id', $request->event_id)->where('participated', 0)->count();
        //         return $this->sendSuccess('Not Found !', compact('participant', 'total_Tickets', 'remaining_tickets'));
        //     }
        // }
    }
    // Check Ticket
    public function checkTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $event['tickets_count'] = EventTicket::where('event_id', $request->event_id)->count();
        $data = EventTicket::where('user_id', Auth::id())->where('event_id', $request->event_id)->first();
        if (isset($data)) {
            return $this->sendSuccess('Already Joined', compact('event'));
        } else {
            return $this->sendSuccess('Generated tickets for this event', compact('event'));
        }
    }
    // notification by (id)
    public function notification($id)
    {
        Notification::find($id)->update([
            'seen' => 1,
        ]);
        return $this->sendSuccess('Notification seen Successfully');
    }
}
