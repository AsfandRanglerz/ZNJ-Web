<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AndroidPaymentController extends Controller
{
    private $merchantId = "ZNJEVENTSCON";
    private $apiPassword = "62ff0507b23317d047f1274867b42a07";
    private $apiUrl = "https://bankalfalah.gateway.mastercard.com/api/rest/version/74";

    /**
     * React Native App ke liye Hosted Checkout URL generate karen
     */
    public function createHostedCheckout(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
                'user_id' => 'required|integer',
                'event_id' => 'required|integer',
                'quantity' => 'sometimes|integer|min:1'
            ]);

            $orderId = uniqid("RN_");
            $amount = number_format((float) $request->amount, 2, '.', '');
            $userId = $request->user_id;
            $eventId = $request->event_id;
            $quantity = $request->quantity ?? 1;

            Log::info("🎯 Creating hosted checkout for React Native", [
                'order_id' => $orderId,
                'amount' => $amount,
                'user_id' => $userId,
                'event_id' => $eventId,
                'quantity' => $quantity
            ]);

            // ✅ STEP 1: Initiate checkout (NEW method)
            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->post($this->apiUrl . "/merchant/" . $this->merchantId . "/session", [
                    "apiOperation" => "INITIATE_CHECKOUT",
                    "interaction" => [
                        "operation" => "PURCHASE",
                        "merchant" => [
                            "name" => "ZNJ Events",
                            "address" => [
                                "line1" => "Pakistan"
                            ]
                        ]
                    ],
                    "order" => [
                        "id" => $orderId,
                        "amount" => $amount,
                        "currency" => "PKR",
                        "description" => "Event Payment"
                    ]
                ]);

            $sessionData = $response->json();

            if (!isset($sessionData['session']['id'])) {
                Log::error("❌ INITIATE_CHECKOUT failed", $sessionData);
                return response()->json([
                    'success' => false,
                    'error' => 'Checkout initiation failed: ' . ($sessionData['error']['explanation'] ?? 'Unknown error')
                ], 400);
            }

            $sessionId = $sessionData['session']['id'];
            Log::info("✅ Session created successfully", ['session_id' => $sessionId]);

            // ✅ Store temporary info
            \App\Models\PaymentTemp::create([
                'order_id' => $orderId,
                'session_id' => $sessionId,
                'user_id' => $userId,
                'event_id' => $eventId,
                'amount' => $amount,
                'quantity' => $quantity,
            ]);

            // ✅ Hosted checkout URL (for app webview)
            $checkoutPageUrl = url('/gateway/pay/' . $orderId . '?session_id=' . $sessionId);

            return response()->json([
                'success' => true,
                'checkout_url' => $checkoutPageUrl,
                'session_id' => $sessionId,
                'order_id' => $orderId,
                'amount' => $amount,
                'currency' => 'PKR',
                'message' => 'Use this URL in WebView for payment processing'
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Hosted Checkout Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Payment Status Check karen
     */
    public function checkPaymentStatus(Request $request)
    {
        $orderId = $request->order_id;

        try {
            if (empty($orderId)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Order ID is required'
                ], 400);
            }

            $url = $this->apiUrl . "/merchant/" . $this->merchantId . "/order/" . $orderId;

            Log::info("🔍 Checking payment status:", ['url' => $url]);

            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->get($url);

            $data = $response->json();
            Log::info("📥 Payment Status Response:", $data);

            // ✅ Agar payment successful hai to tickets generate karen
            if (isset($data['result']) && $data['result'] === 'SUCCESS') {
                $this->handleSuccessfulPayment($orderId);
            }

            return response()->json([
                'success' => true,
                'payment_status' => $data['status'] ?? 'UNKNOWN',
                'result' => $data['result'] ?? 'UNKNOWN',
                'order_id' => $orderId,
                'gateway_response' => $data
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Payment Status Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Successful payment handle karen - PaymentTemp se EventTickets aur Payment create karen
     */
    private function handleSuccessfulPayment($orderId)
    {
        try {
            // ✅ PaymentTemp se data fetch karen
            $paymentTemp = \App\Models\PaymentTemp::where('order_id', $orderId)->first();

            if (!$paymentTemp) {
                Log::error("❌ PaymentTemp not found for successful payment", ['order_id' => $orderId]);
                return false;
            }

            // ✅ Check if already processed
            $existingPayment = \App\Models\Payment::where('transaction_id', $orderId)->first();
            if ($existingPayment) {
                Log::info("✅ Tickets already generated for this payment", ['order_id' => $orderId]);
                return true;
            }

            $userId = $paymentTemp->user_id;
            $eventId = $paymentTemp->event_id;
            $quantity = intval($paymentTemp->quantity ?? 1);
            $amount = floatval($paymentTemp->amount);

            Log::info("✅ Payment successful, generating tickets", [
                'order_id' => $orderId,
                'user_id' => $userId,
                'event_id' => $eventId,
                'quantity' => $quantity,
                'amount' => $amount
            ]);

            // ✅ User aur Event check karen
            $user = \App\Models\User::find($userId);
            $event = \App\Models\Event::find($eventId);

            if (!$user || !$event) {
                throw new \Exception('User or Event not found.');
            }

            // ✅ QR folder create karen
            $qrPath = public_path('qrcodes/');
            if (!file_exists($qrPath)) {
                mkdir($qrPath, 0777, true);
            }

            // ✅ Individual ticket price calculate karen
            $individualPrice = $quantity > 0 ? ($amount / $quantity) : $amount;

            // ✅ Multiple tickets generate karen
            for ($i = 1; $i <= $quantity; $i++) {
                $serialno = mt_rand(1000, 9999);
                $qrToken = Str::random(32);

                // EventTicket create karen
                $ticket = \App\Models\EventTicket::create([
                    'user_id'   => $userId,
                    'event_id'  => $eventId,
                    'name'      => $user->name ?? 'Android User',
                    'phone'     => $user->phone ?? 'N/A',
                    'email'     => $user->email ?? 'android@example.com',
                    'serial_no' => $serialno,
                    'price'     => $individualPrice,
                    'quantity'  => 1,
                ]);

                // QR code generate karen (simple version - GD extension ke bina)
                $qrImageName = 'qr_android_' . $ticket->id . '.png';
                $qrFile = $qrPath . $qrImageName;
                file_put_contents($qrFile, $qrToken);
                
                $ticket->update(['qr_code' => 'qrcodes/' . $qrImageName]);

                // Payment record create karen
                \App\Models\Payment::create([
                    'sender_id'      => $userId,
                    'event_id'       => $eventId,
                    'ticket_id'      => $ticket->id,
                    'payment'        => $individualPrice,
                    'transaction_id' => $orderId,
                    'type'           => 'ticket',
                    'status'         => '1',
                ]);

                Log::info("🎫 Android Ticket #{$i} Created:", [
                    'ticket_id' => $ticket->id,
                    'price' => $ticket->price
                ]);
            }

            // ✅ PaymentTemp delete karen
            $paymentTemp->delete();

            Log::info("✅ Android Payment successful — {$quantity} ticket(s) created for Order {$orderId}");

            return true;

        } catch (\Exception $e) {
            Log::error("❌ Handle Successful Payment Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Direct Payment Callback (Agar bank directly callback kare)
     */
    public function paymentCallback(Request $request)
    {
        Log::info('🔴 ANDROID PAYMENT CALLBACK STARTED', $request->all());

        $result = $request->get('result');
        $orderId = $request->get('order_id');

        if (strtoupper($result) !== 'SUCCESS') {
            return response()->json([
                'success' => false,
                'error' => 'Payment failed or cancelled.'
            ], 400);
        }

        try {
            // ✅ Tickets generate karen
            $success = $this->handleSuccessfulPayment($orderId);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful and tickets generated!',
                    'order_id' => $orderId
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Payment successful but ticket creation failed.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('❌ Android Payment Callback Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Payment callback processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test API
     */
    public function testApi(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Android Payment Controller is working!',
            'timestamp' => now(),
            'endpoints' => [
                'POST /create-hosted-checkout',
                'POST /check-payment-status', 
                'POST /payment-callback'
            ]
        ]);
    }
}