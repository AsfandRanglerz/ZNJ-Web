<?php

namespace App\Http\Controllers\Api;

use App\Models\EventTicket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function scanQr(Request $request)
    {
        

        // Find the ticket with this QR code
        $ticket = EventTicket::where('qr_token', $request->qr_token)->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid QR code.'
            ], 404);
        }

        if ($ticket->is_qr_expired) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been used.'
            ], 400);
        }

        // Expire the QR code
        $ticket->is_qr_expired = true;
        $ticket->save();

        return response()->json([
            'status' => 'success',
            'message' => 'QR code verified successfully.',
            'ticket_id' => $ticket->id,
        ]);
    }
}
