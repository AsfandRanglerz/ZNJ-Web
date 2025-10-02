<?php

namespace App\Http\Controllers\Web;

use App\Models\Event;
use App\Models\Venue;
use App\Models\EventTicket;
use Illuminate\Http\Request;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Builder\Builder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



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
    $request->validate([
        'name'    => 'nullable|string',
        'surname' => 'nullable|string',
        'age'     => 'required|integer',
        'phone'   => 'required|integer',
        'email'   => 'required|email|max:100',
        'photo'   => 'required|image|mimes:jpeg,png,jpg,gif,svg',
    ],
    [
        'age.required'     => 'The age field is required.',
        'phone.required'   => 'The phone number field is required.',
        'phone.integer'    => 'The phone number must be an  integer.',
        'email.required'   => 'The email field is required.',
        'photo.required'   => 'The id field is required.',
        'photo.mimes'      => 'The id must be a file of type: jpeg, png, jpg, gif, svg.',
    ]);
    $event = Event::findOrFail($id);

    $bookedTickets = EventTicket::where('event_id', $event->id)->count();

    // ✅ Requested quantity
    $quantity = (int) $request->input('quantity', 1);

    // ✅ Check available seats
    $availableSeats = $event->seats - $bookedTickets;

    if ($quantity > $availableSeats) {
        return back()->withErrors([
            'quantity' => "Only {$availableSeats} seat(s) left for this event."
        ])->withInput();
    }

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
     $qrPath = public_path('qrcodes/');
    if (!file_exists($qrPath)) {
        mkdir($qrPath, 0777, true);
    }
     
    for ($i = 0; $i < $quantity; $i++) {
        $serialno = mt_rand(1000, 9999);

        $ticket = EventTicket::create([
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
        $ticketUrl = url('/ticket/' . $ticket->id);
        $qrImageName = 'qr_' . $ticket->id . '.png';

        Builder::create()
            ->data($ticketUrl)
            ->size(500)
            ->margin(10)
            ->backgroundColor(new Color(255, 255, 255)) // white
            ->foregroundColor(new Color(0, 0, 0))       // black
            ->build()
            ->saveToFile(public_path('qrcodes/' . $qrImageName));

        $ticket->update([
            'qr_code' => 'qrcodes/' . $qrImageName
        ]);
    }

    return redirect()
        ->route('web.thankyou', $event->id)
        ->with('success', "Ticket(s) generated successfully");
}
}