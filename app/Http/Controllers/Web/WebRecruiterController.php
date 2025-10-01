<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RecruiterAuthService;
use App\Models\EventTicket;
use Illuminate\Support\Facades\Auth;
use App\Models\VenueCategory;
use App\Models\EntertainerDetail;
use App\Models\Event;
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
        return view('web.recruiter.createevent', compact('entertainers', 'venues'));
    }

   public function store(Request $request)
{
    if ($request->hasFile('cover_image')) {
        $file = $request->file('cover_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move(public_path('images/'), $filename);
        $image = 'public/images/' . $filename;
    } else {
        $image = 'public/avator.png';
    }

    // Save event into a variable
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
        'price' => $request->price,
        'seats' => $request->seats,
        'description' => $request->description,
        'cover_image' => $image,
    ]);

    // Attach entertainers if selected
    if ($request->filled('entertainer_id')) {
        $event->entertainers()->attach($request->entertainer_id);
    }

    return redirect()->route('event.create')->with('success', 'Event created successfully');
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
        'password'    => 'nullable|min:6|confirmed',  
        'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

    return redirect()->route('profile.show', $user->id)->with('success', 'Profile Updated Successfully');
}

}
