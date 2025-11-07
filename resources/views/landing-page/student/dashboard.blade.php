<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .status-step {
            text-align: center;
        }
        .status-step .circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
            margin-bottom: 5px;
        }
        .status-step.completed .circle {
            background-color: #28a745;
            color: white;
        }
        .status-step.current .circle {
            background-color: #0d6efd;
            color: white;
        }
        .doc-status {
            font-size: 0.9rem;
            font-weight: bold;
        }
        .doc-status.uploaded {
            color: #28a745;
        }
        .doc-status.pending {
            color: #ffc107;
        }
        .doc-status.not-required {
            color: #6c757d;
        }
        .nav-tabs .nav-link {
            color: #6c757d;
        }
        .btn-link i {
            color: black;
        }

        .btn-link:hover i {
            color: black;
        }

        .btn {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn:active {
            transform: translateY(0);
        }
        .status-step {
            text-align: center;
            position: relative;
            flex: 1;
        }

        .status-step.completed {
            color: #28a745; /* Green for completed steps */
        }

        .status-step.current {
            color: #007bff; /* Blue for current step */
        }

        .status-step .circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 5px;
        }

        .status-step.completed .circle {
            border-color: #28a745;
            background: #28a745;
            color: white;
        }

        .status-step.current .circle {
            border-color: #007bff;
            background: #007bff;
            color: white;
        }


            .status-label {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }

        .bg-green {
            background-color: #28a745; /* Bootstrap-style green */
        }

        .bg-default {
            background-color: #6c757d; /* Neutral gray */
        }


    </style>
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Student Dashboard</h4>
        <div>
            <!-- <a href="{{ url('/job-portal') }}">Job Portal</a> -->
            <a href="javascript:void(0)"
            class="btn btn-link p-0 me-3"
            id="openNotifications">
                <i class="bi bi-bell fs-5"></i>
            </a>

            <a href="" class="btn btn-link p-0 me-3">
                <i class="bi bi-gear fs-5"></i>
            </a>
            <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-link p-0">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ session('active_tab') == 'documents' ? '' : 'active' }}" id="application-tab" data-bs-toggle="tab" data-bs-target="#application" type="button" role="tab">Application</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ session('active_tab') == 'documents' ? 'active' : '' }}" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Documents</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link position-relative" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                        Notifications
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-badge">
                                {{ $unreadNotificationsCount }}
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @elseif(isset($interviewSchedule) && $interviewSchedule && !$interviewSchedule->student_acknowledged)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                1
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @endif
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Application Tab -->
                <div class="tab-pane fade {{ session('active_tab') == 'documents' ? '' : 'show active' }}" id="application" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Application Status</h5>
                            <p class="text-muted">Track your application progress</p>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Status: 
                                    <span class="badge 
                                        @if(empty($student) || !isset($student->status))
                                            bg-warning
                                        @elseif($student->status === 'approved')
                                            bg-success
                                        @elseif($student->status === 'rejected')
                                            bg-danger
                                        @else
                                            bg-secondary
                                        @endif
                                        text-white">
                                        {{ ucfirst($student->status ?? 'Pending') }}
                                    </span>
                                </span></span>
                                @php
                                    $percentage = 0;
                                    $statusClass = 'bg-warning';

                                    // Progress calculation
                                    if(!empty($profile)) {
                                        $percentage = 50;
                                        $statusClass = 'bg-success';
                                    }
                                    if(!empty($student) && isset($student->status)){
                                        if($student->status === 'approved') {
                                            $percentage = 100;
                                            $statusClass = 'bg-success';
                                        }
                                    }

                                @endphp
                                {{ $percentage }}%
                            </div>
                            <div class="progress mb-4">
                                <div class="progress-bar {{ $statusClass }}"
                                    style="width: {{ $percentage }}%;"
                                    role="progressbar"
                                    aria-valuenow="{{ $percentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <!-- Step 1: Submitted -->
                                <div class="status-step completed">
                                    <div class="circle">✓</div>
                                    <div>Submitted</div>
                                </div>

                                <!-- Step 2: Profile Review -->
                                <div class="status-step @if($percentage >= 50) completed @endif">
                                    <div class="circle">@if($percentage >= 50) ✓ @else ⏳ @endif</div>
                                    <div>Profile Review</div>
                                </div>

                                <!-- Step 3: Approval -->
                                <div class="status-step @if($percentage >= 100) completed @endif">
                                    <div class="circle">@if($percentage >= 100) ✓ @else • @endif</div>
                                    <div>Approval</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>There were some errors with your submission:</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            {{-- <h2 class="mb-4">Student Application Form</h2> --}}
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="mb-0">Student Application Form</h2>

                                @if(isset($profile) && $profile->profile_picture)
                                    <div class="profile-pic-wrapper">
                                        <img src="{{ asset('storage/uploads/students/' . $profile->profile_picture) }}" 
                                            alt="Profile Picture" 
                                            width="120" height="120"
                                            class="rounded border object-fit-cover">
                                    </div>
                                @endif
                            </div>
                            <form id="profileForm" method="POST" action="{{ route('student.profile.submit') }}" enctype="multipart/form-data" >
                                @csrf

                                <h4 class="mb-3">Personal Information</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $profile->last_name ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nid" class="form-label">National ID</label>
                                        <input type="text" id="nid" name="nid" class="form-control" value="{{ old('nid', $profile->nid ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mobile_no" class="form-label">Mobile No</label>
                                        <input type="text" id="mobile_no" name="mobile_no" class="form-control" value="{{ old('mobile_no', $profile->mobile_no ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input type="date" id="dob" name="dob" class="form-control" value="{{ old('dob', $profile->dob ?? '') }}" required>
                                        <small class="text-muted">Age must be between 16 to 28 years</small>
                                        @error('dob')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profile_picture" class="form-label">Profile Picture</label>
                                            <input type="file" id="profile_picture" name="profile_picture" class="form-control" 
                                            {{ isset($profile) && $profile->profile_picture ? '' : 'required' }}>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">Permanent Address</h4>
                                <div class="row">
                                
                                    <h4 class="mt-4">Permanent Address</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Atoll</label>
                                            <select name="permanent_atoll_id" id="permanent_atoll" class="form-select">
                                                <option value="">Select Atoll</option>
                                                @foreach($atolls as $atoll)
                                                    <option value="{{ $atoll->id }}" {{ optional($student->permanentAddress)->atoll_id == $atoll->id ? 'selected' : '' }}>
                                                        {{ $atoll->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        {{-- Permanent Island --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Permanent Island</label>
                                            <select name="permanent_island_id" id="permanent_island" class="form-select" required>
                                                <option value="">Select Island</option>
                                                @if($permanentAddress && $permanentAddress->atoll && $permanentAddress->atoll->islands)
                                                    @foreach($permanentAddress->atoll->islands as $island)
                                                        <option value="{{ $island->id }}" 
                                                            {{ old('permanent_island_id', optional($permanentAddress)->island_id) == $island->id ? 'selected' : '' }}>
                                                            {{ $island->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">District</label>
                                        <input type="text" name="permanent_district" class="form-control" value="{{ old('permanent_district', $permanentAddress->district ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="permanent_address" class="form-control" value="{{ old('permanent_address', $permanentAddress->address ?? '') }}" required>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">Present Address</h4>
                                <div class="row">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Atoll</label>
                                            <select name="present_atoll_id" id="present_atoll" class="form-select">
                                                <option value="">Select Atoll</option>
                                                @foreach($atolls as $atoll)
                                                    <option value="{{ $atoll->id }}" {{ optional($student->presentAddress)->atoll_id == $atoll->id ? 'selected' : '' }}>
                                                        {{ $atoll->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                            {{-- Present Island --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Present Island</label>
                                            <select name="present_island_id" id="present_island" class="form-select" required>
                                                <option value="">Select Island</option>
                                                @if($presentAddress && $presentAddress->atoll && $presentAddress->atoll->islands)
                                                    @foreach($presentAddress->atoll->islands as $island)
                                                        <option value="{{ $island->id }}" 
                                                            {{ old('present_island_id', optional($presentAddress)->island_id) == $island->id ? 'selected' : '' }}>
                                                            {{ $island->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">District</label>
                                        <input type="text" name="present_district" class="form-control" value="{{ old('present_district', $presentAddress->district ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="present_address" class="form-control" value="{{ old('present_address', $presentAddress->address ?? '') }}" required>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-3">Parent Details</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $parentDetail->name ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Relation</label>
                                        <input type="text" name="parent_relation" class="form-control" value="{{ old('parent_relation', $parentDetail->relation ?? '') }}" required>
                                    </div>
                                    

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Parent Atoll</label>
                                            <select name="parent_atoll_id" id="parent_atoll" class="form-select">
                                                <option value="">Select Atoll</option>
                                                @foreach($atolls as $atoll)
                                                    <option value="{{ $atoll->id }}" {{ optional($student->parentDetail)->parent_atoll_id == $atoll->id ? 'selected' : '' }}>
                                                        {{ $atoll->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Parent Island</label>
                                            <select name="parent_island_id" id="parent_island" class="form-select">
                                                <option value="">Select Island</option>
                                                @if(optional(optional($student->parentDetail)->parentAtoll)->islands)
                                                    @foreach(optional(optional($student->parentDetail)->parentAtoll)->islands as $island)
                                                        <option value="{{ $island->id }}"
                                                            {{ optional($student->parentDetail)->parent_island_id == $island->id ? 'selected' : '' }}>
                                                            {{ $island->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="parent_address" class="form-control" value="{{ old('parent_address', $parentDetail->address ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mobile No</label>
                                        <input type="text" name="parent_mobile_no" class="form-control" value="{{ old('parent_mobile_no', $parentDetail->mobile_no ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="parent_email" class="form-control" value="{{ old('parent_email', $parentDetail->email ?? '') }}">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Interview Location Preference -->
                    <!-- @if(isset($jobPortalApplication) && $jobPortalApplication && $jobPortalApplication->status === 'approved')
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt me-2"></i>Interview Location Preference
                                </h5>

                                @if(isset($jobPortalApplication->preferred_interview_location_id) && $jobPortalApplication->preferred_interview_location_id)
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle me-2"></i>
                                        <strong>Location Preference Submitted:</strong>
                                        @if(isset($jobPortalApplication->preferredInterviewLocation) && $jobPortalApplication->preferredInterviewLocation)
                                            {{ $jobPortalApplication->preferredInterviewLocation->name }}
                                            @if(null !== $jobPortalApplication->preferredInterviewLocation->getFullAddress())
                                                <br><small class="text-muted">{{ $jobPortalApplication->preferredInterviewLocation->getFullAddress() }}</small>
                                            @endif
                                        @endif
                                        @if(null !== $jobPortalApplication->location_preference_submitted_at)
                                            <br><small class="text-muted">Submitted on: {{ $jobPortalApplication->location_preference_submitted_at->format('M d, Y h:i A') }}</small>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Action Required:</strong> Please select your preferred interview location.
                                    </div>

                                    <form id="locationPreferenceForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="location_id" class="form-label">Preferred Interview Location</label>
                                            <select class="form-select" id="location_id" name="location_id" required>
                                                <option value="">Select your preferred location...</option>
                                                @if(isset($interviewLocations) && $interviewLocations->count() > 0)
                                                    @foreach($interviewLocations as $location)
                                                        <option value="{{ $location['id'] }}"
                                                                title="Contact: {{ $location['contact_info'] }}, Capacity: {{ $location['capacity'] }}, Facilities: {{ $location['facilities'] }}">
                                                            {{ $location['name'] }} - {{ $location['address'] }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="preference_reason" class="form-label">Reason for Preference (Optional)</label>
                                            <textarea class="form-control" id="preference_reason" name="preference_reason" rows="3" placeholder="Please explain why you prefer this location..."></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>Submit Preference
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif -->

                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade {{ session('active_tab') == 'documents' ? 'show active' : '' }}" id="documents" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Document Management</h5>
                            <p class="text-muted">Upload and manage your application documents</p>

                            <!-- Document Upload Form -->
                            <form id="document-upload-form" method="POST" action="{{ route('student.documents.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="type" class="form-label">Document Type</label>
                                    <select id="type" name="type" class="form-select" required>
                                        <option value="">Select Document Type</option>
                                        @if(isset($student) && $student->is_under_age_18)
                                            <option value="parent_consent">Parent Consent Form</option>
                                        @endif
                                        <option value="photo">Photo</option>
                                        <option value="nid_copy">NID Copy</option>
                                        <option value="school_leaving">School Leaving Certificate</option>
                                        <option value="olevel">O-Level Certificate</option>
                                        <option value="alevel">A-Level Certificate</option>
                                        <option value="police_report">Police Report</option>
                                    </select>
                                </div>

                                <!-- Dynamic fields based on document type -->
                                <div id="document-fields">
                                    <!-- Fields will be loaded here via JavaScript -->
                                </div>

                                <div class="mb-3">
                                    <label for="file" class="form-label">File</label>
                                    <input type="file" id="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Allowed formats: PDF, JPG, JPEG, PNG (Max 2MB)</small>
                                </div>

                                <button type="submit" class="btn btn-primary" id="upload-btn">
                                    <span class="btn-text">Upload Document</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </form>

                            <hr class="my-4">

                            <!-- Document List -->
                            <h6>Uploaded Documents</h6>
                            @if(isset($documents) && $documents->count() > 0)
                                <div class="list-group">
                                    @foreach($documents as $document)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $document->type)) }}</strong>
                                                @if($document->school_name)
                                                    <br><small class="text-muted">School: {{ $document->school_name }}</small>
                                                @endif
                                                @if($document->year)
                                                    <br><small class="text-muted">Year: {{ $document->year }}</small>
                                                @endif
                                                @if($document->report_number)
                                                    <br><small class="text-muted">Report No: {{ $document->report_number }}</small>
                                                @endif
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('student.documents.download', $document) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument({{ $document->id }})">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Tab -->
                <div class="tab-pane fade" id="profile" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Profile Information</h5>
                            <p class="text-muted">View and update your personal information</p>

                            <div class="mb-3 row">
                                <label class="col-sm-3 fw-bold">Full Name</label>
                                <div class="col-sm-9">{{$profile->first_name ?? 'N/A'}} {{$profile->last_name ?? ''}}</div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 fw-bold">National ID</label>
                                <div class="col-sm-9">{{$profile->nid ?? 'N/A'}}</div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 fw-bold">Email</label>
                                <div class="col-sm-9">{{$parentDetail->email ?? 'N/A'}}</div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 fw-bold">Phone</label>
                                <div class="col-sm-9">{{$profile->mobile_no ?? 'N/A'}}</div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 fw-bold">Date of Birth</label>
                                <div class="col-sm-9">
                                    @if(!empty($profile) && !empty($profile->dob))
                                        {{ \Carbon\Carbon::parse($profile->dob)->format('d M, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 fw-bold">Age</label>
                                <div class="col-sm-9">
                                    @if(!empty($profile) && !empty($profile->dob))
                                        {{ \Carbon\Carbon::parse($profile->dob)->age }} years
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-primary btn-sm" onclick="switchToApplicationTab()">
                                    <i class="bi bi-pencil me-1"></i> Edit Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Notifications Tab -->
                <div class="tab-pane fade" id="notifications" role="tabpanel">
                    <!-- Notifications Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="bi bi-bell me-2"></i>Messages & Notifications
                            @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                <span class="badge bg-danger ms-2">{{ $unreadNotificationsCount }}</span>
                            @endif
                        </h5>
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                                <i class="bi bi-check-all me-1"></i>Mark All as Read
                            </button>
                        @endif
                        <!-- <button class="btn btn-sm btn-outline-info ms-2" onclick="testNotifications()">
                            <i class="bi bi-bug me-1"></i>Test
                        </button> -->
                    </div>

                    <!-- Notifications List -->
                    @if(isset($notifications) && $notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <div class="card mb-3 notification-item {{ !$notification->is_read ? 'border-primary' : '' }}"
                                 data-notification-id="{{ $notification->id }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-2">
                                                <i class="bi bi-{{ $notification->type === 'info' ? 'info-circle' : ($notification->type === 'success' ? 'check-circle' : ($notification->type === 'warning' ? 'exclamation-triangle' : 'x-circle')) }}
                                                   text-{{ $notification->type === 'info' ? 'primary' : ($notification->type === 'success' ? 'success' : ($notification->type === 'warning' ? 'warning' : 'danger')) }} me-2"></i>
                                                {{ $notification->title }}
                                                @if(!$notification->is_read)
                                                    <span class="badge bg-primary ms-2">New</span>
                                                @endif
                                            </h6>
                                            <p class="card-text mb-2">{{ $notification->message }}</p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                @if($notification->application)
                                                    | <i class="bi bi-file-earmark me-1"></i>Application #{{ $notification->application->application_number }}
                                                @endif
                                            </small>
                                        </div>
                                        @if(!$notification->is_read)
                                            <button class="btn btn-sm btn-outline-primary ms-2" onclick="markAsRead({{ $notification->id }})">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3 mb-3">
                            <i class="bi bi-bell-slash text-muted" style="font-size: 2rem;"></i>
                            <h6 class="mt-2 text-muted">No Messages</h6>
                            <p class="text-muted mb-0">You don't have any messages yet.</p>
                        </div>
                    @endif

                    <hr class="my-4">

                    <!-- Eligibility Check -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-check-circle-fill me-2"></i>Eligibility Status
                            </h5>
                            @if(isset($eligibility))
                                <div class="alert {{ $eligibility['is_eligible'] ? 'alert-success' : 'alert-warning' }}">
                                    <strong>Age: {{ $eligibility['age'] ?? 'Not specified' }} years</strong><br>
                                    {{ $eligibility['message'] }}
                                </div>
                                @if($eligibility['needs_parent_consent'])
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Parent Consent Required:</strong> Since you are under 18, you must upload a parent consent form to complete your application.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Application Status -->
                    @if(isset($jobPortalApplication))
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-file-earmark-text me-2"></i>Application Status
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Application Number:</strong> {{ $jobPortalApplication->application_number }}</p>
                                        <p><strong>Status:</strong>
                                            <span class="badge
                                                @if($jobPortalApplication->status == 'pending_review') bg-warning
                                                @elseif($jobPortalApplication->status == 'approved') bg-success
                                                @elseif($jobPortalApplication->status == 'rejected') bg-danger
                                                @elseif($jobPortalApplication->status == 'interview_scheduled') bg-info
                                                @elseif($jobPortalApplication->status == 'selected') bg-primary
                                                @else bg-secondary
                                                @endif">
                                                {{ ucwords(str_replace('_', ' ', $jobPortalApplication->status)) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Applied Date:</strong> {{ $jobPortalApplication->created_at->format('M d, Y') }}</p>
                                        @if($jobPortalApplication->reviewed_at)
                                            <p><strong>Last Reviewed:</strong> {{ $jobPortalApplication->reviewed_at->format('M d, Y') }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($jobPortalApplication->remarks)
                                    <div class="alert alert-light">
                                        <strong>Remarks:</strong> {{ $jobPortalApplication->remarks }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-file-earmark-plus me-2"></i>Create Application
                                </h5>
                                <p class="text-muted">Complete your profile to create your National Service application.</p>
                                @if(!$student->profile_completed)
                                    <a href="#application" class="btn btn-primary" onclick="switchToApplicationTab()">
                                        <i class="bi bi-pencil me-2"></i>Complete Profile
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Interview Schedule -->
                    @if(isset($interviewSchedule) && $interviewSchedule)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-calendar-event me-2"></i>Interview Schedule
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Date:</strong> {{ null !== $interviewSchedule->interview_date ? $interviewSchedule->interview_date->format('M d, Y') : 'To be announced' }}</p>
                                        <p><strong>Time:</strong> {{ null !== $interviewSchedule->interview_time ? $interviewSchedule->interview_time->format('h:i A') : 'To be announced' }}</p>
                                        <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $interviewSchedule->interview_type ?? 'Interview')) }}</p>
                                        <p><strong>Location:</strong>
                                            @if(isset($interviewSchedule->location) && $interviewSchedule->location)
                                                {{ $interviewSchedule->location->name }}
                                                @if(null !== $interviewSchedule->location->getFullAddress())
                                                    <br><small class="text-muted">{{ $interviewSchedule->location->getFullAddress() }}</small>
                                                @endif
                                            @else
                                                {{ $interviewSchedule->venue ?? 'To be announced' }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        @if($interviewSchedule->dress_code)
                                            <p><strong>Dress Code:</strong> {{ $interviewSchedule->dress_code }}</p>
                                        @endif
                                        @if($interviewSchedule->travel_arrangements)
                                            <p><strong>Travel:</strong> {{ $interviewSchedule->travel_arrangements }}</p>
                                        @endif
                                        @if($interviewSchedule->accommodation_arrangements)
                                            <p><strong>Accommodation:</strong> {{ $interviewSchedule->accommodation_arrangements }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($interviewSchedule->status === 'scheduled' && !$interviewSchedule->student_acknowledged)
                                    <div class="alert alert-info mt-3">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Action Required:</strong> Please acknowledge this interview schedule to confirm your attendance.
                                        <form method="POST" action="{{ route('student.interview.acknowledge', $interviewSchedule->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary ms-2">
                                                <i class="bi bi-check-circle me-1"></i>Acknowledge
                                            </button>
                                        </form>
                                    </div>
                                @elseif($interviewSchedule->student_acknowledged)
                                    <div class="alert alert-success mt-3">
                                        <i class="bi bi-check-circle me-2"></i>
                                        <strong>Confirmed:</strong> You have acknowledged this interview schedule.
                                        @if(null !== $interviewSchedule->acknowledged_at)
                                            <small class="d-block">Acknowledged on: {{ $interviewSchedule->acknowledged_at->format('M d, Y h:i A') }}</small>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif


                    <!-- Ongoing Programs -->
                    @if(isset($ongoingPrograms) && $ongoingPrograms['has_ongoing_program'])
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-calendar-check me-2"></i>Active Programs
                                </h5>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <strong>Good News!</strong> There are currently {{ $ongoingPrograms['active_batches'] }} active National Service program(s) accepting applications.
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-calendar-x me-2"></i>Program Status
                                </h5>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>No Active Programs:</strong> There are currently no active National Service programs. Please check back later or contact us for more information.
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5>Quick Actions</h5>
                    <button type="button" class="btn btn-primary w-100 mb-2" onclick="switchToDocumentsTab()">
                        <i class="bi bi-upload me-2"></i>Upload Documents
                    </button>
                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" onclick="switchToProfileTab()">
                        <i class="bi bi-person me-2"></i>Update Profile
                    </button>
                    <!-- <a href="{{ route('student.status') }}" class="btn btn-outline-info w-100">
                        <i class="bi bi-info-circle me-2"></i>Application Status
                    </a> -->
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5>Contact Support</h5>
                    <p><strong>Email:</strong><br> support@nationalservice.gov.mv</p>
                    <p><strong>Phone:</strong><br> +960 320-5500</p>
                    <p><strong>Office Hours:</strong><br> 8:00 AM - 4:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script>
function updateDocStatus(input) {
    const file = input.files[0];
    const statusEl = input.parentElement.querySelector('.doc-status');

    if (!file) {
        statusEl.textContent = 'Pending';
        statusEl.classList.remove('text-success');
        statusEl.classList.add('text-danger');
        return;
    }

    // Validate file size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be under 2MB.');
        input.value = '';
        statusEl.textContent = 'Pending';
        statusEl.classList.remove('text-success');
        statusEl.classList.add('text-danger');
        return;
    }

    // Validate file type
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        alert('Only PDF, JPG, JPEG, PNG files are allowed.');
        input.value = '';
        statusEl.textContent = 'Pending';
        statusEl.classList.remove('text-success');
        statusEl.classList.add('text-danger');
        return;
    }

    // If valid
    statusEl.textContent = 'Uploaded';
    statusEl.classList.remove('text-danger');
    statusEl.classList.add('text-success');
}
</script> -->

<script>
    document.getElementById('openNotifications').addEventListener('click', function () {
    var triggerEl = document.querySelector('#notifications-tab');
    var tab = new bootstrap.Tab(triggerEl);
    tab.show();
});

(function() {
  const form = document.getElementById('profileForm');
  if (!form) { console.warn('profileForm not found on page; skipping validation init'); return; }

  // utility to show feedback
  function setInvalid(input, message) {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
    let feedback = input.nextElementSibling;
    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
      feedback = document.createElement('div');
      feedback.className = 'invalid-feedback';
      input.parentNode.insertBefore(feedback, input.nextSibling);
    }
    feedback.textContent = message;
  }

  function setValid(input) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    const feedback = input.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
      feedback.textContent = '';
    }
  }

  function clearValidation(input) {
    input.classList.remove('is-invalid', 'is-valid');
    const feedback = input.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) feedback.textContent = '';
  }

  // field validators
  function validateFirstName() {
    const input = document.getElementById('first_name');
    const val = (input.value || '').trim();
    if (!val) {
      setInvalid(input, 'First name is required.');
      return false;
    }
    setValid(input);
    return true;
  }

  function validateDOB() {
    const input = document.getElementById('dob');
    const val = (input.value || '').trim();
    if (!val) {
      setInvalid(input, 'Date of birth is required.');
      return false;
    }
    // optional: check age range 16-28 (comment/uncomment if needed)
    const dob = new Date(val);
    const diff = Date.now() - dob.getTime();
    const age = Math.floor(diff / (1000*60*60*24*365.25));
    if (age < 16 || age > 28) { setInvalid(input, 'Age must be between 16 and 28 years.'); return false; }

    setValid(input);
    return true;
  }

  function validateNID() {
    const input = document.getElementById('nid');
    const val = (input.value || '').trim();
    if (!val) {
      setInvalid(input, 'National ID is required.');
      return false;
    }
    if (!/^\d+$/.test(val)) {
      setInvalid(input, 'National ID must contain only digits.');
      return false;
    }
    setValid(input);
    return true;
  }

  function validateMobile() {
    const input = document.getElementById('mobile_no');
    const val = (input.value || '').trim();
    if (!val) {
      setInvalid(input, 'Mobile number is required.');
      return false;
    }
    if (!/^\d+$/.test(val)) {
      setInvalid(input, 'Mobile number must contain only digits.');
      return false;
    }
    if (val.length > 15) {
      setInvalid(input, 'Mobile number must be at most 15 digits.');
      return false;
    }
    setValid(input);
    return true;
  }

  // sanitize inputs while typing: keep only digits for nid and mobile
  ['nid', 'mobile_no'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('input', function() {
      const start = el.selectionStart;
      // remove all non-digit chars
      const clean = el.value.replace(/\D+/g, '');
      el.value = clean;
      // remove validation state when typing
      clearValidation(el);
      // try re-validate lightly
      if (id === 'nid' && clean !== '') validateNID();
      if (id === 'mobile_no' && clean !== '') validateMobile();
      // restore cursor near end (simple approach)
      el.setSelectionRange(el.value.length, el.value.length);
    });
  });

  // validate on blur for immediate feedback
  document.getElementById('first_name').addEventListener('blur', validateFirstName);
  document.getElementById('dob').addEventListener('blur', validateDOB);
  document.getElementById('nid').addEventListener('blur', validateNID);
  document.getElementById('mobile_no').addEventListener('blur', validateMobile);

  // final form submit check
  form.addEventListener('submit', function(e) {
    let ok = true;

    if (!validateFirstName()) ok = false;
    if (!validateDOB()) ok = false;
    if (!validateNID()) ok = false;
    if (!validateMobile()) ok = false;

    if (!ok) {
        e.preventDefault();  // stops submission
        e.stopPropagation(); // stops bubbling
        const firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.focus();
    }
});

})();

// Document type selector functionality
document.addEventListener('DOMContentLoaded', function() {
    const typeSelector = document.querySelector('#type');
    const fieldsContainer = document.getElementById('document-fields');

    if (typeSelector && fieldsContainer) {
        typeSelector.addEventListener('change', function() {
            const type = this.value;
            let html = '';

            switch(type) {
                case 'school_leaving':
                    html = `
                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name</label>
                            <input type="text" id="school_name" name="school_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" id="year" name="year" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="report_number" class="form-label">Report Number</label>
                            <input type="text" id="report_number" name="report_number" class="form-control" required>
                        </div>
                    `;
                    break;

                case 'olevel':
                case 'alevel':
                    html = `
                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name</label>
                            <input type="text" id="school_name" name="school_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" id="year" name="year" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="subjects" class="form-label">Subjects</label>
                            <input type="text" id="subjects" name="subjects" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="result" class="form-label">Result</label>
                            <input type="text" id="result" name="result" class="form-control" required>
                        </div>
                    `;
                    break;

                case 'police_report':
                    html = `
                        <div class="mb-3">
                            <label for="report_number" class="form-label">Report Number</label>
                            <input type="text" id="report_number" name="report_number" class="form-control" required>
                        </div>
                    `;
                    break;

                default:
                    html = '';
            }

            fieldsContainer.innerHTML = html;
        });
    }
});

// Global functions for tab switching
function switchTab(targetId) {
    console.log('Switching to tab:', targetId);

    // Remove active class from all tabs and tab buttons
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
    });

    // Add active class to target tab button
    const targetButton = document.querySelector(`[data-bs-target="${targetId}"]`);
    if (targetButton) {
        targetButton.classList.add('active');
    }

    // Add active class to target tab pane
    const targetPane = document.querySelector(targetId);
    if (targetPane) {
        targetPane.classList.add('show', 'active');
    }
}

function switchToApplicationTab() {
    console.log('Edit Profile button clicked - switching to application tab');
    switchTab('#application');
}

function switchToProfileTab() {
    console.log('Update Profile button clicked - switching to profile tab');
    switchTab('#profile');
}

function switchToDocumentsTab() {
    console.log('Upload Documents button clicked - switching to documents tab');
    switchTab('#documents');
}

// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard JavaScript loaded');

    // Check if we need to switch to a specific tab from session
    @if(session('active_tab'))
        const activeTab = '{{ session("active_tab") }}';
        console.log('Switching to tab:', activeTab);
        switchTab('#' + activeTab);
    @endif

    // Handle all tab buttons
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetTab = this.getAttribute('data-bs-target');
            console.log('Tab button clicked:', targetTab);
            switchTab(targetTab);
        });
    });

    // Handle upload documents button
    const uploadDocsBtn = document.querySelector('a[data-bs-target="#documents"]');
    if (uploadDocsBtn) {
        uploadDocsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Upload Documents button clicked');
            switchTab('#documents');
        });
    }

    // Document upload form handling
    const documentForm = document.getElementById('document-upload-form');
    const fileInputEl = document.getElementById('file');
    const uploadBtn = document.getElementById('upload-btn');

    // Immediate validation on file choose (2MB max)
    if (fileInputEl) {
        fileInputEl.addEventListener('change', function () {
            const f = this.files && this.files[0] ? this.files[0] : null;
            if (f && f.size > 2 * 1024 * 1024) {
                // Mark invalid and keep button disabled
                this.setCustomValidity('File must be less than 2 MB');
                this.reportValidity();
                window.showAlert('Please upload a file less than 2 MB.', 'warning');
                if (uploadBtn) uploadBtn.disabled = true;
            } else {
                // Clear invalid state and enable button
                this.setCustomValidity('');
                if (uploadBtn) uploadBtn.disabled = false;
            }
        });
    }

    if (documentForm) {
        documentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const btnText = uploadBtn.querySelector('.btn-text');
            const spinner = uploadBtn.querySelector('.spinner-border');

            // Size validation BEFORE showing loading state
            const fileField = this.querySelector('#file');
            const fileObj = fileField && fileField.files ? fileField.files[0] : null;
            if (!fileObj) {
                fileField.setCustomValidity('Please select a file to upload.');
                fileField.reportValidity();
                window.showAlert('Please select a file to upload.', 'warning');
                if (uploadBtn) uploadBtn.disabled = false;
                return;
            }
            if (fileObj.size > 2 * 1024 * 1024) {
                fileField.setCustomValidity('File must be less than 2 MB');
                fileField.reportValidity();
                window.showAlert('Please upload a file less than 2 MB.', 'warning');
                if (uploadBtn) uploadBtn.disabled = true;
                return;
            }

            // Clear any previous validity message
            fileField.setCustomValidity('');

            // Show loading state
            btnText.textContent = 'Uploading...';
            spinner.classList.remove('d-none');
            uploadBtn.disabled = true;

            // Create FormData
            const formData = new FormData(this);

            // Debug: Log form data
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            // Submit via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return response.json();
            })
                .then(data => {
                    console.log('Response data:', data);

                    if (data.success) {
                        // Show success message
                        window.showAlert('Document uploaded successfully!', 'success');

                        // Reset form
                        this.reset();
                        document.getElementById('document-fields').innerHTML = '';

                        // Reload the page to show updated document list
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        window.showAlert(data.message || 'Error uploading document', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    window.showAlert('Error uploading document: ' + error.message, 'danger');
                })
            .finally(() => {
                // Reset button state
                btnText.textContent = 'Upload Document';
                spinner.classList.add('d-none');
                uploadBtn.disabled = false;
            });
        });
    }

    // Document type selector handling
    const typeSelector = document.getElementById('type');
    const fieldsContainer = document.getElementById('document-fields');

    if (typeSelector && fieldsContainer) {
        typeSelector.addEventListener('change', function() {
            const type = this.value;
            let html = '';

            switch(type) {
                case 'school_leaving':
                    html = `
                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name</label>
                            <input type="text" id="school_name" name="school_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" id="year" name="year" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="report_number" class="form-label">Report Number</label>
                            <input type="text" id="report_number" name="report_number" class="form-control" required>
                        </div>
                    `;
                    break;

                case 'olevel':
                case 'alevel':
                    html = `
                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name</label>
                            <input type="text" id="school_name" name="school_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" id="year" name="year" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="subjects" class="form-label">Subjects</label>
                            <input type="text" id="subjects" name="subjects" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="result" class="form-label">Result</label>
                            <input type="text" id="result" name="result" class="form-control" required>
                        </div>
                    `;
                    break;

                case 'police_report':
                    html = `
                        <div class="mb-3">
                            <label for="report_number" class="form-label">Report Number</label>
                            <input type="text" id="report_number" name="report_number" class="form-control" required>
                        </div>
                    `;
                    break;

                default:
                    html = '';
            }

            fieldsContainer.innerHTML = html;
        });
    }

    // Alert function - moved to global scope
    window.showAlert = function(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Insert at the top of the documents tab
        const documentsTab = document.getElementById('documents');
        const firstCard = documentsTab.querySelector('.card');
        if (firstCard) {
            firstCard.insertBefore(alertDiv, firstCard.firstChild);
        }

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    };

    // Delete document function - moved to global scope
    window.deleteDocument = function(documentId) {
        if (!confirm('Are you sure you want to delete this document?')) {
            return;
        }

        const deleteUrl = `{{ url('student/documents') }}/${documentId}`;

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.showAlert('Document deleted successfully!', 'success');
                // Reload the page to show updated document list
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                window.showAlert(data.message || 'Error deleting document', 'danger');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            window.showAlert('Error deleting document: ' + error.message, 'danger');
        });
    };

    // Initialize notification count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateNotificationCount();
    });
});

// Global notification functions
function markAsRead(notificationId) {
    console.log('Marking notification as read:', notificationId);

    fetch(`/student/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('border-primary');
                const badge = notificationItem.querySelector('.badge');
                if (badge) badge.remove();
                const button = notificationItem.querySelector('button');
                if (button) button.remove();
            }
            updateNotificationCount();
        } else {
            console.error('Failed to mark notification as read:', data.error);
            alert('Failed to mark notification as read: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        alert('Error marking notification as read. Please try again.');
    });
}

function markAllAsRead() {
    console.log('Marking all notifications as read');

    fetch('/student/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Remove all "New" badges and mark buttons
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('border-primary');
                const badge = item.querySelector('.badge');
                if (badge) badge.remove();
                const button = item.querySelector('button');
                if (button) button.remove();
            });
            updateNotificationCount();
            console.log('Updated count:', data.updated_count);
        } else {
            console.error('Failed to mark all notifications as read:', data.error);
            alert('Failed to mark all notifications as read: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
        alert('Error marking all notifications as read. Please try again.');
    });
}

function updateNotificationCount() {
    const unreadCount = document.querySelectorAll('.notification-item.border-primary').length;
    const tabBadge = document.querySelector('#notification-badge');
    const tab = document.querySelector('#notifications-tab');

    if (unreadCount > 0) {
        if (tabBadge) {
            // Update existing badge
            tabBadge.textContent = unreadCount;
        } else {
            // Create new badge
            const badge = document.createElement('span');
            badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
            badge.id = 'notification-badge';
            badge.textContent = unreadCount;
            badge.innerHTML += '<span class="visually-hidden">unread notifications</span>';
            tab.appendChild(badge);
        }
    } else {
        // Remove badge if no unread notifications
        if (tabBadge) {
            tabBadge.remove();
        }
    }
}

// Test function for debugging
function testNotifications() {
    console.log('Testing notifications...');

    fetch('/student/test-notifications', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        console.log('Test response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Test response data:', data);
        alert('Test Results:\n' +
              'Authenticated: ' + data.authenticated + '\n' +
              'Student ID: ' + data.student_id + '\n' +
              'Total Notifications: ' + data.notifications_count + '\n' +
              'Unread Count: ' + data.unread_count);
    })
    .catch(error => {
        console.error('Test error:', error);
        alert('Test failed: ' + error.message);
    });
}

// Interview Location Selection Functions
function loadInterviewLocations() {
    console.log('Loading interview locations...');
    fetch('/student/interview-locations', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const select = document.getElementById('location_id');
            if (select) {
                select.innerHTML = '<option value="">Select your preferred location...</option>';
                data.locations.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.id;
                    option.textContent = `${location.name} - ${location.address}`;
                    option.title = `Contact: ${location.contact_info}\nCapacity: ${location.capacity}\nFacilities: ${location.facilities}`;
                    select.appendChild(option);
                });
                console.log('Loaded', data.locations.length, 'locations');
            } else {
                console.error('Failed to load interview locations:', data.error);
            }
        } else {
            console.error('Failed to load interview locations:', data.error);
        }
    })
    .catch(error => {
        console.error('Error loading interview locations:', error);
    });
}

function submitLocationPreference(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');

    // Disable submit button
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Submitting...';

    fetch('/student/interview-location-preference', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success';
            alertDiv.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                <strong>Success!</strong> Your interview location preference has been submitted successfully.
            `;

            // Replace the form with success message
            form.parentNode.insertBefore(alertDiv, form);
            form.style.display = 'none';

            // Reload the page after 2 seconds to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Show error message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Error!</strong> ${data.error || 'Failed to submit location preference. Please try again.'}
            `;

            form.insertBefore(alertDiv, form.firstChild);

            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>Submit Preference';
        }
    })
    .catch(error => {
        console.error('Error submitting location preference:', error);

        // Show error message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger';
        alertDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Error!</strong> Failed to submit location preference. Please try again.
        `;

        form.insertBefore(alertDiv, form.firstChild);

        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>Submit Preference';
    });
}

// Initialize location selection when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Add form submit event listener if the form exists
    if (document.getElementById('locationPreferenceForm')) {
        document.getElementById('locationPreferenceForm').addEventListener('submit', submitLocationPreference);
    }
});

</script>
</body>
</html>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function loadIslands(atollSelectId, islandSelectId) {
    const atollId = $('#' + atollSelectId).val();
    const $islandSelect = $('#' + islandSelectId);
    $islandSelect.html('<option value="">Loading...</option>');

    if (atollId) {
        // Use Laravel's route helper inside Blade
        const url = "{{ route('student.get.islands', ':atoll_id') }}".replace(':atoll_id', atollId);

        $.get(url, function(data) {
            let options = '<option value="">Select Island</option>';
            data.forEach(island => {
                options += `<option value="${island.id}">${island.name}</option>`;
            });
            $islandSelect.html(options);
        });
    } else {
        $islandSelect.html('<option value="">Select Island</option>');
    }
}

$(document).ready(function() {
    $('#permanent_atoll').change(() => loadIslands('permanent_atoll', 'permanent_island'));
    $('#present_atoll').change(() => loadIslands('present_atoll', 'present_island'));
    $('#parent_atoll').change(() => loadIslands('parent_atoll', 'parent_island'));
});
</script>


