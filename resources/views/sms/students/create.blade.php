<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Create Student</title>
    
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
            <a class="navbar-brand" href="{{ route('sms.students.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Create Student
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
                <h2 class="mb-1">Create New Student</h2>
                <p class="text-muted mb-0">Add a new student to the system</p>
            </div>
            <a href="{{ route('sms.students.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Students
            </a>
        </div>

        <form action="{{ route('sms.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Basic Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-person me-2"></i>Basic Information
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Student ID <span class="text-danger">*</span></label>
                        <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" 
                               value="{{ old('student_id') }}" required>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Rank</label>
                        <select name="rank" class="form-select">
                            <option value="">Select Rank</option>
                            <option value="Recruit" {{ old('rank') == 'Recruit' ? 'selected' : '' }}>Recruit</option>
                            <option value="Private" {{ old('rank') == 'Private' ? 'selected' : '' }}>Private</option>
                            <option value="Corporal" {{ old('rank') == 'Corporal' ? 'selected' : '' }}>Corporal</option>
                            <option value="Sergeant" {{ old('rank') == 'Sergeant' ? 'selected' : '' }}>Sergeant</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Name in Dhivehi</label>
                        <input type="text" name="name_in_dhivehi" class="form-control" 
                               value="{{ old('name_in_dhivehi') }}">
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
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">National ID <span class="text-danger">*</span></label>
                        <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" 
                               value="{{ old('national_id') }}" required>
                        @error('national_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" name="contact_no" class="form-control @error('contact_no') is-invalid @enderror" 
                               value="{{ old('contact_no') }}" required>
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
                               value="{{ old('date_of_birth') }}" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select Blood Group</option>
                            <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Service Duration (months)</label>
                        <input type="number" name="service_duration" class="form-control" 
                               value="{{ old('service_duration', 12) }}" min="1" max="24">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Pay Amount</label>
                        <input type="number" name="pay_amount" class="form-control" 
                               value="{{ old('pay_amount', 5000) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-geo-alt me-2"></i>Address Information
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Permanent Address</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" name="permanent_address_name" class="form-control" 
                                       placeholder="Address Name" value="{{ old('permanent_address_name') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="permanent_atoll" class="form-control" 
                                       placeholder="Atoll" value="{{ old('permanent_atoll') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="permanent_island" class="form-control" 
                                       placeholder="Island" value="{{ old('permanent_island') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="permanent_district" class="form-control" 
                                       placeholder="District" value="{{ old('permanent_district') }}">
                            </div>
                            <div class="col-12">
                                <input type="text" name="permanent_road_name" class="form-control" 
                                       placeholder="Road Name" value="{{ old('permanent_road_name') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted">Present Address</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" name="present_address_name" class="form-control" 
                                       placeholder="Address Name" value="{{ old('present_address_name') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="present_atoll" class="form-control" 
                                       placeholder="Atoll" value="{{ old('present_atoll') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="present_island" class="form-control" 
                                       placeholder="Island" value="{{ old('present_island') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="present_district" class="form-control" 
                                       placeholder="District" value="{{ old('present_district') }}">
                            </div>
                            <div class="col-12">
                                <input type="text" name="present_road_name" class="form-control" 
                                       placeholder="Road Name" value="{{ old('present_road_name') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="bi bi-people me-2"></i>Parent Information
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Parent Name</label>
                        <input type="text" name="parent_name" class="form-control" 
                               value="{{ old('parent_name') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Relationship</label>
                        <select name="parent_relationship" class="form-select">
                            <option value="">Select Relationship</option>
                            <option value="Father" {{ old('parent_relationship') == 'Father' ? 'selected' : '' }}>Father</option>
                            <option value="Mother" {{ old('parent_relationship') == 'Mother' ? 'selected' : '' }}>Mother</option>
                            <option value="Guardian" {{ old('parent_relationship') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Parent Email</label>
                        <input type="email" name="parent_email" class="form-control" 
                               value="{{ old('parent_email') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Parent Contact</label>
                        <input type="text" name="parent_contact_no" class="form-control" 
                               value="{{ old('parent_contact_no') }}">
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Parent Address</label>
                        <textarea name="parent_address" class="form-control" rows="2">{{ old('parent_address') }}</textarea>
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
                                <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->batch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Company</label>
                        <input type="text" name="company" class="form-control" 
                               value="{{ old('company') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Platoon</label>
                        <input type="text" name="platoon" class="form-control" 
                               value="{{ old('platoon') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Date of Joining</label>
                        <input type="date" name="date_of_joining" class="form-control" 
                               value="{{ old('date_of_joining') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Application Date</label>
                        <input type="date" name="application_date" class="form-control" 
                               value="{{ old('application_date', date('Y-m-d')) }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Applicant Number</label>
                        <input type="text" name="applicant_number" class="form-control" 
                               value="{{ old('applicant_number') }}">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="form-section">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('sms.students.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Student
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

