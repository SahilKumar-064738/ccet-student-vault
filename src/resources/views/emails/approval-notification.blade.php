<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload {{ ucfirst($action) }} - CCET Student Vault</title>
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
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0;
        }
        .status-approved {
            background: #D1FAE5;
            color: #065F46;
        }
        .status-rejected {
            background: #FEE2E2;
            color: #991B1B;
        }
        .file-details {
            background: #F9FAFB;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .detail-row:last-child {
            border-bottom: none;
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
        .comment-box {
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
            <div class="logo">📚 CCET Student Vault</div>
            <h1 style="color: #1F2937; margin: 0;">
                Upload {{ ucfirst($action) }}
            </h1>
        </div>

        <p>Hello <strong>{{ $upload->user->name }}</strong>,</p>

        <p>
            @if($action === 'approved')
                Great news! Your uploaded resource has been approved and is now available to other students.
            @else
                Your uploaded resource has been reviewed and was not approved at this time.
            @endif
        </p>

        <div style="text-align: center;">
            <span class="status-badge status-{{ $action }}">
                @if($action === 'approved')
                    ✓ Approved
                @else
                    ✗ Rejected
                @endif
            </span>
        </div>

        <div class="file-details">
            <h3 style="margin-top: 0; color: #1F2937;">File Details</h3>
            <div class="detail-row">
                <span style="color: #6B7280;">File Name:</span>
                <strong>{{ $upload->file_name }}</strong>
            </div>
            <div class="detail-row">
                <span style="color: #6B7280;">Subject:</span>
                <strong>{{ $upload->subject->name }}</strong>
            </div>
            <div class="detail-row">
                <span style="color: #6B7280;">Type:</span>
                <strong>{{ ucfirst(str_replace('_', ' ', $upload->upload_type)) }}</strong>
            </div>
            <div class="detail-row">
                <span style="color: #6B7280;">Uploaded:</span>
                <strong>{{ $upload->created_at->format('M d, Y') }}</strong>
            </div>
        </div>

        @if($upload->admin_comment)
            <div class="comment-box">
                <strong>{{ $action === 'approved' ? 'Reviewer Comment:' : 'Rejection Reason:' }}</strong>
                <p style="margin: 10px 0 0 0;">{{ $upload->admin_comment }}</p>
            </div>
        @endif

        @if($action === 'approved')
            <p style="text-align: center;">
                <a href="{{ url('/uploads') }}" class="btn">View in Browse</a>
            </p>
            <p>Your resource is now helping fellow students succeed in their studies. Thank you for contributing to the community!</p>
        @else
            <p>If you believe this was a mistake or would like to resubmit with corrections, please feel free to upload again.</p>
            <p style="text-align: center;">
                <a href="{{ url('/uploads/create') }}" class="btn">Upload Again</a>
            </p>
        @endif

        <div class="footer">
            <p>© {{ date('Y') }} CCET Student Vault. All rights reserved.</p>
            <p>If you have questions, please contact your administrator.</p>
        </div>
    </div>
</body>
</html>