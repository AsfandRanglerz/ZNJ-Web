<!DOCTYPE html>
<html>
<head>
    <title>Welcome to ZNJ</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
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
    <div style="text-align:center; margin-bottom: 20px;">
        <img src="{{ asset('public/admin/assets/img/logo.png') }}" 
             alt="{{ config('app.name') }} Logo" 
             style="height: 100px; margin-bottom: 20px;">
        <h3><strong>Welcome to <span style="color: #021642;">ZNJ</span></strong></h3>
    </div>

    <p>Dear {{ $name ?? 'User' }},</p>

    <p>Your account has been successfully created.</p>

    <p>With your account, you’ll be able to:</p>
    <ul>
        <li>Create Events</li>
        <li>Join Events</li>
        <li>Generate Tickets</li>
    </ul>

    <h3>Your Account Details:</h3>
    <ul>
        <li><strong>Email:</strong> {{ $email ?? 'N/A' }}</li>
        <li><strong>Phone:</strong> {{ $phone ?? 'N/A' }}</li>
    </ul>

    <p>Please keep this information safe and secure. Do not share your login credentials with anyone.</p>

    <p>If you have any questions or need help, you can simply reply to this email.</p>

    <p>Thanks,<br><strong>ZNJ</strong></p>
</body>
</html>