<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Update</title>
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
            border-bottom: 2px solid #dc3545;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 15px 0;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }
        .content {
            margin: 20px 0;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
        }
        .rejection-reasons {
            background-color: #fff5f5;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .future-opportunities {
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
            <h2>Application Status Update</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>We have completed the review of your National Service application. Please find the details below:</p>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Review Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Reviewed By:</strong> {{ $reviewerName }}</p>
            </div>

            <div class="status-badge status-rejected">
                ❌ Application Not Selected
            </div>

            <p>We regret to inform you that your application has not been selected for the National Service Program at this time.</p>

            @if($comments)
                <div class="rejection-reasons">
                    <h4>📋 Review Comments</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>Common Reasons for Non-Selection</h4>
                <ul>
                    <li>Incomplete or inaccurate documentation</li>
                    <li>Failure to meet basic eligibility criteria</li>
                    <li>Medical fitness requirements not met</li>
                    <li>Limited positions available in current batch</li>
                    <li>Application submitted after deadline</li>
                </ul>
            </div>

            <div class="future-opportunities">
                <h4>🔄 Future Opportunities</h4>
                <p>While your application was not selected this time, we encourage you to:</p>
                <ul>
                    <li><strong>Apply for Future Batches:</strong> New application periods are announced regularly</li>
                    <li><strong>Improve Your Application:</strong> Address any issues mentioned in the feedback</li>
                    <li><strong>Stay Updated:</strong> Follow our announcements for new opportunities</li>
                    <li><strong>Seek Guidance:</strong> Contact our support team for application advice</li>
                    <li><strong>Prepare Better:</strong> Ensure all documents are complete and accurate</li>
                </ul>
            </div>

            <div class="info-box">
                <h4>📞 Support and Guidance</h4>
                <p>If you have questions about this decision or need guidance for future applications, please don't hesitate to contact our support team. We are here to help you understand the requirements and improve your chances for future applications.</p>
            </div>

            <div class="info-box">
                <h4>📅 Next Application Period</h4>
                <p>Information about the next application period will be announced on our official channels. We encourage you to stay connected and apply again when the opportunity arises.</p>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your interest in the National Service Program.</p>
            <p>We appreciate your understanding and encourage you to apply again in the future.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


