<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RecruiterAuthService;
use App\Models\EventTicket;
use Illuminate\Support\Facades\Auth;
use App\Models\TalentCategory;
use App\Models\VenueCategory;
use App\Models\EntertainerDetail;
use App\Models\Event;
use App\Models\EventVenue;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class WebRecruiterController extends Controller
{

 public function dashboard() {
    $userId = Auth::id();
    // 1. Created Events
    $createdEvents = Event::where('user_id', $userId)->count();

    // 2. Joined Events
    $joinedEvents = Event::whereIn('id', function ($query) use ($userId) {
        $query->select('event_id')
            ->from('event_tickets')
            ->where('user_id', $userId);
    })->count();

    $eventTicket = EventTicket::where('user_id', $userId)->count();
    
        return view('web.recruiter.dashboard', compact('createdEvents', 'joinedEvents', 'eventTicket'));
    }
    
    //My Joined & Created Events 
    public function myEvents()
{
    $userId = Auth::id();

    // 1. Created Events
    $createdEvents = Event::where('user_id', $userId)->get();

    // 2. Joined Events
    $joinedEvents = Event::whereIn('id', function ($query) use ($userId) {
        $query->select('event_id')
            ->from('event_tickets')
            ->where('user_id', $userId);
    })->get();

    return view('web.recruiter.myevents', compact('createdEvents', 'joinedEvents'));
}
    //Create Event
public function create()
{
    $entertainers = EntertainerDetail::with('User')->get();
    $venues = Venue::with('venueCategory')->get();
    $categories = TalentCategory::select('id', 'category')->get();
    $venueCategories = VenueCategory::select('id', 'category')->get();
    return view('web.recruiter.createevent', compact('entertainers', 'venues', 'categories', 'venueCategories'));
}

public function getByCategory($categoryId)
{
    $venues = Venue::where('category_id', $categoryId)->get(['id', 'title']);
    return response()->json($venues);
}


public function getEntertainersByCategory(Request $request)
{
    $categoryIds = $request->category_ids ?? [];

    // Get entertainers whose category_id matches selected category
    $entertainers = EntertainerDetail::whereIn('category_id', $categoryIds)
        ->with(['user:id,name', 'talentCategory:id,category'])
        ->get()
        ->groupBy('user_id');

    $data = [];

    foreach ($entertainers as $userId => $group) {
        $entertainer = $group->first();
        $name = $entertainer->user->name ?? 'Unnamed Entertainer';
        $professions = $group->map(fn($e) => $e->talentCategory->category ?? 'N/A')->unique()->implode(', ');

        $data[] = [
            'id' => $entertainer->id,
            'name' => "{$name} ",
        ];
    }

    return response()->json($data);
}

public function store(Request $request)
{
    $rules = [
        'title' => 'required|string',
        'about_event' => 'required',
        'event_type' => 'required|string',
        'date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:date',
        'from' => 'required',
        'to' => 'required',
        'joining_type' => 'required',
        'seats' => 'required|integer',
        'description' => 'required|string',
        'cover_image' => 'required|image|mimes:jpg,jpeg,png',
    ];

    // Conditional rules
    if ($request->joining_type === 'Paid') {
        $rules['price'] = 'required|numeric|min:1';
    } else {
        $rules['price'] = 'nullable|numeric|min:0';
    }

    if ($request->event_type === 'Public') {
        $rules['venue_id'] = 'required';
        $rules['entertainer_id'] = 'required';
    } else {
        $rules['venue_id'] = 'nullable';
        $rules['entertainer_id'] = 'nullable';
    }

    $messages = [
        'date.required' => 'The start date is required.',
        'date.after_or_equal' => 'The start date must be a date after or equal to today.',
        'end_date.required' => 'The end date is required.',
        'end_date.after_or_equal' => 'The end date must be a date after or equal to start date.',
        'from.required' => 'The start time is required.',
        'to.required' => 'The end time is required.',
        'joining_type.required' => 'The joining type is required.',
        'cover_image.required' => 'The cover photo is required.',
        'cover_image.mimes' => 'The cover photo must be a file of type: jpg, jpeg, png.',
        'entertainer_id.required' => 'Please select at least one entertainer.',
        'about_event.required' => 'The event information field is required.',
        'venue_id.required' => 'The venue field is required.',
    ];

    $request->validate($rules, $messages);

    // Handle cover image
    if ($request->hasFile('cover_image')) {
        $file = $request->file('cover_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move(public_path('images/'), $filename);
        $image = 'public/images/' . $filename;
    } else {
        $image = 'public/avator.png';
    }

    // Save event
    $event = Event::create([
        'user_id' => Auth::id(),
        'title' => $request->title,
        'venue_id' => $request->venue_id,
        'about_event' => $request->about_event,
        'event_type' => $request->event_type,
        'date' => $request->date,
        'end_date' => $request->end_date,
        'from' => $request->from,
        'to' => $request->to,
        'joining_type' => $request->joining_type,
        'price' => $request->price ?? 0,
        'seats' => $request->seats,
        'description' => $request->description,
        'cover_image' => $image,
    ]);

    // Attach entertainers if provided
    if ($request->filled('entertainer_id')) {
        $event->entertainers()->attach($request->entertainer_id);
    }
    if (isset($request->venue_id)) {
            $event_venue = new EventVenue;
            $event_venue->event_id = $event->id;
            $event_venue->venues_id = $request->venue_id;
            $event_venue->save();
        }

    return redirect()->route('web.recruiter.myevents')->with('success', 'Event created successfully');
}


    // For User Ticket
        public function ticket()
    {

      // dd($tickets);

        $tickets = EventTicket::with('event')
        ->where('user_id', Auth::id()) 
        ->get();
        
        return view('web.recruiter.myticket', compact('tickets'));
    }
        public function showmyprofile($id)
    {
        $user = User::find($id);
        return view('web.recruiter.myprofile', compact('user'));
    }
   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email|unique:users,email,' . $user->id,
        'phone'       => 'required|string|max:20',
        'designation' => 'nullable|string|max:255',
        'password'    => 'nullable|min:8',
        'password_confirmation' => 'same:password',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'image.max'   => 'The image size must not exceed 2 MB.',
        'image.mimes' => 'The image must be a file of type: JPG, JPEG, or PNG.',
        'password.min' => 'The password must be at least 8 characters.',
        'password_confirmation.same' => 'The confirm password must match the password.',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $destination = 'public/images/' . $user->image;
        if ($user->image && File::exists($destination)) {
            File::delete($destination);
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/images', $filename);
        $image = 'public/images/' . $filename;
    } else {
        $image = $user->image;
    }

    // Update user
    $user->update([
        'name'        => $request->name,
        'email'       => $request->email,
        'phone'       => $request->phone,
        'designation' => $request->designation,
        'password'    => $request->filled('password') ? Hash::make($request->password) : $user->password,
        'image'       => $image,
    ]);

    return redirect()->route('profile.show', $user->id)
                     ->with('success', 'Profile Updated Successfully');
}


}
