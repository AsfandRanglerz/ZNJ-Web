<!DOCTYPE html>
<html>
<head>
    <title>Payment Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            line-height: 1.6;
        }
        .success {
            color: green;
            border: 2px solid green;
            padding: 20px;
            border-radius: 8px;
            background: #f0fff0;
        }
        .error {
            color: red;
            border: 2px solid red;
            padding: 20px;
            border-radius: 8px;
            background: #fff0f0;
        }
        .cancelled {
            color: orange;
            border: 2px solid orange;
            padding: 20px;
            border-radius: 8px;
            background: #fffaf0;
        }
        .info {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <h1>Payment Result</h1>
    
    @if($status === 'SUCCESS' || $status === 'success')
        <div class="success">
            <h2>✅ Payment Successful!</h2>
            <p><strong>Order ID:</strong> {{ $order_id ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $status }}</p>
            <p>Thank you for your payment. Your transaction was completed successfully.</p>
        </div>
    @elseif($status === 'CANCELLED')
        <div class="cancelled">
            <h2>⏹️ Payment Cancelled</h2>
            <p>{{ $message ?? 'Payment was cancelled by the user.' }}</p>
        </div>
    @else
        <div class="error">
            <h2>❌ Payment Failed</h2>
            <p><strong>Order ID:</strong> {{ $order_id ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $status ?? 'UNKNOWN' }}</p>
            <p>There was an issue processing your payment. Please try again.</p>
        </div>
    @endif

    @if(isset($data) && is_array($data))
        <div class="info">
            <h3>Detailed Response:</h3>
            <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif

    <div>
        <button onclick="window.location.href='/checkout'">Make Another Payment</button>
        <button onclick="window.location.href='/'">Go to Home</button>
    </div>

    <script>
        // Log the payment result for debugging
        console.log('Payment Result:', {
            status: '{{ $status }}',
            order_id: '{{ $order_id }}',
            data: @json($data ?? [])
        });
    </script>
</body>
</html>