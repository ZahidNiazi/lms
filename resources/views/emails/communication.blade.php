<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?: 'Message from National Service LMS' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f7cff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f7cff;
            margin-bottom: 10px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .message-content {
            background-color: #f8f9fa;
            border-left: 4px solid #4f7cff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .application-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #bbdefb;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">National Service LMS</div>
            <p style="margin: 0; color: #6c757d;">Job Portal Communication</p>
        </div>

        <div class="greeting">
            Dear {{ $studentName ?? 'Student' }},
        </div>

        <div class="application-info">
            <strong>Application Number:</strong> {{ $applicationNumber ?? 'N/A' }}<br>
            <strong>Date:</strong> {{ now()->format('F d, Y') }}
        </div>

        <div class="message-content">
            {!! nl2br(e($messageContent ?? 'No message content')) !!}
        </div>

        <div class="contact-info">
            <strong>Important:</strong> Please keep this email for your records. If you have any questions or need assistance, please contact our support team.
        </div>

        <div class="footer">
            <p>This is an automated message from the National Service LMS Job Portal.</p>
            <p>Please do not reply to this email. For support, contact our help desk.</p>
            <p>&copy; {{ date('Y') }} National Service LMS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
