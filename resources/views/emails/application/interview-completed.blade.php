<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Results</title>
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
        .status-passed {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border: 2px solid #28a745;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
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
        .results-box {
            background: linear-gradient(135deg, #e8f5e8, #d4edda);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">National Service LMS</div>
            <h2>Interview Results</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>Thank you for attending your interview. We have completed the evaluation process and have the results for you.</p>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Interview Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Evaluated By:</strong> {{ $reviewerName }}</p>
            </div>

            @if($status === 'selected')
                <div class="status-badge status-passed">
                    🎉 Congratulations! Interview Passed
                </div>
                
                <div class="results-box">
                    <h4>✅ Interview Results</h4>
                    <p>Congratulations! You have successfully passed your interview and have been <strong>SELECTED</strong> for the National Service Program.</p>
                </div>
                
                <div class="next-steps">
                    <h4>🚀 What Happens Next?</h4>
                    <ol>
                        <li><strong>Batch Assignment:</strong> You will be assigned to a training batch</li>
                        <li><strong>Nomination Letter:</strong> You will receive your official nomination letter</li>
                        <li><strong>Medical Checkup:</strong> Final medical examination will be scheduled</li>
                        <li><strong>Training Preparation:</strong> You will receive training schedule and requirements</li>
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
                        <li>Respond promptly to any requests for information</li>
                    </ul>
                </div>
            @else
                <div class="status-badge status-failed">
                    ❌ Interview Not Successful
                </div>
                
                <div class="results-box">
                    <h4>📋 Interview Results</h4>
                    <p>We regret to inform you that you have not been selected for the National Service Program at this time.</p>
                </div>
                
                <div class="info-box">
                    <h4>🔄 Future Opportunities</h4>
                    <p>While you were not selected this time, we encourage you to:</p>
                    <ul>
                        <li>Consider applying for future batches</li>
                        <li>Improve any areas mentioned in the feedback</li>
                        <li>Stay updated on program announcements</li>
                        <li>Contact our support team for guidance</li>
                    </ul>
                </div>
            @endif

            @if($comments)
                <div class="info-box">
                    <h4>Interview Feedback</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>📞 Support and Guidance</h4>
                <p>If you have any questions about your interview results or need guidance for future applications, please don't hesitate to contact our support team.</p>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your interest in the National Service Program.</p>
            <p>We appreciate your participation in the interview process.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


