<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Assignment</title>
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
        .batch-details {
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
            <h2>Batch Assignment</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>Congratulations! You have been assigned to a training batch for the National Service Program.</p>

            <div class="status-badge">
                🎯 Batch Assigned
            </div>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Assignment Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Assigned By:</strong> {{ $reviewerName }}</p>
            </div>

            @if(isset($batchDetails))
                <div class="batch-details">
                    <h4>🎯 Batch Information</h4>
                    <p><strong>Batch Name:</strong> {{ $batchDetails['name'] ?? 'Training Batch' }}</p>
                    <p><strong>Batch Code:</strong> {{ $batchDetails['code'] ?? 'N/A' }}</p>
                    <p><strong>Start Date:</strong> {{ $batchDetails['start_date'] ?? 'To be announced' }}</p>
                    <p><strong>Training Location:</strong> {{ $batchDetails['location'] ?? 'To be announced' }}</p>
                    <p><strong>Batch Size:</strong> {{ $batchDetails['size'] ?? 'N/A' }} participants</p>
                </div>
            @endif

            <div class="next-steps">
                <h4>🚀 What Happens Next?</h4>
                <ol>
                    <li><strong>Nomination Letter:</strong> You will receive your official nomination letter</li>
                    <li><strong>Medical Checkup:</strong> Final medical examination will be scheduled</li>
                    <li><strong>Training Schedule:</strong> Detailed training schedule will be provided</li>
                    <li><strong>Pre-Training Briefing:</strong> Orientation session will be conducted</li>
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
                    <li>Attend all scheduled briefings and sessions</li>
                    <li>Follow all instructions provided by your batch coordinator</li>
                </ul>
            </div>

            @if($comments)
                <div class="info-box">
                    <h4>Additional Information</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>📞 Contact Information</h4>
                <p>If you have any questions about your batch assignment or need assistance, please contact our support team or your batch coordinator.</p>
            </div>
        </div>

        <div class="footer">
            <p>Welcome to your National Service training batch!</p>
            <p>We look forward to your successful completion of the program.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


