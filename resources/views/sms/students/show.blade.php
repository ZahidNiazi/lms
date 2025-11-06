<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Student Details</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #4f7cff;
            --warning-yellow: #ffc107;
            --success-green: #28a745;
            --danger-red: #dc3545;
            --purple: #8b5cf6;
            --light-bg: #f8f9fa;
            --card-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            color: #333 !important;
            font-size: 1.25rem;
        }

        .card {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 12px;
        }

        .info-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .student-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-blue);
        }

        .info-item {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }

        .info-value {
            color: #6c757d;
        }

        .badge {
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.students.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Student Details
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('sms.dashboard') }}">
                            <i class="bi bi-house me-2"></i>Dashboard
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('job-portal.logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Student Details</h2>
                <p class="text-muted mb-0">Complete information about {{ $student->full_name }}</p>
            </div>
            <div>
                <a href="{{ route('sms.students.edit', $student->id) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <a href="{{ route('sms.students.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Students
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Student Photo and Basic Info -->
            <div class="col-md-4">
                <div class="info-section text-center">
                    @if($student->photo)
                        <img src="{{ asset($student->photo) }}" alt="Student Photo" class="student-photo mb-3">
                    @else
                        <div class="student-photo bg-light d-flex align-items-center justify-content-center mb-3 mx-auto">
                            <i class="bi bi-person display-4 text-muted"></i>
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $student->full_name }}</h4>
                    @if($student->name_in_dhivehi)
                        <p class="text-muted mb-2">{{ $student->name_in_dhivehi }}</p>
                    @endif
                    <p class="text-muted mb-3">{{ $student->student_id }}</p>
                    
                    @if($student->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
            </div>

            <!-- Student Information -->
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-person me-2"></i>Basic Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Student ID:</span>
                                <span class="info-value">{{ $student->student_id }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Rank:</span>
                                <span class="info-value">{{ $student->rank ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Gender:</span>
                                <span class="info-value">{{ ucfirst($student->gender) }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Date of Birth:</span>
                                <span class="info-value">{{ $student->date_of_birth->format('M d, Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Age:</span>
                                <span class="info-value">{{ $student->age ?? 'N/A' }} years</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Blood Group:</span>
                                <span class="info-value">{{ $student->blood_group ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Service Duration:</span>
                                <span class="info-value">{{ $student->service_duration ?? 'N/A' }} months</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Pay Amount:</span>
                                <span class="info-value">${{ number_format($student->pay_amount ?? 0, 2) }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status:</span>
                                <span class="info-value">{{ ucfirst($student->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-telephone me-2"></i>Contact Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Email:</span>
                                <span class="info-value">{{ $student->email }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">National ID:</span>
                                <span class="info-value">{{ $student->national_id }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Contact Number:</span>
                                <span class="info-value">{{ $student->contact_no }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Last Login:</span>
                                <span class="info-value">{{ $student->last_login_at ? $student->last_login_at->diffForHumans() : 'Never' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-geo-alt me-2"></i>Address Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Permanent Address</h6>
                            <div class="info-item">
                                <span class="info-label">Address:</span>
                                <span class="info-value">{{ $student->permanent_address_name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Atoll:</span>
                                <span class="info-value">{{ $student->permanentAtoll->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Island:</span>
                                <span class="info-value">{{ $student->permanentIsland->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">District:</span>
                                <span class="info-value">{{ $student->permanent_district ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Present Address</h6>
                            <div class="info-item">
                                <span class="info-label">Address:</span>
                                <span class="info-value">{{ $student->present_address_name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Atoll:</span>
                                <span class="info-value">{{ $student->presentAtoll->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Island:</span>
                                <span class="info-value">{{ $student->presentIsland->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">District:</span>
                                <span class="info-value">{{ $student->present_district ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-people me-2"></i>Parent Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Parent Name:</span>
                                <span class="info-value">{{ $student->parent_name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Relationship:</span>
                                <span class="info-value">{{ $student->parent_relationship ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Parent Email:</span>
                                <span class="info-value">{{ $student->parent_email ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Parent Contact:</span>
                                <span class="info-value">{{ $student->parent_contact_no ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Atoll:</span>
                                <span class="info-value">{{ $student->parentAtoll->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Islands:</span>
                                <span class="info-value">{{ $student->parentIsland->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($student->parent_address)
                        <div class="info-item">
                            <span class="info-label">Parent Address:</span>
                            <span class="info-value">{{ $student->parent_address }}</span>
                        </div>
                    @endif
                </div>

                <!-- Training Information -->
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-mortarboard me-2"></i>Training Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Batch:</span>
                                <span class="info-value">
                                    @if($student->batch)
                                        <span class="badge bg-info">{{ $student->batch->batch_name }}</span>
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Company:</span>
                                <span class="info-value">{{ $student->company->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Platoon:</span>
                                <span class="info-value">{{ $student->platoon->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Date of Joining:</span>
                                <span class="info-value">{{ $student->date_of_joining ? $student->date_of_joining->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Application Date:</span>
                                <span class="info-value">{{ $student->application_date ? $student->application_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Applicant Number:</span>
                                <span class="info-value">{{ $student->applicant_number ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Medical Information -->
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-mortarboard me-2"></i>Medical Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($student->medicalRecords as $index => $record)
                                <div class="info-item">
                                    <span class="info-label">Condition:</span>
                                    <span class="info-value">
                                        @if($record->medical_condition)
                                            <span >{{ $record->medical_condition }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Severity Level:</span>
                                    <span class="info-value">
                                        @if($record->medical_severity_level)
                                            <span >{{ $record->medical_severity_level }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Medical Notes:</span>
                                    <span class="info-value">
                                        @if($record->medical_notes)
                                            <span >{{ $record->medical_notes }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                            
                        </div>
                        
                    </div>
                </div>
                <!-- Academic Information --> 
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-mortarboard me-2"></i>Academic Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($student->AcademiclRecords as $index => $academic)
                                <div class="info-item">
                                    <span class="info-label">Document Type:</span>
                                    <span class="info-value">
                                        @if($academic->document_type)
                                            <span >{{ $academic->document_type }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Institution:</span>
                                    <span class="info-value">
                                        @if($academic->institution)
                                            <span >{{ $academic->institution }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Start Date:</span>
                                    <span class="info-value">
                                        @if($academic->start_date)
                                            <span >{{ $academic->start_date }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">End Date:</span>
                                    <span class="info-value">
                                        @if($academic->end_date)
                                            <span >{{ $academic->end_date }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                            
                        </div>
                        
                    </div>
                </div>
                <!-- Academic Information --> 
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-mortarboard me-2"></i>Observation
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($student->Observation as $index => $observation)
                                <div class="info-item">
                                    <span class="info-label">Type:</span>
                                    <span class="info-value">
                                        @if($observation->observation_type)
                                            <span >{{ $observation->observation_type }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Level:</span>
                                    <span class="info-value">
                                        @if($observation->severity_level)
                                            <span >{{ $observation->severity_level }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Notes:</span>
                                    <span class="info-value">
                                        @if($observation->observation_notes)
                                            <span >{{ $observation->observation_notes }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                
                            @endforeach
                            
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

