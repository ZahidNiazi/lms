<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Approval Update</title>
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
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 15px 0;
        }
        .status-approved {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border: 2px solid #28a745;
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
        .celebration-box {
            background: linear-gradient(135deg, #e8f5e8, #d4edda);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .next-steps {
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
            <h2>Final Approval Update</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>We have completed the final review of your National Service application. Please find the details below:</p>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Review Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Reviewed By:</strong> {{ $reviewerName }}</p>
            </div>

            @if($status === 'approved')
                <div class="status-badge status-approved">
                    🎉 CONGRATULATIONS! APPLICATION APPROVED
                </div>
                
                <div class="celebration-box">
                    <h3>🎊 Welcome to the National Service Program! 🎊</h3>
                    <p>We are delighted to inform you that your application has been <strong>APPROVED</strong> for the National Service Program!</p>
                </div>
                
                <div class="next-steps">
                    <h4>🚀 What Happens Next?</h4>
                    <ol>
                        <li><strong>Batch Assignment:</strong> You will be assigned to a training batch</li>
                        <li><strong>Nomination Letter:</strong> You will receive your official nomination letter</li>
                        <li><strong>Training Schedule:</strong> Details about your training program will be provided</li>
                        <li><strong>Medical Checkup:</strong> Final medical examination will be scheduled</li>
                        <li><strong>Training Commencement:</strong> Your National Service training will begin</li>
                    </ol>
                </div>
                
                <div class="info-box">
                    <h4>📋 Important Information</h4>
                    <p>Please ensure you:</p>
                    <ul>
                        <li>Check your email regularly for updates</li>
                        <li>Keep your contact information updated</li>
                        <li>Prepare for the training program</li>
                        <li>Complete any required medical examinations</li>
                    </ul>
                </div>
            @elseif($status === 'rejected')
                <div class="status-badge status-rejected">
                    ❌ Application Not Selected
                </div>
                <p>We regret to inform you that your application has not been selected for the National Service Program at this time.</p>
                
                @if($comments)
                    <div class="info-box">
                        <h4>Review Comments</h4>
                        <p>{{ $comments }}</p>
                    </div>
                @endif
                
                <div class="info-box">
                    <h4>Future Opportunities</h4>
                    <p>While your application was not selected this time, we encourage you to:</p>
                    <ul>
                        <li>Consider applying for future batches</li>
                        <li>Improve any areas mentioned in the feedback</li>
                        <li>Stay updated on program announcements</li>
                        <li>Contact our support team for guidance</li>
                    </ul>
                </div>
            @else
                <div class="status-badge status-pending">
                    📋 Final Review In Progress
                </div>
                <p>Your final review is currently being processed. We will notify you once the review is complete.</p>
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


