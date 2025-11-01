<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Event Payment</title>

  <!-- ✅ Correct JS path for version ≥ 74 -->
  <script src="https://bankalfalah.gateway.mastercard.com/static/checkout/checkout.min.js"
          data-error="errorCallback"
          data-cancel="cancelCallback"></script>

  <script>
    const sessionId = "{{ $sessionId }}";

    Checkout.configure({
      session: { id: sessionId }
    });

    function pay() {
      Checkout.showPaymentPage();
    }

    function errorCallback(error) {
      console.error("❌ Payment error:", error);
      alert("Payment failed:\n" + JSON.stringify(error, null, 2));
    }

    function cancelCallback() {
      alert("Payment cancelled!");
    }
  </script>

  <style>
    body {
      background: #f9f9f9;
      text-align: center;
      font-family: Arial, sans-serif;
      padding-top: 60px;
    }
    h2 { color: #333; }
    button {
      background: #007bff;
      color: #fff;
      border: none;
      padding: 12px 28px;
      border-radius: 8px;
      font-size: 18px;
      cursor: pointer;
    }
    button:hover { background: #0056b3; }
  </style>
</head>
<body>
  <h2>Pay for Your Event</h2>
  <p>Amount: <strong>{{ $amount }} PKR</strong></p>
  <button onclick="pay()">Pay Now</button>
</body>
</html>
