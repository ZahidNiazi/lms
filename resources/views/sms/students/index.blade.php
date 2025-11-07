<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Students Management</title>
    
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

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .student-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }
        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff !important;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Students Management
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
                <h2 class="mb-1">Students Management</h2>
                <p class="text-muted mb-0">Manage student records and information</p>
            </div>
            {{-- <a href="{{ route('sms.students.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Student
            </a> --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-plus-circle me-2"></i>Add Student
            </button>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('sms.students.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, ID, Email..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->batch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Company</label>
                        <select name="company" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company }}" {{ request('company') == $company ? 'selected' : '' }}>
                                    {{ $company }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Platoon</label>
                        <select name="platoon" class="form-select">
                            <option value="">All Platoons</option>
                            @foreach($platoons as $platoon)
                                <option value="{{ $platoon }}" {{ request('platoon') == $platoon ? 'selected' : '' }}>
                                    {{ $platoon }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                        </select>
                    </div>
                    
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Students Table -->
        <div class="card">
            <div class="card-body">
                {{-- <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Batch</th>
                                <th>Company</th>
                                <th>Platoon</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td>
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo" class="student-photo">
                                        @else
                                            <div class="student-photo bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $student->student_id }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $student->full_name }}</strong>
                                            @if($student->name_in_dhivehi)
                                                <br><small class="text-muted">{{ $student->name_in_dhivehi }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if($student->batch)
                                            <span class="badge bg-info">{{ $student->batch->batch_name }}</span>
                                        @else
                                            <span class="text-muted">No Batch</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->company ?? 'N/A' }}</td>
                                    <td>{{ $student->platoon ?? 'N/A' }}</td>
                                    <td>
                                        @if($student->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sms.students.show', $student->id) }}" class="btn btn-outline-primary btn-action" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('sms.students.edit', $student->id) }}" class="btn btn-outline-warning btn-action" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-action" title="Delete" onclick="deleteStudent({{ $student->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people display-4 d-block mb-3"></i>
                                            <h5>No students found</h5>
                                            <p>Start by adding your first student or adjust your search filters.</p>
                                            <a href="{{ route('sms.students.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Add Student
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> --}}
                <div class="row">
                    @forelse($students as $student)
                        <div class="col-xl-4 col-lg-6 mb-4">
                            <div class="student-card border rounded-3 p-3 shadow-sm h-100">

                                {{-- Header --}}
                                <div class="student-head mb-3 d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- @if($student->photo && file_exists(public_path($student->photo)))
                                            <img src="{{ asset($student->photo) }}"
                                                alt="{{ $student->full_name }}"
                                                class="rounded-circle object-cover"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                        @else --}}
                                        @if($student->photo)
                                            <img src="{{ asset('storage/uploads/students/' . $student->photo) }}"
                                                alt="{{ $student->full_name }}"
                                                class="rounded-circle object-cover"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div class="avatar bg-primary text-white rounded-circle d-flex justify-content-center align-items-center"
                                                style="width: 45px; height: 45px; font-weight: 600; font-size: 18px;">
                                                {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <h5 class="mb-0 fw-bold">{{ $student->full_name }}</h5>
                                            <small class="text-muted">
                                                ID: {{ $student->student_id }}
                                                @if($student->created_at)
                                                    • Applied {{ $student->created_at->diffForHumans() }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    <span class="status-badge {{ $student->is_active ? 'bg-success text-white' : 'bg-secondary text-white' }} px-2 py-1 rounded">
                                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <hr>

                                {{-- Body Info --}}
                                <div class="student-info mb-3">
                                    <p class="mb-1"><strong>Email:</strong> {{ $student->email }}</p>
                                    <p class="mb-1"><strong>Batch:</strong>
                                        @if($student->batch)
                                            <span class="badge bg-info">{{ $student->batch->batch_name }}</span>
                                        @else
                                            <span class="text-muted">No Batch</span>
                                        @endif
                                    </p>
                                    <p class="mb-1"><strong>Company:</strong> {{ $student->company->name ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Platoon:</strong> {{ $student->platoon->name ?? 'N/A' }}</p>
                                    {{-- <p class="mb-1"><strong>Status:</strong>
                                        <span class="status-badge {{ $student->is_active ? 'bg-success' : 'bg-secondary' }} px-2 py-1 rounded">
                                            {{ $student->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p> --}}
                                </div>

                                {{-- Footer --}}
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Applied: {{ optional($student->created_at)->format('d/m/Y') }}
                                    </small>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('sms.students.show', $student->id) }}" class="btn btn-outline-primary btn-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('sms.students.edit', $student->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" title="Delete"
                                                onclick="deleteStudent({{ $student->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people display-4 d-block mb-3"></i>
                                <h5>No students found</h5>
                                <p>Start by adding your first student or adjust your search filters.</p>
                                <a href="{{ route('sms.students.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Add Student
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($students->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Add student Modal --}}
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
            <form action="{{ route('sms.students.storeProfile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Student Application Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="studentTab" role="tablist">
                    <li class="nav-item" role="presentation">
                    <button class="nav-link active text-primary" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">Personal</button>
                    </li>
                    <li class="nav-item" role="presentation">
                    <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab">Address</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="parent-tab" data-bs-toggle="tab" data-bs-target="#parent" type="button" role="tab">Parent Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab">Medical Records</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab">Academic</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="observation-tab" data-bs-toggle="tab" data-bs-target="#observation" type="button" role="tab">Observation</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="studentTabContent">
                    <!-- Personal Info -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                        </div>
                         <div class="col-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                        <small class="text-muted">Age must be between 16 to 28 years</small>
                        </div>
                        <div class="col-6">
                        <label class="form-label">National ID</label>
                        <input type="text" name="nid" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Mobile No</label>
                        <input type="text" name="mobile_no" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control" required>
                        </div>
                       
                    </div>
                    </div>

                    <!-- Address -->
                    <div class="tab-pane fade" id="address" role="tabpanel">
                    <h6 class="fw-bold">Permanent Address</h6>
                    <div class="row g-3">
                         <div class="col-6">
                        <label class="form-label">Atoll</label>
                        <select name="permanent_atoll" id="permanent_atoll" class="form-select" required>
                            <option value="">Select Atoll</option>
                            @foreach($atolls as $atoll)
                            <option value="{{ $atoll->id }}">{{ $atoll->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Island</label>
                        <select name="permanent_island" id="permanent_island" class="form-select" required>
                            <option value="">Select Island</option>
                        </select>
                        </div>
                        <div class="col-6">
                        <label class="form-label">District</label>
                        <input type="text" name="permanent_district" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="permanent_address" class="form-control" required>
                        </div>
                    </div>

                    <hr class="my-3">

                    <h6 class="fw-bold">Present Address</h6>
                    <div class="row g-3">
                        <div class="col-6">
                        <label class="form-label">Atoll</label>
                        <select name="present_atoll" id="present_atoll" class="form-select" required>
                            <option value="">Select Atoll</option>
                            @foreach($atolls as $atoll)
                            <option value="{{ $atoll->id }}">{{ $atoll->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Island</label>
                        <select name="present_island" id="present_island" class="form-select" required>
                            <option value="">Select Island</option>
                        </select>
                        </div>
                        <div class="col-6">
                        <label class="form-label">District</label>
                        <input type="text" name="present_district" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="present_address" class="form-control" required>
                        </div>
                    </div>
                    </div>

                    <!-- Parent Info -->
                    <div class="tab-pane fade" id="parent" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="parent_name" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Relation</label>
                        <input type="text" name="parent_relation" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Atoll</label>
                        <select name="parent_atoll" id="parent_atoll" class="form-select" required>
                            <option value="">Select Atoll</option>
                            @foreach($atolls as $atoll)
                            <option value="{{ $atoll->id }}">{{ $atoll->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Island</label>
                        <select name="parent_island" id="parent_island" class="form-select" required>
                            <option value="">Select Island</option>
                        </select>
                        </div>

                        <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="parent_address" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Mobile No</label>
                        <input type="text" name="parent_mobile_no" class="form-control" required>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="parent_email" class="form-control">
                        </div>
                    </div>
                    </div>

                    <div class="tab-pane fade" id="medical" role="tabpanel">
                        <h6 class="fw-bold">Add Medical Record</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Medical Condition</label>
                                <input type="text" name="medical_condition" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Medical Severity Level</label>
                                <select name="medical_severity_level" class="form-control">
                                    <option value="">-- Select Severity Level --</option>
                                    <option value="mild">Mild</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="severe">Severe</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">Medical Notes</label>
                                <textarea name="medical_notes" class="form-control" rows="3" placeholder="Enter any additional medical details..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="academic" role="tabpanel">
                            <h6 class="fw-bold">Add Academic Record</h6>
                            <div class="row g-3">
                                <!-- Document Type -->
                                <div class="col-12">
                                    <label class="form-label">Document Type</label>
                                    <select name="document_type" class="form-control">
                                        <option value="">-- Select Document Type --</option>
                                        <option value="certificate">Certificate</option>
                                        <option value="diploma">Diploma</option>
                                        <option value="bachelors">Bachelors</option>
                                        <option value="masters">Masters</option>
                                    </select>
                                </div>

                                <!-- Institution / Organization -->
                                <div class="col-12">
                                    <label class="form-label">Institution / Organization</label>
                                    <input type="text" name="institution" class="form-control" placeholder="Enter institution or organization name">
                                </div>

                                <!-- Start Date -->
                                <div class="col-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>

                                <!-- End Date -->
                                <div class="col-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>

                                <!-- Result / Grade -->
                                <div class="col-12">
                                    <label class="form-label">Result / Grade</label>
                                    <input type="text" name="result" class="form-control" placeholder="Enter grade or result">
                                </div>
                            </div>
                    </div>
                    <div class="tab-pane fade" id="observation" role="tabpanel">
                        <h6 class="fw-bold">Add New Observation</h6>
                        <!-- Observation Type -->
                        <div class="col-12">
                            <label class="form-label">Observation Type</label>
                            <select name="observation_type" class="form-control">
                                <option value="">-- Select Observation Type --</option>
                                <option value="behavioral">Behavioral</option>
                                <option value="academic">Academic</option>
                                <option value="medical">Medical</option>
                                <option value="attendance">Attendance</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Severity Level -->
                        <div class="col-12">
                            <label class="form-label">Severity Level</label>
                            <select name="severity_level" class="form-control">
                                <option value="">-- Select Severity Level --</option>
                                <option value="mild">Mild</option>
                                <option value="moderate">Moderate</option>
                                <option value="severe">Severe</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>

                        <!-- Observation Notes -->
                        <div class="col-12">
                            <label class="form-label">Observation Notes</label>
                            <textarea name="observation_notes" class="form-control" rows="3" placeholder="Enter detailed notes about the observation..."></textarea>
                        </div>
                    </div>
                    
                </div>
                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Student</button>
                </div>
            </form>
            </div>
        </div>
    </div>


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function deleteStudent(studentId) {
            if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('sms.students.index') }}/${studentId}`;
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add method override
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

        function loadIslands(atollSelector, islandSelector) {
            var atollId = $(atollSelector).val();
            var $islandSelect = $(islandSelector);
            $islandSelect.empty().append('<option value="">Select Island</option>');

            if (atollId) {
            const url = "{{ route('sms.get.islands', ':atoll_id') }}".replace(':atoll_id', atollId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                if (response.length > 0) {
                    response.forEach(function (island) {
                    $islandSelect.append('<option value="' + island.id + '">' + island.name + '</option>');
                    });
                }
                },
                error: function (xhr) {
                console.error('Error fetching islands:', xhr.responseText);
                }
            });
            }
        }

        // Bind change events for all 3 address groups
        $('#permanent_atoll').on('change', function () {
            loadIslands('#permanent_atoll', '#permanent_island');
        });

        $('#present_atoll').on('change', function () {
            loadIslands('#present_atoll', '#present_island');
        });

        $('#parent_atoll').on('change', function () {
            loadIslands('#parent_atoll', '#parent_island');
        });

        });
    </script>


</body>
</html>