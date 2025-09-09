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
                    <div class="col-md-4">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name', $student->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name', $student->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Name in Dhivehi</label>
                        <input type="text" name="name_in_dhivehi" class="form-control" 
                               value="{{ old('name_in_dhivehi', $student->name_in_dhivehi) }}">
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
                        <input type="text" name="company" class="form-control" 
                               value="{{ old('company', $student->company) }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Platoon</label>
                        <input type="text" name="platoon" class="form-control" 
                               value="{{ old('platoon', $student->platoon) }}">
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
</body>
</html>

