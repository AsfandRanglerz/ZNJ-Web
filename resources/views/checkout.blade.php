@extends('web.layout.app')

@section('title', 'Payment Checkout')

@section('content')
    <div class="container d-flex mt-5 w-100 mb-5 form-container-join-event">
        <div class="login-form row g-3 w-100">
            <h2 class="text-white text-center mb-4">Payment Checkout</h2>

            <!-- Order Details -->
            {{-- <div class="col-12 mb-3 mt-2">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <p class="mb-1"><strong>Amount:</strong> {{ $amount }} {{ $currency }}</p>
                        <p class="mb-0"><strong>Event:</strong> {{ $event->title }}</p>
                    </div>
                </div>
            </div> --}}

            <!-- Credit Card Details -->
            <div class="col-12 mb-3 mt-2">
                <h5 class="text-white mb-3">Credit Card Details</h5>
            </div>

            <!-- Card Number -->
            <div class="col-12 mb-3 mt-2">
                <label class="form-label lebel-of-join-event-input">Card Number</label>
                <input type="text" id="card-number" class="form-control input-uniform" placeholder="Card Number" readonly>
            </div>

            <!-- Expiry and CVV -->
            <div class="col-md-4 col-12 mb-3 mt-2">
                <label class="form-label lebel-of-join-event-input">Expiry Month</label>
                <input type="text" id="expiry-month" class="form-control input-uniform" placeholder="MM" readonly>
            </div>

            <div class="col-md-4 col-12 mb-3 mt-2">
                <label class="form-label lebel-of-join-event-input">Expiry Year</label>
                <input type="text" id="expiry-year" class="form-control input-uniform" placeholder="YY" readonly>
            </div>

            <div class="col-md-4 col-12 mb-3 mt-2">
                <label class="form-label lebel-of-join-event-input">CVV</label>
                <input type="text" id="security-code" class="form-control input-uniform" placeholder="CVV" readonly>
            </div>

            <!-- Cardholder Name -->
            <div class="col-12 mb-3 mt-2">
                <label class="form-label lebel-of-join-event-input">Cardholder Name</label>
                <input type="text" id="cardholder-name" class="form-control input-uniform" placeholder="Cardholder Name"
                    readonly>
            </div>

            <!-- Pay Button -->
            <div class="col-12 mb-2 text-center d-flex justify-content-center align-items-center">
                <button id="payButton" class="btn mt-4 submit-btn-for-genrate-ticket" onclick="processPayment();">
                    Pay Now - {{ $amount }} {{ $currency }}
                </button>
            </div>

            <!-- Loading -->
            <div id="loading" class="col-12 text-center mt-3" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Processing payment...</span>
                </div>
                <p class="mt-2 text-white">Processing payment...</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://bankalfalah.gateway.mastercard.com/form/version/74/merchant/ZNJEVENTSCON/session.js"></script>
    <script>
        // CSRF token ko globally define karen
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        console.log("CSRF Token:", CSRF_TOKEN);
        console.log("Session ID:", "{{ $session_id }}");

        // Frame-breaker
        if (self === top) {
            const antiClickjack = document.getElementById("antiClickjack");
            if (antiClickjack) antiClickjack.remove();
        } else {
            top.location = self.location;
        }

        let isProcessing = false;
        let authTransactionId = null;
        let authPopup = null; // ✅ POPUP CONTROL VARIABLE

        // ✅ COMPLETE 5-STEP FLOW
        PaymentSession.configure({
            session: "{{ $session_id }}",
            fields: {
                card: {
                    number: "#card-number",
                    securityCode: "#security-code",
                    expiryMonth: "#expiry-month",
                    expiryYear: "#expiry-year",
                    nameOnCard: "#cardholder-name"
                }
            },
            frameEmbeddingMitigation: ["javascript"],
            callbacks: {
                initialized: function() {
                    console.log("✅ Step 1: Hosted Fields initialized");
                    document.querySelectorAll('.input-uniform').forEach(f => f.removeAttribute('readonly'));
                },
                formSessionUpdate: function(response) {
                    console.log("Step 2 Response:", response);
                    if (response.status === "ok") {
                        console.log("✅ Step 2: Session updated successfully");
                        initiateAuthentication("{{ $session_id }}", "{{ $order_id }}", "{{ $amount }}");
                    } else {
                        console.error("❌ Step 2 Failed:", response.errors);
                        resetPaymentButton();
                    }
                }
            }
        });

        function processPayment() {
            if (isProcessing) return;
            isProcessing = true;
            document.getElementById('payButton').disabled = true;
            document.getElementById('loading').style.display = 'block';
            console.log("🔄 Starting payment...");
            PaymentSession.updateSessionFromForm('card');
        }

        function resetPaymentButton() {
            isProcessing = false;
            document.getElementById('payButton').disabled = false;
            document.getElementById('loading').style.display = 'none';
        }

        // STEP 3: Initiate Authentication
        function initiateAuthentication(sessionId, orderId, amount) {
            console.log("🎯 Step 3 started");
            fetch("/ZNJ-Web/initiate-auth", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        order_id: orderId,
                        amount: amount
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Step 3 Response:", data);
                    if (data.result === "SUCCESS") {
                        console.log("✅ Step 3 Complete");
                        const authId = data.transaction?.id || data.authentication?.id || data.order?.id;
                        if (authId) {
                            authenticatePayer(authId, sessionId, orderId, data);
                        } else {
                            // Direct payment if no authentication needed
                            processPaymentFinal(sessionId, orderId, "{{ $amount }}");
                        }
                    } else {
                        console.error("❌ Step 3 Failed");
                        resetPaymentButton();
                    }
                })
                .catch(err => {
                    console.error("❌ Step 3 Error:", err);
                    resetPaymentButton();
                });
        }

        // STEP 4: Authenticate Payer - WITH POPUP CONTROL
        function authenticatePayer(authId, sessionId, orderId, step3Data) {
            console.log("🎯 Step 4 started");
            fetch("/ZNJ-Web/authenticate-payer", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        authentication_id: authId,
                        session_id: sessionId,
                        order_id: orderId,
                        step3_data: step3Data
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Step 4 Response:", data);

                    authTransactionId = data.transaction?.id || data.authentication?.transaction?.id || authId;
                    console.log("🔍 Auth Transaction ID:", authTransactionId);

                    if (data.result === "SUCCESS") {
                        console.log("✅ Step 4 Complete - Direct Success");
                        processPaymentFinal(sessionId, orderId, "{{ $amount }}");
                    } else if (data.result === "PENDING" || data.order?.authenticationStatus === "AUTHENTICATION_PENDING") {
                        console.log("⏳ Step 4: OTP Required");
                        showOTPPopup(data, sessionId, orderId);
                    } else {
                        console.error("❌ Step 4 Failed");
                        resetPaymentButton();
                    }
                })
                .catch(err => {
                    console.error("❌ Step 4 Error:", err);
                    resetPaymentButton();
                });
        }

        // OTP Popup - WITH POPUP CONTROL
        function showOTPPopup(authData, sessionId, orderId) {
            const redirectUrl = authData.authentication?.redirect?.url;
            const redirectHtml = authData.authentication?.redirect?.html;

            // ✅ POPUP KO STORE KAREN TAKE BAAD MEIN CLOSE KAR SAKEN
            authPopup = window.open("", "3DS Challenge", "width=420,height=700,scrollbars=yes,resizable=yes");

            if (!authPopup || authPopup.closed) {
                alert("❌ Popup blocked! Enable popups.");
                resetPaymentButton();
                return;
            }

            // ✅ SIMPLE - Koi script modification nahi
            if (redirectHtml) {
                authPopup.document.open();
                authPopup.document.write(redirectHtml);
                authPopup.document.close();
            } else if (redirectUrl) {
                authPopup.location.href = redirectUrl;
            }

            // ✅ AUTH STATUS CHECK USE KAREN
            checkAuthStatusRepeatedly(sessionId, orderId);
        }

        // ✅ IMPROVED AUTH STATUS CHECK WITH POPUP CONTROL
        function checkAuthStatusRepeatedly(sessionId, orderId, attempt = 0) {
            if (attempt >= 20) {
                console.error("❌ Max auth check attempts reached");
                // ✅ POPUP CLOSE KAREN FAILURE PAR
                if (authPopup && !authPopup.closed) {
                    authPopup.close();
                }
                alert("❌ Authentication timeout. Please try again.");
                resetPaymentButton();
                return;
            }

            console.log(`🔄 Checking auth status... Attempt: ${attempt + 1}`);

            fetch("/ZNJ-Web/check-auth-status", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        order_id: orderId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Auth Status Response:", data);
                    const authStatus = data.authenticationStatus || data.order?.authenticationStatus || data.result;

                    if (authStatus === "AUTHENTICATION_SUCCESSFUL" || authStatus === "SUCCESS") {
                        console.log("✅ Authentication Successful!");
                        
                        // ✅ PEHLE POPUP CLOSE KAREN
                        if (authPopup && !authPopup.closed) {
                            setTimeout(() => {
                                authPopup.close();
                            }, 500);
                        }
                        
                        // ✅ PHIR PAYMENT PROCESS KAREN
                        setTimeout(() => {
                            processPaymentFinal(sessionId, orderId, "{{ $amount }}");
                        }, 1000);
                    } else if (authStatus === "AUTHENTICATION_PENDING" || authStatus === "PENDING") {
                        console.log("⏳ Still pending...");
                        setTimeout(() => checkAuthStatusRepeatedly(sessionId, orderId, attempt + 1), 2000);
                    } else {
                        console.error("❌ Authentication Failed:", authStatus);
                        
                        // ✅ FAILURE PAR BHI POPUP CLOSE KAREN
                        if (authPopup && !authPopup.closed) {
                            authPopup.close();
                        }
                        
                        alert("❌ 3D Secure verification failed.");
                        resetPaymentButton();
                    }
                })
                .catch(err => {
                    console.error("❌ Auth check error:", err);
                    setTimeout(() => checkAuthStatusRepeatedly(sessionId, orderId, attempt + 1), 2000);
                });
        }

        // STEP 5: Final Payment - WITH SINGLE REDIRECT
        function processPaymentFinal(sessionId, orderId, amount) {
            console.log("🎯 Step 5 started - Final Payment");
            console.log("🔍 Using Auth Transaction ID:", authTransactionId);

            fetch("/ZNJ-Web/process-payment", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        order_id: orderId,
                        amount: amount,
                        auth_transaction_id: authTransactionId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Step 5 Final Response:", data);

                    const result = data.result || data.response?.result;
                    const gatewayCode = data.response?.response?.gatewayCode;

                    // ✅ SIMPLE SUCCESS CHECK
                    if (result === "SUCCESS") {
                        console.log("✅ Payment Successful!");
                        
                        // ✅ SIRF MAIN WINDOW KO REDIRECT KAREN
                        // ✅ Popup already close ho chuka hoga
                        window.location.href = `/ZNJ-Web/payment/callback?result=SUCCESS&session_id=${sessionId}&order_id=${orderId}&amount=${amount}&transaction_id=${authTransactionId}`;
                    } else {
                        console.error("❌ Payment Failed:", data);
                        const errorMsg = data.response?.response?.acquirerMessage ||
                            data.error?.message ||
                            "Payment failed. Please try again.";
                        alert("❌ " + errorMsg);
                        resetPaymentButton();
                    }
                })
                .catch(err => {
                    console.error("❌ Step 5 Error:", err);
                    alert("❌ Network error. Please check your connection and try again.");
                    resetPaymentButton();
                });
        }

        // ✅ EXTRA SAFETY: PAGE UNLOAD PAR POPUP CLOSE
        window.addEventListener('beforeunload', function() {
            if (authPopup && !authPopup.closed) {
                authPopup.close();
            }
        });

        // Toastr Messages (Optional)
        // $(document).ready(function() {
        //     @if (session('success'))
        //         toastr.success("{{ session('success') }}");
        //     @endif

        //     @if (session('error'))
        //         toastr.error("{{ session('error') }}");
        //     @endif

        //     @if ($errors->any())
        //         @foreach ($errors->all() as $error)
        //             toastr.error("{{ $error }}");
        //         @endforeach
        //     @endif
        // });
    </script>
@endsection
