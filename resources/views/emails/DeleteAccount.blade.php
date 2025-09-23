@component('mail::message')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deletion Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }
        .email-header {
            text-align: center;
            background-color: #fff;
        }
        .email-header img {
            max-height: 100px;
            margin-bottom: 8px;
        }
        .email-body {
            color: #333333;
        }
        .email-body h1 {
            font-size: 24px;
            margin-bottom: 18px;
            color: #2d3748;
        }
        .email-body p {
            line-height: 1.7;
            margin-bottom: 18px;
        }
        .email-footer {
            text-align: center;
            padding: 18px 10px;
            font-size: 13px;
            color: #999999;
            background-color: #f9f9f9;
        }
        @media (max-width: 600px) {
            .email-wrapper {
                width: 100% !important;
                margin: 0;
                border-radius: 0;
            }
            .email-body {
                padding: 20px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="{{ asset('public/admin/assets/img/logo.png')}}" alt="{{ config('app.name') }} Logo">
        </div>
        <div class="email-body">
            <h1>Account Deletion Request Received</h1>
            <p>Dear {{$data->name}},</p>
            <p>Your account deletion request has been received successfully. Our team will process your request as soon as possible.</p>
            <p>If you did not make this request or have any questions, please contact our support team immediately.</p>
            <p>Thank you,<br>{{ config('app.name') }}</p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
@endcomponent
