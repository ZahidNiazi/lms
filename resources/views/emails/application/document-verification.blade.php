<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification Update</title>
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
        .status-approved {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
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
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4f7cff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">National Service LMS</div>
            <h2>Document Verification Update</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>We have completed the document verification process for your National Service application. Please find the details below:</p>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Review Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Reviewed By:</strong> {{ $reviewerName }}</p>
            </div>

            @if($status === 'approved')
                <div class="status-badge status-approved">
                    ✅ Document Verification Complete
                </div>
                <p>Congratulations! Your documents have been successfully verified. All submitted documents meet our requirements and are authentic.</p>
                
                <div class="info-box">
                    <h4>Next Steps</h4>
                    <p>Your application will now proceed to the next stage of review. You will be notified once the basic criteria check is completed.</p>
                </div>
            @elseif($status === 'rejected')
                <div class="status-badge status-rejected">
                    ❌ Document Verification Failed
                </div>
                <p>We regret to inform you that your document verification has not been successful. Please review the issues below:</p>
                
                @if($comments)
                    <div class="info-box">
                        <h4>Issues Found</h4>
                        <p>{{ $comments }}</p>
                    </div>
                @endif
                
                <div class="info-box">
                    <h4>Required Actions</h4>
                    <p>Please resubmit your application with corrected documents. Ensure all documents are:</p>
                    <ul>
                        <li>Clear and legible</li>
                        <li>Authentic and unaltered</li>
                        <li>Complete and up-to-date</li>
                        <li>In the required format</li>
                    </ul>
                </div>
            @else
                <div class="status-badge status-pending">
                    📋 Document Verification In Progress
                </div>
                <p>Your document verification is currently being processed. We will notify you once the review is complete.</p>
            @endif

            @if($comments)
                <div class="info-box">
                    <h4>Additional Comments</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Thank you for your interest in the National Service Program.</p>
            <p>If you have any questions, please contact our support team.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


