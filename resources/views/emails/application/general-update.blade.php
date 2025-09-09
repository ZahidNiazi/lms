<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Update</title>
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
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4f7cff;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f7cff;
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b3d7ff;
        }
        .content {
            margin: 20px 0;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #4f7cff;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">National Service LMS</div>
            <h2>Application Update</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>We have an update regarding your National Service application. Please find the details below:</p>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Update Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Updated By:</strong> {{ $reviewerName }}</p>
            </div>

            <div class="status-badge status-processing">
                📋 Application Status Update
            </div>

            <p>Your application status has been updated. We are continuing to process your application and will notify you of any further developments.</p>

            @if($comments)
                <div class="info-box">
                    <h4>Update Details</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>What This Means</h4>
                <p>Your application is being actively reviewed by our team. We will continue to process your application and keep you informed of any updates or requirements.</p>
            </div>

            <div class="info-box">
                <h4>Next Steps</h4>
                <p>Please continue to:</p>
                <ul>
                    <li>Check your email regularly for updates</li>
                    <li>Ensure your contact information is current</li>
                    <li>Be prepared to provide additional information if requested</li>
                    <li>Wait for further communication from our team</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your patience and interest in the National Service Program.</p>
            <p>If you have any questions, please contact our support team.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


