@component('mail::message')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
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
            text-align: center;
        }
        .otp-box {
            display: block;
            width: fit-content;
            margin: 24px auto 18px auto;
            padding: 12px 32px;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 4px;
            background: #f6f8fa;
            border-radius: 6px;
            color: #2d3748;
            border: 1px solid #e2e8f0;
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
            <h1>Password Reset Request</h1>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            <div class="otp-box">{{ $data['otp'] }}</div>
            <p>This password reset OTP will expire in 60 minutes.</p>
            <p>If you did not request a password reset, no further action is required.</p>
            <p>Thank you,<br>{{ config('app.name') }}</p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
@endcomponent

