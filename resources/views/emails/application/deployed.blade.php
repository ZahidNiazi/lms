<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Deployment</title>
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
        .deployment-details {
            background: linear-gradient(135deg, #e8f5e8, #d4edda);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .service-expectations {
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
            <h2>Service Deployment</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>Congratulations! You have been successfully deployed to begin your National Service.</p>

            <div class="status-badge">
                🎯 Service Deployed
            </div>

            <div class="info-box">
                <h4>Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Deployment Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Deployed By:</strong> {{ $reviewerName }}</p>
            </div>

            @if(isset($deploymentDetails))
                <div class="deployment-details">
                    <h4>🎯 Deployment Information</h4>
                    <p><strong>Service Location:</strong> {{ $deploymentDetails['location'] ?? 'Service Location' }}</p>
                    <p><strong>Service Department:</strong> {{ $deploymentDetails['department'] ?? 'N/A' }}</p>
                    <p><strong>Service Duration:</strong> {{ $deploymentDetails['duration'] ?? 'As per program requirements' }}</p>
                    <p><strong>Supervisor:</strong> {{ $deploymentDetails['supervisor'] ?? 'To be assigned' }}</p>
                    <p><strong>Service Start Date:</strong> {{ $deploymentDetails['start_date'] ?? 'Immediate' }}</p>
                </div>
            @endif

            <div class="service-expectations">
                <h4>📋 Service Expectations</h4>
                <p>During your National Service, you will be expected to:</p>
                <ul>
                    <li>Perform your assigned duties with dedication and professionalism</li>
                    <li>Follow all workplace policies and procedures</li>
                    <li>Maintain regular attendance and punctuality</li>
                    <li>Respect your colleagues and supervisors</li>
                    <li>Contribute positively to your assigned department</li>
                    <li>Participate in any required training or development programs</li>
                    <li>Maintain confidentiality and security protocols</li>
                    <li>Report any issues or concerns to your supervisor</li>
                </ul>
            </div>

            <div class="info-box">
                <h4>📋 Important Reminders</h4>
                <ul>
                    <li>Arrive on time for your first day of service</li>
                    <li>Bring all required documents and identification</li>
                    <li>Dress appropriately for your workplace</li>
                    <li>Follow all safety protocols and guidelines</li>
                    <li>Maintain regular communication with your supervisor</li>
                    <li>Keep your contact information updated</li>
                    <li>Report any changes in your circumstances immediately</li>
                </ul>
            </div>

            @if($comments)
                <div class="info-box">
                    <h4>Deployment Information</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>📞 Support and Assistance</h4>
                <p>If you have any questions about your deployment or need assistance during your service, please contact your supervisor or our support team.</p>
            </div>
        </div>

        <div class="footer">
            <p>Welcome to your National Service deployment!</p>
            <p>We wish you success in your service to the nation.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


