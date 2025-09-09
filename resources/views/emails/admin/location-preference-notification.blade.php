<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Location Preference Notification</title>
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
        .email-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f7cff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f7cff;
            margin: 0;
            font-size: 24px;
        }
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-section h3 {
            color: #4f7cff;
            margin-top: 0;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #666;
        }
        .info-value {
            flex: 1;
        }
        .location-details {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4f7cff;
            margin: 15px 0;
        }
        .reason-section {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: #4f7cff;
            color: white;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📍 Interview Location Preference Submitted</h1>
            <p>A student has submitted their preferred interview location</p>
        </div>

        <div class="info-section">
            <h3>Student Information</h3>
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $student->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $student->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Application #:</div>
                <div class="info-value">
                    <span class="badge">{{ $application->application_number }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Submitted:</div>
                <div class="info-value">{{ now()->format('M d, Y h:i A') }}</div>
            </div>
        </div>

        <div class="location-details">
            <h3>📍 Selected Interview Location</h3>
            <div class="info-row">
                <div class="info-label">Location:</div>
                <div class="info-value"><strong>{{ $location->name }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value">{{ $location->getFullAddress() }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Contact:</div>
                <div class="info-value">{{ $location->getContactInfo() }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Capacity:</div>
                <div class="info-value">{{ $location->capacity }} students</div>
            </div>
            <div class="info-row">
                <div class="info-label">Facilities:</div>
                <div class="info-value">{{ $location->getFacilitiesList() }}</div>
            </div>
        </div>

        @if($preferenceReason)
        <div class="reason-section">
            <h3>💭 Student's Reason for Preference</h3>
            <p><em>"{{ $preferenceReason }}"</em></p>
        </div>
        @endif

        <div class="info-section">
            <h3>Next Steps</h3>
            <ul>
                <li>Review the student's location preference</li>
                <li>Consider the location capacity and facilities</li>
                <li>Schedule the interview at the preferred location if possible</li>
                <li>Update the student about the interview schedule</li>
            </ul>
        </div>

        <div class="footer">
            <p>This is an automated notification from the National Service LMS Job Portal.</p>
            <p>Please log in to the admin panel to view more details and take action.</p>
        </div>
    </div>
</body>
</html>


