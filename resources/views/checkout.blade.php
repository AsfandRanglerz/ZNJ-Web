<!DOCTYPE html>
<html>
<head>
    <title>Payment Checkout</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ✅ LIVE SESSION.JS URL -->
    <script src="https://bankalfalah.gateway.mastercard.com/form/version/100/merchant/ZNJEVENTSCON/session.js"></script>
    <style id="antiClickjack">
        body {
            display: none !important;
        }
    </style>
    <style>
        .input-field {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 250px;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .loading {
            display: none;
            color: #007bff;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Payment Checkout</h2>
    <div>Amount: {{ $amount }} {{ $currency }}</div>
    <div>Order ID: {{ $order_id }}</div>
    <hr>
    <h3>Credit Card Details</h3>
    <div><input type="text" id="card-number" class="input-field" placeholder="Card Number" readonly></div>
    <div><input type="text" id="expiry-month" class="input-field" placeholder="MM" readonly></div>
    <div><input type="text" id="expiry-year" class="input-field" placeholder="YY" readonly></div>
    <div><input type="text" id="security-code" class="input-field" placeholder="CVV" readonly></div>
    <div><input type="text" id="cardholder-name" class="input-field" placeholder="Cardholder Name" readonly></div>

    <button id="payButton" onclick="processPayment();">Pay Now</button>
    <div id="loading" class="loading">Processing payment...</div>

    <script>
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

        // ✅ COMPLETE 5-STEP FLOW (Wahi jo pehle successful tha)
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
                    document.querySelectorAll('.input-field').forEach(f => f.removeAttribute('readonly'));
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
            fetch("/ZNJ-Web/initiate-auth", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
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
                            showOTPPopup(data, sessionId, orderId);
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

        // STEP 4: Authenticate Payer
        function authenticatePayer(authId, sessionId, orderId, step3Data) {
            console.log("🎯 Step 4 started");
            fetch("/ZNJ-Web/authenticate-payer", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        authentication_id: authId,
                        session_id: sessionId,
                        order_id: orderId,
                        step3_data: step3Data,
                        redirectURL: window.location.origin + "/payment/callback"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Step 4 Response:", data);

                    // ✅ AUTH TRANSACTION ID CAPTURE KAREIN
                    authTransactionId = data.transaction?.id || data.authentication?.transaction?.id || authId;
                    console.log("🔍 Auth Transaction ID:", authTransactionId);

                    if (data.result === "SUCCESS") {
                        console.log("✅ Step 4 Complete");
                        processPaymentFinal(sessionId, orderId, "{{ $amount }}");
                    } else if (data.result === "PENDING" || data.order?.authenticationStatus === "AUTHENTICATION_PENDING") {
                        console.log("⏳ Step 4: Waiting for OTP...");
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

        // OTP Popup
        function showOTPPopup(authData, sessionId, orderId) {
            const redirectUrl = authData.authentication?.redirect?.url;
            const redirectHtml = authData.authentication?.redirect?.html;
            const popup = window.open("", "3DS Challenge", "width=420,height=700,scrollbars=yes,resizable=yes");

            if (!popup || popup.closed) {
                alert("❌ Popup blocked! Enable popups.");
                resetPaymentButton();
                return;
            }

            if (redirectHtml) {
                popup.document.open();
                popup.document.write(redirectHtml);
                popup.document.close();
                monitorOTPPopup(popup, sessionId, orderId);
            } else if (redirectUrl) {
                popup.location.href = redirectUrl;
                monitorOTPPopup(popup, sessionId, orderId);
            }
        }

        // OTP Monitor
        function monitorOTPPopup(popup, sessionId, orderId) {
            const checkInterval = setInterval(() => {
                if (popup.closed) {
                    clearInterval(checkInterval);
                    console.log("✅ OTP popup closed, checking authentication status...");
                    checkAuthStatus(sessionId, orderId);
                }
            }, 1000);
        }

        // Enhanced Auth Status Check
        function checkAuthStatus(sessionId, orderId) {
            console.log("🔄 Checking authentication status...");

            fetch("/ZNJ-Web/check-auth-status", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        order_id: orderId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("🔍 Auth Status Response:", data);

                    const authStatus = data.authenticationStatus || data.order?.authenticationStatus;
                    console.log("🔍 Authentication Status:", authStatus);

                    if (authStatus === "AUTHENTICATION_SUCCESSFUL") {
                        console.log("✅ Authentication Successful! Processing payment...");
                        processPaymentFinal(sessionId, orderId, "{{ $amount }}");
                    } else if (authStatus === "AUTHENTICATION_PENDING") {
                        console.log("⏳ Authentication still pending, checking again...");
                        setTimeout(() => checkAuthStatus(sessionId, orderId), 3000);
                    } else if (authStatus === "AUTHENTICATION_NOT_IN_EFFECT" || authStatus === "AUTHENTICATION_FAILED") {
                        console.error("❌ Authentication Failed:", authStatus);
                        alert("❌ 3D Secure verification failed. Please try again.");
                        resetPaymentButton();
                    } else {
                        console.log("⚠️ Unknown auth status, proceeding with payment...");
                        processPaymentFinal(sessionId, orderId, "{{ $amount }}");
                    }
                })
                .catch(err => {
                    console.error("❌ Auth status check failed:", err);
                    processPaymentFinal(sessionId, orderId, "{{ $amount }}");
                });
        }

        // STEP 5: Final Payment
        function processPaymentFinal(sessionId, orderId, amount) {
            console.log("🎯 Step 5 started");
            console.log("🔍 Using Auth Transaction ID:", authTransactionId);

            fetch("/ZNJ-Web/process-payment", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
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
                    console.log("Step 5 Response:", data);

                    const result = data.response?.result;
                    const gatewayCode = data.response?.response?.gatewayCode;
                    const authStatus = data.response?.order?.authenticationStatus;

                    // ✅ SUCCESS CONDITIONS
                    if (result === "SUCCESS" && gatewayCode === "APPROVED") {
                        console.log("✅ Payment Successful! (Approved)");
                        alert("✅ Payment Successful!");
                        window.location.href = "/ZNJ-Web/payment/callback?result=SUCCESS";
                    } else if (result === "SUCCESS" && authStatus === "AUTHENTICATION_NOT_IN_EFFECT") {
                        console.log("⚠️ Payment successful but 3DS skipped");
                        alert("✅ Payment Successful! (3D Secure was not required)");
                        window.location.href = "/ZNJ-Web/payment/callback?result=SUCCESS";
                    } else {
                        console.error("❌ Payment Failed:", data);
                        alert("❌ Payment Failed: " + (data.response?.response?.acquirerMessage || "Unknown error"));
                        resetPaymentButton();
                    }
                })
                .catch(err => {
                    console.error("❌ Step 5 Error:", err);
                    resetPaymentButton();
                });
        }
    </script>
</body>
</html>