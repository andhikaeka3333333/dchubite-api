<!DOCTYPE html>
<html>
<head>
    <style>
        .email-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #2D89EF;
            padding: 10px;
            background: #E3F2FD;
            display: inline-block;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Your OTP Code</h2>
        <p>Use the following OTP to verify your login:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>This OTP is valid for 5 minutes.</p>
        <p class="footer">If you did not request this, please ignore this email.</p>
    </div>
</body>
</html>
