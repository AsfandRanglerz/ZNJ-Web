<!DOCTYPE html>
<html>
<head>
    <title>Welcome to ZNJ</title>
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
            padding: 20px;
        }
        .email-header img {
            max-height: 100px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 25px;
            color: #333333;
        }
        .email-body h1 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #2d3748;
        }
        .email-body p {
            line-height: 1.6;
            margin-bottom: 15px;
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
        h3 {
            font-weight: 600;
        }
        ul {
            padding-left: 18px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="{{ asset('public/admin/assets/img/logo.png') }}" alt="{{ config('app.name') }} Logo">
        </div>
    <div style="text-align:center; margin-bottom: 20px;">
        <h3><strong>Welcome to <span style="color: #021642;">ZNJ</span></strong></h3>
    </div>

    <p>Dear {{ $name ?? 'User' }},</p>

    <p>Your account has been successfully created.</p>

    <h3>Your Account Details:</h3>
    <ul>
        <li><strong>Email:</strong> {{ $email ?? 'N/A' }}</li>
        <li><strong>Phone:</strong> {{ $phone ?? 'N/A' }}</li>
    </ul>

   
    <p>Please keep this information safe and secure. Do not share your login credentials with anyone.</p>

    <p>If you have any questions or need assistance, feel free to contact our support team at <a href="mailto:eventsznj@gmail.com">eventsznj@gmail.com</a> anytime.</p>
     <div class="email-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>

</body>
</html>
