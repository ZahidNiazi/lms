<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Application Details</title>
    
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

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background: var(--primary-blue);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #3d6bff;
            transform: translateY(-1px);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-pending_review { background: #e3f2fd; color: #1976d2; }
        .status-document_review { background: #fff3e0; color: #f57c00; }
        .status-approved { background: #e8f5e8; color: #2e7d32; }
        .status-rejected { background: #ffebee; color: #c62828; }
        .status-interview_scheduled { background: #f3e5f5; color: #7b1fa2; }
        .status-selected { background: #e8f5e8; color: #2e7d32; }
        .status-batch_assigned { background: #e1f5fe; color: #0277bd; }

        .document-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }

        .document-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .document-verified {
            border-left: 4px solid var(--success-green);
        }

        .document-issue {
            border-left: 4px solid var(--danger-red);
        }

        .document-missing {
            border-left: 4px solid var(--warning-yellow);
        }

        .action-buttons {
            position: sticky;
            top: 20px;
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.applications.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Application Details: {{ $application->application_number }}
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('job-portal.dashboard') }}">
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
        <div class="row">
            <div class="col-md-8">
                <!-- Student Information -->
                <div class="info-card">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-8">
                            <h3 class="mb-2">{{ $application->student->name }}</h3>
                            <p class="text-muted mb-0">{{ $application->application_number }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="status-badge status-{{ $application->status }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong><i class="bi bi-envelope me-2"></i>Email:</strong>
                                {{ $application->student->email }}
                            </div>
                            <div class="mb-3">
                                <strong><i class="bi bi-telephone me-2"></i>Phone:</strong>
                                {{ $application->student->profile->mobile_no ?? 'N/A' }}
                            </div>
                            <div class="mb-3">
                                <strong><i class="bi bi-calendar me-2"></i>Application Date:</strong>
                                {{ $application->created_at->format('M d, Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong><i class="bi bi-person-badge me-2"></i>NID:</strong>
                                {{ $application->student->profile->nid ?? 'N/A' }}
                            </div>
                            <div class="mb-3">
                                <strong><i class="bi bi-calendar-event me-2"></i>Date of Birth:</strong>
                                {{ $application->student->profile->dob ? \Carbon\Carbon::parse($application->student->profile->dob)->format('M d, Y') : 'N/A' }}
                            </div>
                            <div class="mb-3">
                                <strong><i class="bi bi-clock me-2"></i>Age:</strong>
                                {{ $application->student->profile->dob ? \Carbon\Carbon::parse($application->student->profile->dob)->age . ' years' : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    @if($application->student->addresses->count() > 0)
                        <h5 class="mt-4 mb-3">Address Information</h5>
                        @foreach($application->student->addresses as $address)
                            <div class="mb-3">
                                <strong>{{ ucfirst($address->type) }} Address:</strong>
                                {{ $address->address }}, {{ $address->island }}, {{ $address->atoll }}
                            </div>
                        @endforeach
                    @endif

                    @if($application->student->parentDetail)
                        <h5 class="mt-4 mb-3">Parent Information</h5>
                        <div class="mb-3">
                            <strong>Name:</strong> {{ $application->student->parentDetail->name }}<br>
                            <strong>Relation:</strong> {{ $application->student->parentDetail->relation }}<br>
                            <strong>Phone:</strong> {{ $application->student->parentDetail->mobile_no }}<br>
                            <strong>Email:</strong> {{ $application->student->parentDetail->email ?? 'N/A' }}
                        </div>
                    @endif

                    @if($application->preferred_interview_location_id)
                        <h5 class="mt-4 mb-3">📍 Interview Location Preference</h5>
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-8">
                                    <strong>Preferred Location:</strong> 
                                    @if($application->preferredInterviewLocation)
                                        {{ $application->preferredInterviewLocation->name }}
                                        <br><small class="text-muted">{{ $application->preferredInterviewLocation->getFullAddress() }}</small>
                                    @else
                                        Location ID: {{ $application->preferred_interview_location_id }}
                                    @endif
                                    @if($application->location_preference_reason)
                                        <br><strong>Reason:</strong> <em>{{ $application->location_preference_reason }}</em>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    @if($application->location_preference_submitted_at)
                                        <small class="text-muted">
                                            Submitted: {{ $application->location_preference_submitted_at->format('M d, Y h:i A') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Document Verification -->
                <div class="info-card">
                    <h5 class="mb-4">
                        <i class="bi bi-file-earmark-check me-2"></i>Document Verification
                    </h5>

                    @if($application->student->documents->count() > 0)
                        @foreach($application->student->documents as $document)
                            <div class="document-item {{ $document->verified ? 'document-verified' : ($document->has_issues ? 'document-issue' : '') }}">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ ucfirst(str_replace('_', ' ', $document->type)) }}</h6>
                                        <small class="text-muted">
                                            @if($document->school_name)
                                                School: {{ $document->school_name }}<br>
                                            @endif
                                            @if($document->year)
                                                Year: {{ $document->year }}<br>
                                            @endif
                                            @if($document->report_number)
                                                Report No: {{ $document->report_number }}<br>
                                            @endif
                                            @if($document->subjects)
                                                Subjects: {{ $document->subjects }}<br>
                                            @endif
                                            @if($document->result)
                                                Result: {{ $document->result }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="col-md-3">
                                        @if($document->verified)
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($document->has_issues)
                                            <span class="badge bg-danger">Issues Found</span>
                                        @else
                                            <span class="badge bg-warning">Pending Review</span>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <a href="{{ route('job-portal.documents.download', $document) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i> View
                                        </a>
                                    </div>
                                </div>
                                @if($document->verification_notes)
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <strong>Notes:</strong> {{ $document->verification_notes }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No documents uploaded</h5>
                        </div>
                    @endif
                </div>

                <!-- Application Reviews -->
                @if($application->reviews->count() > 0)
                    <div class="info-card">
                        <h5 class="mb-4">
                            <i class="bi bi-clipboard-check me-2"></i>Review History
                        </h5>
                        
                        @foreach($application->reviews as $review)
                            <div class="border-start border-3 border-primary ps-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ ucfirst(str_replace('_', ' ', $review->review_type)) }}</h6>
                                        <p class="mb-1">{{ $review->comments }}</p>
                                        <small class="text-muted">
                                            Reviewed by: {{ $review->reviewer->name ?? 'System' }} on {{ $review->reviewed_at->format('M d, Y H:i') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $review->status === 'approved' ? 'success' : ($review->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <h6 class="mb-3">Quick Actions</h6>
                    
                    <!-- Document Review -->
                    <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        <i class="bi bi-clipboard-check me-2"></i>Review Application
                    </button>

                    <!-- Status Update -->
                    <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="bi bi-arrow-repeat me-2"></i>Update Status
                    </button>

                    <!-- Send Message -->
                    <a href="{{ route('job-portal.communications.create', ['application_id' => $application->id]) }}" class="btn btn-outline-info w-100 mb-2">
                        <i class="bi bi-send me-2"></i>Send Message
                    </a>

                    <!-- Schedule Interview -->
                    @if(in_array($application->status, ['approved', 'document_review']))
                        <button class="btn btn-outline-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#interviewModal">
                            <i class="bi bi-calendar-event me-2"></i>Schedule Interview
                        </button>
                    @endif

                    <!-- Record Interview Result -->
                    @if($application->status === 'interview_scheduled')
                        <button class="btn btn-outline-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#resultModal">
                            <i class="bi bi-clipboard-data me-2"></i>Record Result
                        </button>
                    @endif

                    <!-- Assign to Batch -->
                    @if($application->status === 'selected')
                        <button class="btn btn-outline-info w-100 mb-2" data-bs-toggle="modal" data-bs-target="#batchModal">
                            <i class="bi bi-people me-2"></i>Assign to Batch
                        </button>
                    @endif

                    <hr>

                    <!-- Application Statistics -->
                    <h6 class="mb-3">Application Info</h6>
                    <div class="mb-2">
                        <small class="text-muted">Submitted:</small><br>
                        <strong>{{ $application->created_at->format('M d, Y H:i') }}</strong>
                    </div>
                    @if($application->reviewed_at)
                        <div class="mb-2">
                            <small class="text-muted">Last Reviewed:</small><br>
                            <strong>{{ $application->reviewed_at->format('M d, Y H:i') }}</strong>
                        </div>
                    @endif
                    @if($application->remarks)
                        <div class="mb-2">
                            <small class="text-muted">Remarks:</small><br>
                            <strong>{{ $application->remarks }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('job-portal.applications.review', $application->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="review_type" class="form-label">Review Type</label>
                            <select class="form-select" id="review_type" name="review_type" required>
                                <option value="">Select Review Type</option>
                                <option value="document_verification">Document Verification</option>
                                <option value="basic_criteria_check">Basic Criteria Check</option>
                                <option value="final_approval">Final Approval</option>
                                <option value="rejection">Rejection</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="needs_resubmission">Needs Resubmission</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea class="form-control" id="comments" name="comments" rows="4" placeholder="Enter review comments..."></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requires_resubmission" name="requires_resubmission" value="1">
                                <label class="form-check-label" for="requires_resubmission">
                                    Requires Resubmission
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Application Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('job-portal.applications.status', $application->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">New Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending_review" {{ $application->status === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                                <option value="document_review" {{ $application->status === 'document_review' ? 'selected' : '' }}>Document Review</option>
                                <option value="approved" {{ $application->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="interview_scheduled" {{ $application->status === 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                                <option value="interview_completed" {{ $application->status === 'interview_completed' ? 'selected' : '' }}>Interview Completed</option>
                                <option value="selected" {{ $application->status === 'selected' ? 'selected' : '' }}>Selected</option>
                                <option value="batch_assigned" {{ $application->status === 'batch_assigned' ? 'selected' : '' }}>Batch Assigned</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks...">{{ $application->remarks }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason (if applicable)</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Enter rejection reason...">{{ $application->rejection_reason }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Schedule Interview Modal -->
    <div class="modal fade" id="interviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Interview - {{ $application->student->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('job-portal.applications.schedule-interview.store', $application->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="interview_date" class="form-label">Interview Date</label>
                                    <input type="date" class="form-control" id="interview_date" name="interview_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="interview_time" class="form-label">Interview Time</label>
                                    <input type="time" class="form-control" id="interview_time" name="interview_time" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="interview_type" class="form-label">Interview Type</label>
                                    <select class="form-select" id="interview_type" name="interview_type" required>
                                        <option value="">Select Interview Type</option>
                                        <option value="medical">Medical</option>
                                        <option value="fitness_swimming">Fitness Swimming</option>
                                        <option value="fitness_run">Fitness Run</option>
                                        <option value="aptitude_test">Aptitude Test</option>
                                        <option value="physical_interview">Physical Interview</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location_id" class="form-label">Interview Location</label>
                                    <select class="form-select" id="location_id" name="location_id" required>
                                        <option value="">Select Location</option>
                                        @if(isset($interviewLocations))
                                            @foreach($interviewLocations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="1">Main Office</option>
                                            <option value="2">Training Center</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="instructions" class="form-label">Instructions</label>
                            <textarea class="form-control" id="instructions" name="instructions" rows="3" placeholder="Enter interview instructions..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="dress_code" class="form-label">Dress Code</label>
                            <textarea class="form-control" id="dress_code" name="dress_code" rows="2" placeholder="Enter dress code requirements..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="travel_arrangements" class="form-label">Travel Arrangements</label>
                            <textarea class="form-control" id="travel_arrangements" name="travel_arrangements" rows="2" placeholder="Enter travel arrangement details..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="accommodation_arrangements" class="form-label">Accommodation Arrangements</label>
                            <textarea class="form-control" id="accommodation_arrangements" name="accommodation_arrangements" rows="2" placeholder="Enter accommodation details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Schedule Interview</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Record Interview Result Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Interview Result - {{ $application->student->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('job-portal.applications.interview-result', $application->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medical_result" class="form-label">Medical Result</label>
                                    <select class="form-select" id="medical_result" name="medical_result">
                                        <option value="">Select Result</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fitness_result" class="form-label">Fitness Result</label>
                                    <select class="form-select" id="fitness_result" name="fitness_result">
                                        <option value="">Select Result</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="swimming_result" class="form-label">Swimming Result</label>
                                    <select class="form-select" id="swimming_result" name="swimming_result">
                                        <option value="">Select Result</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="running_result" class="form-label">Running Result</label>
                                    <select class="form-select" id="running_result" name="running_result">
                                        <option value="">Select Result</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="aptitude_result" class="form-label">Aptitude Test Result</label>
                                    <select class="form-select" id="aptitude_result" name="aptitude_result">
                                        <option value="">Select Result</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="aptitude_score" class="form-label">Aptitude Score (0-100)</label>
                                    <input type="number" class="form-control" id="aptitude_score" name="aptitude_score" min="0" max="100" placeholder="Enter score">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="physical_interview_result" class="form-label">Physical Interview Result</label>
                                    <select class="form-select" id="physical_interview_result" name="physical_interview_result">
                                        <option value="">Select Result</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="overall_result" class="form-label">Overall Result</label>
                                    <select class="form-select" id="overall_result" name="overall_result" required>
                                        <option value="">Select Overall Result</option>
                                        <option value="passed">Passed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea class="form-control" id="comments" name="comments" rows="4" placeholder="Enter detailed comments about the interview..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Record Result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign to Batch Modal -->
    <div class="modal fade" id="batchModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign to Training Batch - {{ $application->student->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('job-portal.applications.assign-batch', $application->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="batch_id" class="form-label">Select Training Batch</label>
                            <select class="form-select" id="batch_id" name="batch_id" required>
                                <option value="">Select Batch</option>
                                @if(isset($trainingBatches))
                                    @foreach($trainingBatches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->batch_name }} ({{ $batch->batch_code }}) - {{ $batch->start_date->format('M d, Y') }}</option>
                                    @endforeach
                                @else
                                    <option value="1">Batch 2024-01 (Jan 2024)</option>
                                    <option value="2">Batch 2024-02 (Feb 2024)</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="assignment_type" class="form-label">Assignment Type</label>
                            <select class="form-select" id="assignment_type" name="assignment_type" required>
                                <option value="">Select Type</option>
                                <option value="automatic">Automatic</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Assign to Batch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

