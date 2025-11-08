<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->title }} - CCET Student Vault</title>
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
        .notification-type {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 10px 0;
        }
        .type-announcement {
            background: #DBEAFE;
            color: #1E40AF;
        }
        .type-general {
            background: #E5E7EB;
            color: #374151;
        }
        .message-box {
            background: #F9FAFB;
            border-left: 4px solid #4F46E5;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #4F46E5;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
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
            <div class="logo">📢 CCET Student Vault</div>
            <h1 style="color: #1F2937; margin: 0;">{{ $notification->title }}</h1>
            <span class="notification-type type-{{ $notification->type }}">
                {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
            </span>
        </div>

        <div class="message-box">
            <p style="margin: 0; white-space: pre-line;">{{ $notification->body }}</p>
        </div>

        @if($notification->branch_id && $notification->year)
            <p style="color: #6B7280; font-size: 14px;">
                This notification is for: <strong>{{ \App\Models\Branch::find($notification->branch_id)->name }} - Year {{ $notification->year }}</strong>
            </p>
        @endif

        <p style="text-align: center;">
            <a href="{{ url('/notifications') }}" class="btn">View All Notifications</a>
        </p>

        <div class="footer">
            <p>Sent on {{ $notification->created_at->format('F d, Y \a\t h:i A') }}</p>
            <p>© {{ date('Y') }} CCET Student Vault. All rights reserved.</p>
        </div>
    </div>
</body>
</html>