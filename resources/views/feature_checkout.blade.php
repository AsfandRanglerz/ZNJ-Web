<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feature Ad Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://bankalfalah.gateway.mastercard.com/static/checkout/checkout.min.js"
            data-error="errorCallback"
            data-cancel="cancelCallback"></script>
</head>
<body style="font-family: Arial, sans-serif; text-align:center; padding:40px;">

<h2>Complete Your Feature Ad Payment</h2>
<p>Amount: <strong>PKR {{ $amount }}</strong></p>

<button id="payButton"
        style="padding:10px 25px; background:#007bff; color:white; border:none; border-radius:5px;">
    Pay Now
</button>

<script type="text/javascript">
    Checkout.configure({
        session: { id: "{{ $sessionId }}" }
    });

    document.getElementById("payButton").onclick = function () {
        Checkout.showPaymentPage();
    };
</script>

</body>
</html>
