<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Venue;
use App\Models\EventTicket;


class EventController extends Controller
{
public function searchEvent(Request $request)
{
    $query = Event::with('venue');

    if ($request->filled('search')) {
        $query->where('title', 'LIKE', '%' . $request->search . '%');
    }

    if ($request->filled('date')) {
        $query->whereDate('date', $request->date);
    }

    if ($request->filled('city')) {
        $query->whereHas('venue', function($q) use ($request) {
            $q->where('location', $request->city);
        });
    }

    $events = $query->orderBy('date', 'desc')->paginate(8);

    $cities = Venue::select('location')->distinct()->pluck('location');

    return view('web.event', compact('events','cities'));
}
public function eventDetail($id)
{
    $event = Event::with([
        'organizer',                     
        'entertainers',       
        'venue.venuecategory', 
        'entertainers.talentCategory',                         
        // 'eventVenues.venue',             
        'reviews.user',                
    ])->findOrFail($id);

   $allEvents = Event::where('title', $event->title) 
    ->where('id', '!=', $event->id)             
    ->paginate(8);


    return view('web.eventdetail', compact('event', 'allEvents'));
}

public function generateTicket(Request $request, $id)
{
    $event = Event::findOrFail($id);
    $quantity = (int) $request->query('quantity', 1);
    
    return view('web.generateticket', compact('event', 'quantity'));


}
public function createTicket(Request $request, $id)
{
    $event = Event::findOrFail($id);

    // Handle image once
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move(public_path('images/'), $filename);
        $image = 'public/images/' . $filename;
    } else {
        $image = 'public/avator.png';
    }

    $quantity = $request->input('quantity', 1);

    for ($i = 0; $i < $quantity; $i++) {
        $serialno = mt_rand(1000, 9999);

        EventTicket::create([
            'user_id'   => Auth::id(),
            'event_id'  => $event->id,
            'name'      => $request->name,
            'surname'   => $request->surname,
            'age'       => $request->age,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'photo'     => $image,
            'gender'    => $request->gender,
            'serial_no' => $serialno,
        ]);
    }

    return redirect()
        ->route('web.thankyou', $event->id)
        ->with('success', "Ticket(s) generated successfully");
}
}