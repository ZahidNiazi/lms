<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joining Instructions</title>
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
        .welcome-header {
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
        .instructions-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .requirements-box {
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">National Service LMS</div>
            <h2>Joining Instructions</h2>
        </div>

        <div class="welcome-header">
            <h3>🎉 Welcome to the National Service Program!</h3>
            <h4>Your journey begins here</h4>
        </div>

        <div class="content">
            <p><strong>Date:</strong> {{ now()->format('M d, Y') }}</p>
            
            <p>Dear <strong>{{ $application->student->name }}</strong>,</p>

            <p>Congratulations on your selection for the National Service Program! We are excited to welcome you to this important journey of service to our nation.</p>

            <div class="info-box">
                <h4>📋 Your Details</h4>
                <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                <p><strong>Service ID:</strong> To be assigned upon joining</p>
                <p><strong>Batch Assignment:</strong> To be confirmed</p>
                <p><strong>Service Start Date:</strong> To be announced</p>
            </div>

            <div class="instructions-box">
                <h4>📋 Pre-Joining Requirements</h4>
                <p>Please ensure you complete the following before your service begins:</p>
                <ol>
                    <li><strong>Medical Examination:</strong> Complete final medical checkup at designated facility</li>
                    <li><strong>Document Verification:</strong> Bring all original documents for verification</li>
                    <li><strong>Emergency Contact:</strong> Update emergency contact information</li>
                    <li><strong>Personal Items:</strong> Prepare required personal items as per checklist</li>
                    <li><strong>Transportation:</strong> Arrange transportation to service location</li>
                </ol>
            </div>

            <div class="requirements-box">
                <h4>📦 Items to Bring</h4>
                <p><strong>Essential Documents:</strong></p>
                <ul>
                    <li>Original National ID Card</li>
                    <li>Original Educational Certificates</li>
                    <li>Medical Certificate</li>
                    <li>Emergency Contact Information</li>
                    <li>Bank Account Details (for stipend)</li>
                </ul>
                
                <p><strong>Personal Items:</strong></p>
                <ul>
                    <li>Appropriate clothing for service activities</li>
                    <li>Personal hygiene items</li>
                    <li>Prescribed medications (if any)</li>
                    <li>Notebook and writing materials</li>
                    <li>Mobile phone and charger</li>
                </ul>
            </div>

            <div class="info-box">
                <h4>🚀 Service Timeline</h4>
                <ol>
                    <li><strong>Orientation Week:</strong> Introduction to program and fellow service members</li>
                    <li><strong>Training Phase:</strong> Comprehensive training in various service areas</li>
                    <li><strong>Service Assignment:</strong> Placement in assigned service location</li>
                    <li><strong>Regular Assessments:</strong> Performance evaluations and feedback</li>
                    <li><strong>Service Completion:</strong> Final assessment and certification</li>
                </ol>
            </div>

            <div class="instructions-box">
                <h4>📞 Important Contacts</h4>
                <p><strong>Program Coordinator:</strong> To be assigned</p>
                <p><strong>Batch Supervisor:</strong> To be assigned</p>
                <p><strong>Emergency Contact:</strong> 24/7 support line</p>
                <p><strong>Administrative Office:</strong> For general inquiries</p>
            </div>

            <div class="info-box">
                <h4>⚠️ Important Reminders</h4>
                <ul>
                    <li>Arrive on time for all scheduled activities</li>
                    <li>Follow all safety protocols and guidelines</li>
                    <li>Maintain professional conduct at all times</li>
                    <li>Keep your contact information updated</li>
                    <li>Report any issues or concerns immediately</li>
                    <li>Participate actively in all program activities</li>
                </ul>
            </div>

            @if($comments)
                <div class="info-box">
                    <h4>Additional Instructions</h4>
                    <p>{{ $comments }}</p>
                </div>
            @endif

            <div class="info-box">
                <h4>🎯 What to Expect</h4>
                <p>Your National Service journey will be challenging yet rewarding. You will:</p>
                <ul>
                    <li>Develop valuable skills and experience</li>
                    <li>Contribute meaningfully to your community</li>
                    <li>Build lasting friendships with fellow service members</li>
                    <li>Gain a deeper understanding of national service</li>
                    <li>Receive recognition for your contributions</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p><strong>We look forward to welcoming you to the National Service Program!</strong></p>
            <p>Your dedication to serving the nation is commendable.</p>
            <p><strong>National Service LMS Team</strong></p>
        </div>
    </div>
</body>
</html>


