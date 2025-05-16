<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 500px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #4f46e5;
            padding: 20px;
            text-align: center;
        }
        .email-header img {
            width: 80px;
            margin-bottom: 10px;
        }
        .email-header h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0;
        }
        .email-body {
            padding: 30px;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 4px;
            margin: 20px 0;
        }
        .email-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/lock--v1.png" alt="Secure Lock">
            <h1>Password Reset OTP</h1>
        </div>
        <div class="email-body">
            <p>Hello,</p>
            <p>Use the following OTP to reset your password:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>This code will expire in 10 minutes.</p>
            <p>If you didn't request a password reset, please ignore this email.</p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} D'chubite POS. All rights reserved.
        </div>
    </div>
</body>
</html>
