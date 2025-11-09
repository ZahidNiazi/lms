<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Edit Student</title>
    
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

        .form-section {
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
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.students.show', $student->id) }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Edit Student
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
                <h2 class="mb-1">Edit Student</h2>
                <p class="text-muted mb-0">Update information for {{ $student->full_name }}</p>
            </div>
            <div>
                <a href="{{ route('sms.students.show', $student->id) }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-eye me-2"></i>View
                </a>
                <a href="{{ route('sms.students.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Students
                </a>
            </div>
        </div>

        <form action="{{ route('sms.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-person me-2"></i>Basic Information
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name', $student->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name', $student->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Name in Dhivehi</label>
                        <input type="text" name="name_in_dhivehi" class="form-control" 
                               value="{{ old('name_in_dhivehi', $student->name_in_dhivehi) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Student ID <span class="text-danger">*</span></label>
                        <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" 
                               value="{{ old('student_id', $student->student_id) }}" required>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Rank</label>
                        <select name="rank" class="form-select">
                            <option value="">Select Rank</option>
                            <option value="Recruit" {{ old('rank', $student->rank) == 'Recruit' ? 'selected' : '' }}>Recruit</option>
                            <option value="Private" {{ old('rank', $student->rank) == 'Private' ? 'selected' : '' }}>Private</option>
                            <option value="Corporal" {{ old('rank', $student->rank) == 'Corporal' ? 'selected' : '' }}>Corporal</option>
                            <option value="Sergeant" {{ old('rank', $student->rank) == 'Sergeant' ? 'selected' : '' }}>Sergeant</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        @if($student->photo)
                            <small class="text-muted">Current: {{ basename($student->photo) }}</small>
                        @endif
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                </div>
                
                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <label class="form-label">Martial Status<span class="text-danger">*</span></label>
                        <select name="martial_status" id="martial_status" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select Martial Status</option>
                            <option value="Married" {{ old('martial_status', $student->martial_status) == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Unmarried" {{ old('martial_status', $student->martial_status) == 'Unmarried' ? 'selected' : '' }}>Unmarried</option>
                        </select>
                        @error('martial_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3" id="kids_field" style="display: none;">
                        <label class="form-label">Number of Kids</label>
                        <input type="number" name="kids" id="kids" class="form-control"
                            value="{{ old('kids', $student->kids ?? '') }}"
                            placeholder="Enter number of kids">
                    </div>
                    
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-telephone me-2"></i>Contact Information
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $student->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">National ID <span class="text-danger">*</span></label>
                        <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" 
                               value="{{ old('national_id', $student->national_id) }}" required>
                        @error('national_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" name="contact_no" class="form-control @error('contact_no') is-invalid @enderror" 
                               value="{{ old('contact_no', $student->contact_no) }}" required>
                        @error('contact_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Personal Details -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-calendar me-2"></i>Personal Details
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                               value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select Blood Group</option>
                            <option value="A+" {{ old('blood_group', $student->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group', $student->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group', $student->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group', $student->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group', $student->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group', $student->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_group', $student->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group', $student->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Service Duration (months)</label>
                        <input type="number" name="service_duration" class="form-control" 
                               value="{{ old('service_duration', $student->service_duration) }}" min="1" max="24">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Pay Amount</label>
                        <input type="number" name="pay_amount" class="form-control" 
                               value="{{ old('pay_amount', $student->pay_amount) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <!-- Address Information Section -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-geo-alt me-2"></i>Address Information
                </h5>
                
                <div class="row">
                    <!-- Permanent Address -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Permanent Address</h6>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="permanent_address_name" class="form-control" 
                                    value="{{ old('permanent_address_name', $student->permanent_address_name) }}" 
                                    placeholder="Enter permanent address">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Atoll</label>
                                <select name="permanent_atoll" id="permanent_atoll" class="form-select">
                                    <option value="">Select Atoll</option>
                                    @foreach($atolls as $atoll)
                                        <option value="{{ $atoll->id }}" 
                                            {{ old('permanent_atoll', $student->permanent_atoll_id) == $atoll->id ? 'selected' : '' }}>
                                            {{ $atoll->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Island</label>
                                <select name="permanent_island" id="permanent_island" class="form-select">
                                    <option value="">Select Island</option>
                                    @if($student->permanent_atoll_id)
                                        @foreach($islands->where('atoll_id', $student->permanent_atoll_id) as $island)
                                            <option value="{{ $island->id }}" 
                                                {{ old('permanent_island', $student->permanent_island_id) == $island->id ? 'selected' : '' }}>
                                                {{ $island->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">District</label>
                                <input type="text" name="permanent_district" class="form-control" 
                                    value="{{ old('permanent_district', $student->permanent_district) }}" 
                                    placeholder="Enter district">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Road Name</label>
                                <input type="text" name="permanent_road_name" class="form-control" 
                                    value="{{ old('permanent_road_name', $student->permanent_road_name) }}" 
                                    placeholder="Enter road name">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Present Address -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Present Address</h6>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="present_address_name" class="form-control" 
                                    value="{{ old('present_address_name', $student->present_address_name) }}" 
                                    placeholder="Enter present address">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Atoll</label>
                                <select name="present_atoll" id="present_atoll" class="form-select">
                                    <option value="">Select Atoll</option>
                                    @foreach($atolls as $atoll)
                                        <option value="{{ $atoll->id }}" 
                                            {{ old('present_atoll', $student->present_atoll_id) == $atoll->id ? 'selected' : '' }}>
                                            {{ $atoll->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Island</label>
                                <select name="present_island" id="present_island" class="form-select">
                                    <option value="">Select Island</option>
                                    @if($student->present_atoll_id)
                                        @foreach($islands->where('atoll_id', $student->present_atoll_id) as $island)
                                            <option value="{{ $island->id }}" 
                                                {{ old('present_island', $student->present_island_id) == $island->id ? 'selected' : '' }}>
                                                {{ $island->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">District</label>
                                <input type="text" name="present_district" class="form-control" 
                                    value="{{ old('present_district', $student->present_district) }}" 
                                    placeholder="Enter district">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Road Name</label>
                                <input type="text" name="present_road_name" class="form-control" 
                                    value="{{ old('present_road_name', $student->present_road_name) }}" 
                                    placeholder="Enter road name">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Information Section -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-people me-2"></i>Parent Information
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Parent Name</label>
                        <input type="text" name="parent_name" class="form-control" 
                            value="{{ old('parent_name', $student->parent_name) }}" 
                            placeholder="Enter parent name">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Relationship</label>
                        <select name="parent_relationship" class="form-select">
                            <option value="">Select Relationship</option>
                            <option value="Father" {{ old('parent_relationship', $student->parent_relationship) == 'Father' ? 'selected' : '' }}>Father</option>
                            <option value="Mother" {{ old('parent_relationship', $student->parent_relationship) == 'Mother' ? 'selected' : '' }}>Mother</option>
                            <option value="Guardian" {{ old('parent_relationship', $student->parent_relationship) == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Parent Email</label>
                        <input type="email" name="parent_email" class="form-control" 
                            value="{{ old('parent_email', $student->parent_email) }}" 
                            placeholder="parent@example.com">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Parent Contact</label>
                        <input type="text" name="parent_contact_no" class="form-control" 
                            value="{{ old('parent_contact_no', $student->parent_contact_no) }}" 
                            placeholder="Enter contact number">
                    </div>
                    
                    <div class="col-12">
                        <h6 class="text-muted mb-3 mt-3">Parent Address</h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Parent Address</label>
                        <textarea name="parent_address" class="form-control" rows="2" 
                                placeholder="Enter parent address">{{ old('parent_address', $student->parent_address) }}</textarea>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Atoll</label>
                        <select name="parent_atoll" id="parent_atoll" class="form-select">
                            <option value="">Select Atoll</option>
                            @foreach($atolls as $atoll)
                                <option value="{{ $atoll->id }}" 
                                    {{ old('parent_atoll', $student->parent_atoll_id) == $atoll->id ? 'selected' : '' }}>
                                    {{ $atoll->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Island</label>
                        <select name="parent_island" id="parent_island" class="form-select">
                            <option value="">Select Island</option>
                            @if($student->parent_atoll_id)
                                @foreach($islands->where('atoll_id', $student->parent_atoll_id) as $island)
                                    <option value="{{ $island->id }}" 
                                        {{ old('parent_island', $student->parent_island_id) == $island->id ? 'selected' : '' }}>
                                        {{ $island->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <!-- Training Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-mortarboard me-2"></i>Training Information
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">Select Batch</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ old('batch_id', $student->batch_id) == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->batch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Company</label>
                        <select name="company_id" class="form-control">
                            <option value="">Select Company</option>
                            @foreach($comapnies as $company)
                                <option value="{{ $company->id }}" 
                                    {{ $student->company_id == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Platoon</label>
                        <select name="platoon_id" class="form-control">
                            <option value="">Select Platoon</option>
                            @foreach($platoons as $platoon)
                                <option value="{{ $platoon->id }}" 
                                    {{ $student->platoon_id == $platoon->id ? 'selected' : '' }}>
                                    {{ $platoon->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-mortarboard me-2"></i>Medical Records
                </h5>
                @php
                    $medical = $student->medicalRecords->first();
                @endphp

                <div class="row g-3">
                    <!-- Medical Condition -->
                    <div class="col-12">
                        <label class="form-label">Medical Condition</label>
                        <input type="text" name="medical_condition" class="form-control"
                            value="{{ old('medical_condition', $medical?->medical_condition) }}">
                    </div>

                    <!-- Severity Level -->
                    <div class="col-12">
                        <label class="form-label">Medical Severity Level</label>
                        <select name="medical_severity_level" class="form-control">
                            <option value="">-- Select Severity Level --</option>
                            <option value="mild" {{ old('medical_severity_level', $medical?->medical_severity_level) == 'mild' ? 'selected' : '' }}>Mild</option>
                            <option value="moderate" {{ old('medical_severity_level', $medical?->medical_severity_level) == 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="severe" {{ old('medical_severity_level', $medical?->medical_severity_level) == 'severe' ? 'selected' : '' }}>Severe</option>
                            <option value="critical" {{ old('medical_severity_level', $medical?->medical_severity_level) == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>

                    <!-- Medical Notes -->
                    <div class="col-12 mt-3">
                        <label class="form-label">Medical Notes</label>
                        <textarea name="medical_notes" class="form-control" rows="3"
                                placeholder="Enter any additional medical details...">{{ old('medical_notes', $medical?->medical_notes) }}</textarea>
                    </div>
                </div>

            </div>
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-mortarboard me-2"></i>Academic
                </h5>
                @php
                    $record = $student->AcademiclRecords->first();
                    
                @endphp

                <div class="row g-3">
                    <!-- Document Type -->
                    <div class="col-12">
                        <label class="form-label">Document Type</label>
                        <select name="document_type" class="form-control">
                            <option value="">-- Select Document Type --</option>
                            <option value="certificate" {{ old('document_type', $record?->document_type) == 'certificate' ? 'selected' : '' }}>Certificate</option>
                            <option value="diploma" {{ old('document_type', $record?->document_type) == 'diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="bachelors" {{ old('document_type', $record?->document_type) == 'bachelors' ? 'selected' : '' }}>Bachelors</option>
                            <option value="masters" {{ old('document_type', $record?->document_type) == 'masters' ? 'selected' : '' }}>Masters</option>
                        </select>
                    </div>

                    <!-- Institution / Organization -->
                    <div class="col-12">
                        <label class="form-label">Institution / Organization</label>
                        <input type="text" name="institution" class="form-control"
                            value="{{ old('institution', $record?->institution) }}">
                    </div>

                    <!-- Start Date -->
                    <div class="col-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ old('start_date', $record?->start_date) }}">
                    </div>

                    <!-- End Date -->
                    <div class="col-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ old('end_date', $record?->end_date) }}">
                    </div>

                    <!-- Result / Grade -->
                    <div class="col-12">
                        <label class="form-label">Result / Grade</label>
                        <input type="text" name="result" class="form-control"
                            value="{{ old('result', $record?->result) }}">
                    </div>
                </div>


                
            </div>
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-mortarboard me-2"></i>Observation
                </h5>
                @php
                    $observation = $student->Observation->first(); // assuming one observation record per student
                @endphp

                <div class="row g-3">
                    <!-- Observation Type -->
                    <div class="col-12">
                        <label class="form-label">Observation Type</label>
                        <select name="observation_type" class="form-control">
                            <option value="">-- Select Observation Type --</option>
                            <option value="behavioral" {{ old('observation_type', $observation->observation_type ?? '') == 'behavioral' ? 'selected' : '' }}>Behavioral</option>
                            <option value="academic" {{ old('observation_type', $observation->observation_type ?? '') == 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="medical" {{ old('observation_type', $observation->observation_type ?? '') == 'medical' ? 'selected' : '' }}>Medical</option>
                            <option value="attendance" {{ old('observation_type', $observation->observation_type ?? '') == 'attendance' ? 'selected' : '' }}>Attendance</option>
                            <option value="other" {{ old('observation_type', $observation->observation_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Severity Level -->
                    <div class="col-12">
                        <label class="form-label">Severity Level</label>
                        <select name="severity_level" class="form-control">
                            <option value="">-- Select Severity Level --</option>
                            <option value="mild" {{ old('severity_level', $observation->severity_level ?? '') == 'mild' ? 'selected' : '' }}>Mild</option>
                            <option value="moderate" {{ old('severity_level', $observation->severity_level ?? '') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="severe" {{ old('severity_level', $observation->severity_level ?? '') == 'severe' ? 'selected' : '' }}>Severe</option>
                            <option value="critical" {{ old('severity_level', $observation->severity_level ?? '') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>

                    <!-- Observation Notes -->
                    <div class="col-12 mt-3">
                        <label class="form-label">Observation Notes</label>
                        <textarea name="observation_notes" class="form-control" rows="3" placeholder="Enter detailed notes about the observation...">{{ old('observation_notes', $observation->observation_notes ?? '') }}</textarea>
                    </div>
                </div>
                
            </div>

            <!-- Submit Buttons -->
            <div class="form-section">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('sms.students.show', $student->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Student
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const maritalSelect = document.getElementById('martial_status');
        const kidsField = document.getElementById('kids_field');
        const kidsInput = document.getElementById('kids');

        // Function to toggle kids field visibility
        function toggleKidsField() {
            if (maritalSelect.value === 'Married') {
                kidsField.style.display = 'block';
            } else {
                kidsField.style.display = 'none';
                kidsInput.value = ''; // clear if hidden
            }
        }

        // Initial check (for edit form)
        toggleKidsField();

        // Change event
        maritalSelect.addEventListener('change', toggleKidsField);
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        
        function loadIslands(atollSelector, islandSelector, selectedIslandId = null) {
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
                                var selected = selectedIslandId && island.id == selectedIslandId ? 'selected' : '';
                                $islandSelect.append('<option value="' + island.id + '" ' + selected + '>' + island.name + '</option>');
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error('Error fetching islands:', xhr.responseText);
                    }
                });
            }
        }

        // Load islands on page load if atoll is already selected
        @if($student->permanent_atoll)
            loadIslands('#permanent_atoll', '#permanent_island', {{ $student->permanent_island ?? 'null' }});
        @endif
        
        @if($student->present_atoll)
            loadIslands('#present_atoll', '#present_island', {{ $student->present_island ?? 'null' }});
        @endif
        
        @if($student->parent_atoll)
            loadIslands('#parent_atoll', '#parent_island', {{ $student->parent_island ?? 'null' }});
        @endif

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

