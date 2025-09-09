<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Criteria Review Update</title>
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
        .criteria-list {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
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
            <h2>Basic Criteria Review Update</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>We have completed the basic criteria review for your National Service application. Please find the details below:</p>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Review Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Reviewed By:</strong> {{ $reviewerName }}</p>
            </div>

            @if($status === 'approved')
                <div class="status-badge status-approved">
                    ✅ Basic Criteria Approved
                </div>
                <p>Excellent! You have successfully met all the basic criteria requirements for the National Service Program.</p>
                
                <div class="criteria-list">
                    <h4>✅ Criteria Met</h4>
                    <ul>
                        <li>Age requirements satisfied</li>
                        <li>Educational qualifications verified</li>
                        <li>Medical fitness confirmed</li>
                        <li>Citizenship status verified</li>
                        <li>All required documents submitted</li>
                    </ul>
                </div>
                
                <div class="info-box">
                    <h4>Next Steps</h4>
                    <p>Your application will now proceed to the final approval stage. You will be notified of the final decision soon.</p>
                </div>
            @elseif($status === 'rejected')
                <div class="status-badge status-rejected">
                    ❌ Basic Criteria Not Met
                </div>
                <p>We regret to inform you that your application does not meet the basic criteria requirements for the National Service Program.</p>
                
                @if($comments)
                    <div class="info-box">
                        <h4>Criteria Issues</h4>
                        <p>{{ $comments }}</p>
                    </div>
                @endif
                
                <div class="criteria-list">
                    <h4>Basic Criteria Requirements</h4>
                    <ul>
                        <li>Must be between 18-35 years of age</li>
                        <li>Must have completed secondary education</li>
                        <li>Must be medically fit</li>
                        <li>Must be a Maldivian citizen</li>
                        <li>Must not have any criminal record</li>
                    </ul>
                </div>
            @else
                <div class="status-badge status-pending">
                    📋 Basic Criteria Review In Progress
                </div>
                <p>Your basic criteria review is currently being processed. We will notify you once the review is complete.</p>
            @endif

            @if($comments)
                <div class="info-box">
                    <h4>Review Comments</h4>
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


