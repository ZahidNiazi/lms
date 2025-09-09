<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Completed</title>
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
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border: 2px solid #28a745;
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
        .completion-details {
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
            <h2>Training Completed</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>Congratulations! You have successfully completed your National Service training program.</p>

            <div class="status-badge">
                🎓 Training Completed
            </div>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Completion Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Updated By:</strong> {{ $reviewerName }}</p>
            </div>

            @if(isset($trainingDetails))
                <div class="completion-details">
                    <h4>🎯 Training Completion Details</h4>
                    <p><strong>Training Program:</strong> {{ $trainingDetails['program'] ?? 'National Service Training' }}</p>
                    <p><strong>Duration Completed:</strong> {{ $trainingDetails['duration'] ?? 'Full program' }}</p>
                    <p><strong>Training Location:</strong> {{ $trainingDetails['location'] ?? 'Training Center' }}</p>
                    <p><strong>Batch:</strong> {{ $trainingDetails['batch'] ?? 'N/A' }}</p>
                    <p><strong>Completion Status:</strong> Successfully Completed</p>
                </div>
            @endif

            <div class="next-steps">
                <h4>🚀 What Happens Next?</h4>
                <ol>
                    <li><strong>Certificate Issuance:</strong> Your training completion certificate will be issued</li>
                    <li><strong>Deployment Assignment:</strong> You will be assigned to your deployment location</li>
                    <li><strong>Service Commencement:</strong> Your National Service will officially begin</li>
                    <li><strong>Performance Monitoring:</strong> Your service performance will be monitored</li>
                    <li><strong>Final Assessment:</strong> Final evaluation will be conducted at the end of service</li>
                </ol>
            </div>

            <div class="info-box">
                <h4>📋 Important Information</h4>
                <p>Please ensure you:</p>
                <ul>
                    <li>Keep your training completion certificate safe</li>
                    <li>Check your email regularly for deployment updates</li>
                    <li>Maintain your contact information updated</li>
                    <li>Prepare for your service deployment</li>
                    <li>Follow all instructions for the next phase</li>
                    <li>Continue to maintain the standards learned during training</li>
                </ul>
            </div>

            @if($comments)
                <div class="info-box">
                    <h4>Training Summary</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>📞 Support and Assistance</h4>
                <p>If you have any questions about your training completion or need assistance with the next steps, please contact our support team.</p>
            </div>
        </div>

        <div class="footer">
            <p>Congratulations on completing your National Service training!</p>
            <p>We wish you success in your service deployment.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


