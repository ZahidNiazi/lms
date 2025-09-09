<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Scheduled</title>
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
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #1565c0;
            border: 2px solid #2196f3;
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
        .interview-details {
            background: linear-gradient(135deg, #e8f5e8, #d4edda);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .instructions-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
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
            <h2>Interview Scheduled</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>Congratulations! Your application has been reviewed and you have been selected for an interview. Please find the interview details below:</p>

            <div class="status-badge">
                📅 Interview Scheduled
            </div>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Scheduled Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Scheduled By:</strong> {{ $reviewerName }}</p>
            </div>

            @if(isset($interviewDetails))
                <div class="interview-details">
                    <h4>🎯 Interview Information</h4>
                    <p><strong>Interview Type:</strong> {{ ucwords(str_replace('_', ' ', $interviewDetails['type'] ?? 'General Interview')) }}</p>
                    <p><strong>Date:</strong> {{ $interviewDetails['date'] ?? 'To be confirmed' }}</p>
                    <p><strong>Time:</strong> {{ $interviewDetails['time'] ?? 'To be confirmed' }}</p>
                    <p><strong>Location:</strong> {{ $interviewDetails['location'] ?? 'To be confirmed' }}</p>
                    
                    @if(isset($interviewDetails['instructions']))
                        <div class="instructions-box">
                            <h5>📋 Important Instructions</h5>
                            <p>{{ $interviewDetails['instructions'] }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="info-box">
                <h4>📋 What to Bring</h4>
                <ul>
                    <li>Original National ID Card</li>
                    <li>Original Educational Certificates</li>
                    <li>Medical Certificate (if required)</li>
                    <li>Two recent passport-size photographs</li>
                    <li>Any other documents mentioned in your application</li>
                </ul>
            </div>

            <div class="instructions-box">
                <h4>⚠️ Important Reminders</h4>
                <ul>
                    <li>Arrive 15 minutes before your scheduled time</li>
                    <li>Dress appropriately for the interview</li>
                    <li>Bring all required documents</li>
                    <li>Be prepared to answer questions about your application</li>
                    <li>Contact us immediately if you cannot attend</li>
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
                <p>If you have any questions about your interview or need to reschedule, please contact our support team as soon as possible.</p>
            </div>
        </div>

        <div class="footer">
            <p>We look forward to meeting you at your interview!</p>
            <p>Good luck with your National Service application.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


