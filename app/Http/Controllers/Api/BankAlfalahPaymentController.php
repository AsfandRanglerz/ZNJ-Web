<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BankAlfalahPaymentController extends Controller
{
    // ✅ MOST LIKELY CORRECT FOR LIVE
    private $merchantId = "ZNJEVENTSCON";
    private $apiPassword = "62ff0507b23317d047f1274867b42a07"; // API Key
    private $apiUrl = "https://bankalfalah.gateway.mastercard.com/api/rest/version/74";
    /**
     * Step 1 + 2: Create & Update Session
     */
    public function createCheckoutSession(Request $request)
    {
        $orderId = uniqid("ORDER_");
        $amount = floatval($request->amount ?? 100.00);

        try {
            // Step 1: Create session
            $sessionResponse = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->post($this->apiUrl . "/merchant/" . $this->merchantId . "/session", [
                    "apiOperation" => "CREATE_CHECKOUT_SESSION"
                ]);

            $sessionData = $sessionResponse->json();
            if (!isset($sessionData['session']['id'])) {
                return response()->json(['success' => false, 'error' => 'Session creation failed', 'details' => $sessionData], 400);
            }

            $sessionId = $sessionData['session']['id'];

            // Step 2: Update session
            $updateResponse = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->put($this->apiUrl . "/merchant/" . $this->merchantId . "/session/" . $sessionId, [
                    "order" => [
                        "id" => $orderId,
                        "amount" => $amount,
                        "currency" => "PKR",
                        "description" => "Payment for order"
                    ]
                ]);

            $updateData = $updateResponse->json();
            if (!isset($updateData['session']['updateStatus']) || $updateData['session']['updateStatus'] !== "SUCCESS") {
                return response()->json(['success' => false, 'error' => 'Session update failed', 'details' => $updateData], 400);
            }

            return response()->json([
                'success'    => true,
                'session_id' => $sessionId,
                'order_id'   => $orderId,
                'amount'     => $amount,
                'currency'   => "PKR"
            ]);
        } catch (\Exception $e) {
            Log::error("Session Error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show checkout page
     */
    public function showCheckoutPage(Request $request, $event_id)
    {
        $event = \App\Models\Event::findOrFail($event_id);

        $amount = floatval($request->amount ?? $event->price ?? 100.00);

        $sessionResponse = $this->createCheckoutSession(new Request([
            'amount' => $amount,
            'email'  => Auth()->user()->email ?? 'customer@example.com',
        ]));

        $sessionData = json_decode($sessionResponse->getContent(), true);

        if (!$sessionData['success']) {
            return "Session creation failed: " . json_encode($sessionData);
        }

        return view('checkout', [
            'session_id' => $sessionData['session_id'],
            'order_id'   => $sessionData['order_id'],
            'amount'     => $sessionData['amount'],
            'currency'   => $sessionData['currency'],
            'event'      => $event,
        ]);
    }


    /**
     * Step 3: Initiate Authentication (3DS)
     */
    public function initiateAuthentication(Request $request)
    {
        try {
            $sessionId = $request->session_id;
            $orderId   = $request->order_id;
            $correlationId = uniqid("INIT_AUTH_");

            $transactionId = uniqid("AUTH_");

            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->put($this->apiUrl . "/merchant/" . $this->merchantId . "/order/" . $orderId . "/transaction/" . $transactionId, [
                    "apiOperation" => "INITIATE_AUTHENTICATION",
                    "correlationId" => $correlationId,
                    "order" => [
                        "currency" => "PKR",
                        "reference" => $orderId
                    ],
                    "session" => [
                        "id" => $sessionId
                    ],
                    "transaction" => [
                        "reference" => "AUTH-" . uniqid()
                    ],
                    "authentication" => [
                        "acceptVersions" => "3DS2",
                        "channel" => "PAYER_BROWSER",
                        "purpose" => "PAYMENT_TRANSACTION"
                    ]
                ]);

            $data = $response->json();
            Log::info("3DS Initiate Authentication:", $data);
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("3DS Initiate Authentication Error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Step 4: Authenticate Payer
     */
    public function authenticatePayer(Request $request)
    {
        try {
            $orderId          = $request->order_id;
            $sessionId        = $request->session_id;
            $authenticationId = $request->authentication_id;

            $url = $this->apiUrl . "/merchant/" . $this->merchantId . "/order/" . $orderId . "/transaction/" . $authenticationId;

            $payload = [
                "apiOperation" => "AUTHENTICATE_PAYER",
                "session" => [
                    "id" => $sessionId
                ],
                "authentication" => [
                "redirectResponseUrl" => route('payment.callback')
                ],
                "device" => [
                    "browserDetails" => [
                        "acceptHeaders" => $request->header('Accept') ?? "application/json",
                        "colorDepth"    => 24,
                        "javaEnabled"   => false,
                        "language"      => $request->getPreferredLanguage() ?? "en-US",
                        "screenHeight"  => 1080,
                        "screenWidth"   => 1920,
                        "timeZone"      => -300,
                        "3DSecureChallengeWindowSize" => "FULL_SCREEN"
                    ]
                ]
            ];

            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->put($url, $payload);

            $data = $response->json();
            Log::info("3DS Authenticate Payer Response:", $data);
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Authenticate Payer Error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check Authentication Status
     */
    public function checkAuthStatus(Request $request)
    {
        try {
            $orderId = $request->order_id;

            $url = $this->apiUrl . "/merchant/" . $this->merchantId . "/order/" . $orderId;

            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->get($url);

            $data = $response->json();
            Log::info('Auth Status Check:', $data);

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Auth Status Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Step 5: Pay after authentication - CORRECTED
     */
    public function processPayment(Request $request)
    {
        $sessionId       = $request->session_id;
        $orderId         = $request->order_id;
        $amount          = number_format((float) $request->amount, 2, '.', '');
        $authTransactionId = $request->auth_transaction_id; // ✅ AUTH TRANSACTION ID

        $payload = [
            "apiOperation" => "PAY",
            "authentication" => [
                "transactionId" => $authTransactionId // ✅ AUTH REFERENCE ADD KAREIN
            ],
            "order" => [
                "amount"   => $amount,
                "currency" => "PKR",
                "description" => "Test Transaction"
            ],
            "session" => [
                "id" => $sessionId
            ],
            "sourceOfFunds" => [
                "type" => "CARD"
            ]
        ];

        $transactionId = uniqid("TXN_");
        $url = $this->apiUrl . "/merchant/{$this->merchantId}/order/{$orderId}/transaction/{$transactionId}";

        $response = Http::withOptions(['verify' => false])
            ->withBasicAuth("merchant.{$this->merchantId}", $this->apiPassword)
            ->put($url, $payload);

        $data = $response->json();
        Log::info('Payment Response:', $data);

        return response()->json([
            "http_code" => $response->status(),
            "response"  => $data
        ]);
    }

    /**
     * Callback
     */
    public function paymentCallback(Request $request)
    {
        Log::info('PAY STEP AMOUNT:', ['amount' => $request->amount]);
        Log::info('Payment Callback Data:', $request->all());

        // Optional: You can handle success/failure check here if callback provides status
        // Example: $status = $request->input('result') ?? 'UNKNOWN';

        // Redirect to thank you page
        return redirect()->route('web.thankyou')->with([
            'payment_status' => 'success',
            'callback_data'  => $request->all(),
        ]);
    }
}
