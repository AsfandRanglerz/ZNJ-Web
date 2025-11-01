<?php

namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FeatureAdsPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FeatureAdsPaymentController extends Controller
{
    private $merchantId = "ZNJEVENTSCON";
    private $apiPassword = "62ff0507b23317d047f1274867b42a07";
    private $apiUrl = "https://bankalfalah.gateway.mastercard.com/api/rest/version/74";

    /**
     * Create Hosted Checkout Session
     */
    public function createHostedCheckout(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
                'user_id' => 'required|integer',
                'type' => 'required|in:event,venue,entertainer', // identify ad type
                'entity_id' => 'required|integer',               // event_id, venue_id, entertainer_detail_id
                'package_id' => 'required|integer'
            ]);

            $orderId = uniqid("ADS_");
            $amount = number_format((float) $request->amount, 2, '.', '');
            $userId = $request->user_id;

            Log::info("🎯 Creating Feature Ad Checkout", $request->all());

            // Step 1: Create checkout session on Bank Alfalah Gateway
            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->post($this->apiUrl . "/merchant/" . $this->merchantId . "/session", [
                    "apiOperation" => "INITIATE_CHECKOUT",
                    "interaction" => [
                        "operation" => "PURCHASE",
                        "returnUrl" => route('feature.payment.success'),
                        "cancelUrl" => route('feature.payment.cancel'),
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
                        "description" => "Feature Ad Payment"
                    ]
                ]);

            $sessionData = $response->json();

            if (!isset($sessionData['session']['id'])) {
                Log::error("❌ INITIATE_CHECKOUT failed", $sessionData);
                return response()->json([
                    'success' => false,
                    'error' => 'Checkout initiation failed.'
                ], 400);
            }

            $sessionId = $sessionData['session']['id'];

            // Step 2: Store record
            $payment = new FeatureAdsPayment();
            $payment->order_id = $orderId;
            $payment->session_id = $sessionId;
            $payment->amount = $amount;
            $payment->status = 'pending';

            // Assign based on type
            switch ($request->type) {
                case 'event':
                    $payment->event_id = $request->entity_id;
                    $payment->event_feature_ads_package_id = $request->package_id;
                    break;
                case 'venue':
                    $payment->venue_id = $request->entity_id;
                    $payment->venue_feature_ads_package_id = $request->package_id;
                    break;
                case 'entertainer':
                    $payment->entertainer_detail_id = $request->entity_id;
                    $payment->entertainer_feature_ads_package_id = $request->package_id;
                    break;
            }

            $payment->save();

            // Step 3: Generate checkout URL
            $checkoutPageUrl = url('/api/feature/pay/' . $orderId . '?session_id=' . $sessionId);

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
            Log::error("❌ Feature Ads Checkout Error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check Payment Status
     */
    public function checkPaymentStatus(Request $request)
    {
        try {
            $orderId = $request->order_id;
            if (!$orderId) {
                return response()->json(['success' => false, 'error' => 'Order ID is required'], 400);
            }

            $url = $this->apiUrl . "/merchant/" . $this->merchantId . "/order/" . $orderId;

            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth("merchant." . $this->merchantId, $this->apiPassword)
                ->get($url);

            $data = $response->json();

            if (isset($data['result']) && $data['result'] === 'SUCCESS') {
                FeatureAdsPayment::where('order_id', $orderId)->update(['status' => 'success']);
            }

            return response()->json([
                'success' => true,
                'status' => $data['status'] ?? 'UNKNOWN',
                'result' => $data['result'] ?? 'UNKNOWN',
                'gateway_response' => $data
            ]);
        } catch (\Exception $e) {
            Log::error("❌ checkPaymentStatus error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function paymentCallback(Request $request)
    {
        Log::info('🔴 FEATURE ADS PAYMENT CALLBACK', $request->all());
        $orderId = $request->get('order_id');
        $result = $request->get('result');

        // 🔍 Find payment record
        $payment = FeatureAdsPayment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'error' => 'Payment record not found.'
            ]);
        }

        if (strtoupper($result) === 'SUCCESS') {
            // Update payment status
            $payment->update(['status' => 'success']);

            // Update related table feature_status = 1
            if ($payment->event_id) {
                DB::table('events')
                    ->where('id', $payment->event_id)
                    ->update(['feature_status' => 1]);
            }

            if ($payment->venue_id) {
                DB::table('venues')
                    ->where('id', $payment->venue_id)
                    ->update(['feature_status' => 1]);
            }

            if ($payment->entertainer_detail_id) {
                DB::table('entertainer_details')
                    ->where('id', $payment->entertainer_detail_id)
                    ->update(['feature_status' => 1]);
            }

            Log::info("✅ Feature status updated for order: {$orderId}");

            return response()->json([
                'success' => true,
                'message' => 'Feature Ad Payment completed successfully and feature status updated.'
            ]);
        } else {
            // Payment failed or canceled
            $payment->update(['status' => 'failed']);

            return response()->json([
                'success' => false,
                'error' => 'Payment failed or canceled.'
            ]);
        }
    }



    public function testApi()
    {
        return response()->json([
            'success' => true,
            'message' => 'Feature Ads Payment API Working ✅',
            'timestamp' => now(),
        ]);
    }
}
