<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Interview - Job Portal</title>
    
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

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .student-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-primary:hover {
            background-color: #3d6bff;
            border-color: #3d6bff;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-briefcase me-2"></i>Job Portal - Schedule Interview
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('job-portal.applications.index') }}">
                    <i class="bi bi-arrow-left me-1"></i>Back to Applications
                </a>
                <a class="nav-link" href="{{ route('job-portal.dashboard') }}">
                    <i class="bi bi-house me-1"></i>Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0">Schedule Interview</h1>
                <p class="text-muted">Schedule interview for student application</p>
            </div>
        </div>

        <!-- Student Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="student-info">
                    <h5 class="mb-3">
                        <i class="bi bi-person me-2"></i>Student Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $application->student->profile->first_name ?? '' }} {{ $application->student->profile->last_name ?? '' }}</p>
                            <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
                            <p><strong>Email:</strong> {{ $application->student->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mobile:</strong> {{ $application->student->profile->mobile_no ?? 'N/A' }}</p>
                            <p><strong>NID:</strong> {{ $application->student->profile->nid ?? 'N/A' }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-warning">{{ ucwords(str_replace('_', ' ', $application->status)) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interview Scheduling Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="form-card">
                    <h5 class="mb-4">
                        <i class="bi bi-calendar-event me-2"></i>Interview Details
                    </h5>

                    <form method="POST" action="{{ route('job-portal.applications.schedule-interview.store', $application->id) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="interview_date" class="form-label">Interview Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('interview_date') is-invalid @enderror" 
                                       id="interview_date" name="interview_date" 
                                       value="{{ old('interview_date') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('interview_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="interview_time" class="form-label">Interview Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('interview_time') is-invalid @enderror" 
                                       id="interview_time" name="interview_time" 
                                       value="{{ old('interview_time') }}" required>
                                @error('interview_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location_id" class="form-label">Interview Location <span class="text-danger">*</span></label>
                                <select class="form-select @error('location_id') is-invalid @enderror" 
                                        id="location_id" name="location_id" required>
                                    <option value="">Select Location</option>
                                    @foreach($interviewLocations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }} - {{ $location->address }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="interview_type" class="form-label">Interview Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('interview_type') is-invalid @enderror" 
                                        id="interview_type" name="interview_type" required>
                                    <option value="">Select Type</option>
                                    @foreach($interviewTypes as $type)
                                        <option value="{{ $type }}" {{ old('interview_type') == $type ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('interview_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="instructions" class="form-label">Instructions</label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                      id="instructions" name="instructions" rows="3" 
                                      placeholder="Special instructions for the interview...">{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dress_code" class="form-label">Dress Code</label>
                            <input type="text" class="form-control @error('dress_code') is-invalid @enderror" 
                                   id="dress_code" name="dress_code" 
                                   value="{{ old('dress_code', 'Formal attire') }}" 
                                   placeholder="e.g., Formal attire, Business casual">
                            @error('dress_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="travel_arrangements" class="form-label">Travel Arrangements</label>
                            <textarea class="form-control @error('travel_arrangements') is-invalid @enderror" 
                                      id="travel_arrangements" name="travel_arrangements" rows="2" 
                                      placeholder="Travel arrangements and instructions...">{{ old('travel_arrangements') }}</textarea>
                            @error('travel_arrangements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="accommodation_arrangements" class="form-label">Accommodation Arrangements</label>
                            <textarea class="form-control @error('accommodation_arrangements') is-invalid @enderror" 
                                      id="accommodation_arrangements" name="accommodation_arrangements" rows="2" 
                                      placeholder="Accommodation arrangements and instructions...">{{ old('accommodation_arrangements') }}</textarea>
                            @error('accommodation_arrangements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('job-portal.applications.show', $application->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-calendar-check me-1"></i>Schedule Interview
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Interview Process Information -->
            <div class="col-lg-4">
                <div class="form-card">
                    <h5 class="mb-4">
                        <i class="bi bi-info-circle me-2"></i>Interview Process
                    </h5>
                    
                    <div class="mb-3">
                        <h6>Interview Stages:</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success me-2"></i>Medical Examination</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Fitness Test (Swimming)</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Fitness Test (Running)</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Aptitude Test</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Physical Interview</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6>Important Notes:</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Student will receive email notification</li>
                            <li>• Student can acknowledge the interview</li>
                            <li>• Absent students get second attempt</li>
                            <li>• Results are not visible to students</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-lightbulb me-2"></i>
                        <strong>Tip:</strong> Make sure to provide clear instructions and all necessary details for the interview.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
