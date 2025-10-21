<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user', 'entertainer', 'venue', 'event')->where('type', 'guest')->get();
        return view('admin.payment.payment', compact('payments'));
    }


    public function feature()
    {
        $payments = Payment::with('user', 'talent.talentCategory', 'entertainer', 'venue.venueCategory', 'event', 'venuePackage', 'eventPackage', 'entertainerFeaturePackage')->where('type', 'feature')->get();
        return view('admin.payment.feature', compact('payments'));
    }


public function ticketPayment()
{
    $payments = \App\Models\Payment::with(['user', 'event', 'ticket'])
        ->where('type', 'ticket')
        ->selectRaw('
            sender_id, 
            event_id, 
            SUM(payment) as total_payment, 
            COUNT(id) as total_tickets, 
            MAX(created_at) as created_at, 
            MAX(status) as status,
            MAX(id) as latest_payment_id
        ')
        ->groupBy('sender_id', 'event_id')
        ->get()
        ->map(function($payment) {
            // ✅ MANUALLY RELATIONSHIPS ATTACH KAREN
            $payment->user = \App\Models\User::find($payment->sender_id);
            $payment->event = \App\Models\Event::find($payment->event_id);
            $payment->ticket = \App\Models\EventTicket::where('user_id', $payment->sender_id)
                ->where('event_id', $payment->event_id)
                ->first();
            return $payment;
        });

    return view('admin.payment.eventTicketPayment', compact('payments'));
}


    public function status($id)
    {
        $data = Payment::find($id);
        $data->update(['status' => $data->status == 0 ? '1' : '0']);
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }
}
