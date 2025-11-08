<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - CCET Student Vault</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
        }
        .otp-box {
            background: #F3F4F6;
            border: 2px dashed #4F46E5;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #4F46E5;
            margin: 20px 0;
        }
        .info {
            background: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            color: #6B7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔐 CCET Student Vault</div>
            <h1 style="color: #1F2937; margin: 0;">
                @if($type === 'email_verification')
                    Email Verification
                @else
                    Password Reset
                @endif
            </h1>
        </div>

        <p>Hello <strong>{{ $user->name }}</strong>,</p>

        <p>
            @if($type === 'email_verification')
                Thank you for registering with CCET Student Vault. To complete your registration, please use the following One-Time Password (OTP):
            @else
                We received a request to reset your password. Use the following One-Time Password (OTP) to reset your password:
            @endif
        </p>

        <div class="otp-box">
            <p style="margin: 0; color: #6B7280; font-size: 14px;">Your OTP Code</p>
            <div class="otp-code">{{ $otp }}</div>
            <p style="margin: 0; color: #6B7280; font-size: 14px;">
                Valid for {{ $expiryMinutes }} minutes
            </p>
        </div>

        <div class="info">
            <strong>⚠️ Security Notice:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>This OTP will expire in {{ $expiryMinutes }} minutes</li>
                <li>Do not share this code with anyone</li>
                <li>CCET Student Vault will never ask for your OTP via phone or email</li>
            </ul>
        </div>

        <p>
            If you didn't request this 
            @if($type === 'email_verification')
                registration
            @else
                password reset
            @endif
            , please ignore this email or contact support if you have concerns.
        </p>

        <div class="footer">
            <p>© {{ date('Y') }} CCET Student Vault. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>