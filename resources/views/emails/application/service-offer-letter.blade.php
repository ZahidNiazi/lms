<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Offer Letter</title>
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
        .official-header {
            background: linear-gradient(135deg, #e8f5e8, #d4edda);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
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
        .terms-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .signature-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
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
            <h2>Service Offer Letter</h2>
        </div>

        <div class="official-header">
            <h3>🎉 CONGRATULATIONS!</h3>
            <h4>You have been selected for the National Service Program</h4>
        </div>

        <div class="content">
            <p><strong>Date:</strong> {{ now()->format('M d, Y') }}</p>
            
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>We are delighted to inform you that you have been successfully selected to participate in the National Service Program. This is a significant achievement and we congratulate you on your selection.</p>

            <div class="info-box">
                <h4>📋 Application Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Selection Date:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Selected By:</strong> {{ $reviewerName }}</p>
            </div>

            <div class="info-box">
                <h4>🎯 Service Offer Details</h4>
                <p><strong>Program:</strong> National Service Program</p>
                <p><strong>Service Duration:</strong> As per program requirements</p>
                <p><strong>Service Type:</strong> Full-time National Service</p>
                <p><strong>Selection Status:</strong> Confirmed</p>
            </div>

            <div class="terms-box">
                <h4>📋 Terms and Conditions</h4>
                <p>By accepting this offer, you agree to:</p>
                <ul>
                    <li>Complete the full duration of the National Service Program</li>
                    <li>Follow all program rules and regulations</li>
                    <li>Maintain professional conduct throughout your service</li>
                    <li>Participate actively in all assigned activities</li>
                    <li>Respect fellow service members and supervisors</li>
                    <li>Maintain confidentiality of sensitive information</li>
                    <li>Report for duty as scheduled</li>
                    <li>Complete all required training and assessments</li>
                </ul>
            </div>

            <div class="info-box">
                <h4>🚀 Next Steps</h4>
                <ol>
                    <li><strong>Batch Assignment:</strong> You will be assigned to a training batch</li>
                    <li><strong>Medical Examination:</strong> Complete final medical checkup</li>
                    <li><strong>Training Preparation:</strong> Receive training schedule and requirements</li>
                    <li><strong>Service Commencement:</strong> Begin your National Service journey</li>
                </ol>
            </div>

            @if($comments)
                <div class="info-box">
                    <h4>Additional Information</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>📞 Important Contacts</h4>
                <p>For any questions or clarifications regarding this offer, please contact:</p>
                <ul>
                    <li>National Service LMS Support Team</li>
                    <li>Your assigned batch coordinator (to be provided)</li>
                    <li>Program administration office</li>
                </ul>
            </div>

            <div class="signature-section">
                <p><strong>This offer is valid and binding upon acceptance.</strong></p>
                <p>We look forward to welcoming you to the National Service Program and supporting you throughout your service journey.</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>National Service LMS</strong></p>
            <p>Ministry of Youth, Sports and Community Empowerment</p>
            <p>Republic of Maldives</p>
            <p>This is an official communication from the National Service Program.</p>
        </div>
    </div>
</body>
</html>


