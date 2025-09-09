<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Started</title>
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
        .training-details {
            background: linear-gradient(135deg, #e8f5e8, #d4edda);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .expectations {
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
            <h2>Training Commenced</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>Congratulations! Your National Service training has officially commenced.</p>

            <div class="status-badge">
                🚀 Training Started
            </div>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Training Start Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Updated By:</strong> {{ $reviewerName }}</p>
            </div>

            @if(isset($trainingDetails))
                <div class="training-details">
                    <h4>🎯 Training Information</h4>
                    <p><strong>Training Program:</strong> {{ $trainingDetails['program'] ?? 'National Service Training' }}</p>
                    <p><strong>Duration:</strong> {{ $trainingDetails['duration'] ?? 'As per program schedule' }}</p>
                    <p><strong>Training Location:</strong> {{ $trainingDetails['location'] ?? 'Training Center' }}</p>
                    <p><strong>Batch:</strong> {{ $trainingDetails['batch'] ?? 'N/A' }}</p>
                </div>
            @endif

            <div class="expectations">
                <h4>📋 Training Expectations</h4>
                <p>During your training, you will be expected to:</p>
                <ul>
                    <li>Attend all scheduled training sessions</li>
                    <li>Participate actively in all activities</li>
                    <li>Follow all safety protocols and guidelines</li>
                    <li>Maintain discipline and professionalism</li>
                    <li>Complete all assigned tasks and assessments</li>
                    <li>Respect fellow trainees and instructors</li>
                    <li>Adhere to the training schedule and timings</li>
                </ul>
            </div>

            <div class="info-box">
                <h4>📋 Important Reminders</h4>
                <ul>
                    <li>Arrive on time for all training sessions</li>
                    <li>Bring required materials and equipment</li>
                    <li>Wear appropriate training attire</li>
                    <li>Follow all safety instructions</li>
                    <li>Report any issues to your instructors immediately</li>
                    <li>Maintain regular communication with your batch coordinator</li>
                </ul>
            </div>

            @if($comments)
                <div class="info-box">
                    <h4>Additional Information</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>📞 Support and Assistance</h4>
                <p>If you have any questions or need assistance during your training, please don't hesitate to contact your instructors or batch coordinator.</p>
            </div>
        </div>

        <div class="footer">
            <p>Welcome to your National Service training journey!</p>
            <p>We wish you success in your training program.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


