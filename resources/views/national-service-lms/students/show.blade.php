@extends('layouts.admin')

@section('page-title')
    Student Details - {{ $student->name }}
@endsection

@push('css-page')
    <style>
        .detail-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        .section-title {
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f1f1;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #2c3e50;
        }
        .info-value {
            color: #34495e;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .document-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            margin: 5px;
            display: inline-block;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger { background: #dc3545; color: white; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Student Details</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('national-service-lms.dashboard') }}">National Service LMS</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('national-service-lms.students') }}">Students</a></li>
                    <li class="breadcrumb-item active">{{ $student->name }}</li>
                </ol>
            </div>
        </div>
    </div>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="detail-section">
                <h5 class="section-title">Quick Actions</h5>
                <div class="d-flex flex-wrap">
                    <a href="{{ route('national-service-lms.students') }}" class="action-btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                    </a>
                    @if($student->application_stage === 'approved_for_interview')
                        <button type="button" class="action-btn btn-success p-1" data-bs-toggle="modal" data-bs-target="#scheduleInterviewModal">
                            <i class="fas fa-calendar-plus me-2"></i>
                        </button>
                    @endif
                    @if($student->status === 'approved')
                        <button type="button" class="action-btn btn-warning p-1" data-bs-toggle="modal" data-bs-target="#createDeploymentModal">
                            <i class="fas fa-user-tie me-2"></i>
                        </button>
                    @endif
                    <button type="button" class="action-btn btn-warning p-1" data-bs-toggle="modal" data-bs-target="#updateStageModal">
                        <i class="fas fa-edit me-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="detail-section">
                <h5 class="section-title">Basic Information</h5>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $student->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $student->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Application Date:</span>
                    <span class="info-value">{{ $student->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Application Stage:</span>
                    <span class="status-badge status-{{ $student->application_stage }}">
                        {{ str_replace('_', ' ', ucfirst($student->application_stage)) }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-badge status-{{ $student->status }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Profile Completed:</span>
                    <span class="info-value">{{ $student->profile_completed ? 'Yes' : 'No' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Reachable:</span>
                    <span class="info-value">{{ $student->is_reachable ? 'Yes' : 'No' }}</span>
                </div>
                @if($student->is_under_age_18)
                <div class="info-row">
                    <span class="info-label">Age Status:</span>
                    <span class="badge bg-warning">Under Age 18</span>
                </div>
                @endif
                @if($student->rejection_reason)
                <div class="info-row">
                    <span class="info-label">Rejection Reason:</span>
                    <span class="info-value">{{ $student->rejection_reason }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-section">
                <h5 class="section-title">Profile Information</h5>
                @if($student->profile)
                    <div class="info-row">
                        <span class="info-label">First Name:</span>
                        <span class="info-value">{{ $student->profile->first_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last Name:</span>
                        <span class="info-value">{{ $student->profile->last_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">NID:</span>
                        <span class="info-value">{{ $student->profile->nid }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mobile No:</span>
                        <span class="info-value">{{ $student->profile->mobile_no }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date of Birth:</span>
                        <span class="info-value"> @if(!empty($student->profile->dob))
                            {{ \Carbon\Carbon::parse($student->profile->dob ?? '')->format('d M, Y') }}
                        @else
                            N/A
                        @endif
                    </span>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Profile information not completed yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Addresses -->
    @if($student->addresses->count() > 0)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="detail-section">
                <h5 class="section-title">Permanent Address</h5>
                @php $permanentAddress = $student->addresses->where('type', 'permanent')->first(); @endphp
                @if($permanentAddress)

                    <div class="info-row">
                        <span class="info-label">Atoll:</span>
                        <span class="info-value">{{ $permanentAddress->atoll }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Island:</span>
                        <span class="info-value">{{ $permanentAddress->island }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">District:</span>
                        <span class="info-value">{{ $permanentAddress->district }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{ $permanentAddress->address }}</span>
                    </div>
                    {{-- <div class="info-row">
                        <span class="info-label">Postal Code:</span>
                        <span class="info-value">{{ $permanentAddress->postal_code }}</span>
                    </div>--}}
                 @else
                    <div class="alert alert-info">Permanent address not provided.</div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-section">
                <h5 class="section-title">Present Address</h5>
                @php $presentAddress = $student->addresses->where('type', 'present')->first(); @endphp
                @if($presentAddress)

                    <div class="info-row">
                        <span class="info-label">Atoll:</span>
                        <span class="info-value">{{ $presentAddress->atoll }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Island:</span>
                        <span class="info-value">{{ $presentAddress->island }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">District:</span>
                        <span class="info-value">{{ $presentAddress->district }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{ $presentAddress->address }}</span>
                    </div>
                    {{-- <div class="info-row">
                        <span class="info-label">Postal Code:</span>
                        <span class="info-value">{{ $presentAddress->postal_code }}</span>
                    </div> --}}
                @else
                    <div class="alert alert-info">Present address not provided.</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Parent Details -->
    @if($student->parentDetail)
    <div class="row mb-4">
        <div class="col-12">
            <div class="detail-section">
                <h5 class="section-title">Parent/Guardian Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Name:</span>
                            <span class="info-value">{{ $student->parentDetail->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Relation:</span>
                            <span class="info-value">{{ $student->parentDetail->relation ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Atoll:</span>
                            <span class="info-value">{{ $student->parentDetail->atoll ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Island:</span>
                            <span class="info-value">{{ $student->parentDetail->island ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Address:</span>
                            <span class="info-value">{{ $student->parentDetail->address ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">{{ $student->parentDetail->mobile_no ?? 'N/A' }}</span>
                        </div>
                         <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $student->parentDetail->email ?? 'N/A' }}</span>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Mother's Name:</span>
                            <span class="info-value">{{ $student->parentDetail->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Mother's Occupation:</span>
                            <span class="info-value">{{ $student->parentDetail->occupation ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Mother's Contact:</span>
                            <span class="info-value">{{ $student->parentDetail->contact ?? 'N/A' }}</span>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Documents -->
    @php
    use Illuminate\Support\Str;
@endphp

@if($student->documents->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="detail-section">
            <h5 class="section-title">Uploaded Documents</h5>
            <div class="row">
                @foreach($student->documents as $document)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="document-item">
                        <h6 class="mb-2">{{ ucfirst($document->type) }}</h6>
                        {{-- <p class="mb-2 text-muted">{{ $document->file_path ?? 'No file name' }}</p> --}}

                        <div class="d-flex p-2">
                            @if(!empty($document->file_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($document->file_path))
                                <a  href="{{ route('student.documents.download', $document) }}"
                                    class="action-btn btn-primary btn-sm js-download"
                                    data-url="{{ route('student.documents.download', $document) }}"
                                    data-filename="{{ $document->original_name ?? basename($document->file_path) }}"
                                    data-turbo="false" data-turbolinks="false" wire:navigate="false">
                                    <i class="fas fa-download" style="margin:-7px;"></i> Download
                                </a>
                            @else
                                <span class="text-danger">No file uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif


    <!-- Training Information -->
    @if($student->trainingEnrollments->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="detail-section">
                <h5 class="section-title">Training Information</h5>
                @foreach($student->trainingEnrollments as $enrollment)
                <div class="document-item">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Batch:</strong> {{ $enrollment->trainingBatch->batch_name }} ({{ $enrollment->trainingBatch->batch_code }})
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $enrollment->status === 'completed' ? 'success' : ($enrollment->status === 'in_training' ? 'primary' : 'warning') }}">
                                {{ str_replace('_', ' ', ucfirst($enrollment->status)) }}
                            </span>
                        </div>
                        <div class="col-md-3">
                            <strong>Enrolled:</strong> {{ $enrollment->enrollment_date->format('d/m/Y') }}
                        </div>
                    </div>
                    @if($enrollment->completion_date)
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Completed:</strong> {{ $enrollment->completion_date->format('d/m/Y') }}
                        </div>
                    </div>
                    @endif
                    @if($enrollment->notes)
                    <div class="row mt-2">
                        <div class="col-12">
                            <strong>Notes:</strong> {{ $enrollment->notes }}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Deployment Information -->
    @if($student->deployments->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="detail-section">
                <h5 class="section-title">Deployment Information</h5>
                @foreach($student->deployments as $deployment)
                <div class="document-item">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Unit:</strong> {{ ucfirst($deployment->unit) }}
                        </div>
                        <div class="col-md-3">
                            <strong>Unit Name:</strong> {{ $deployment->unit_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Position:</strong> {{ $deployment->position }}
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $deployment->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($deployment->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Deployment Date:</strong> {{ $deployment->deployment_date->format('d/m/Y') }}
                        </div>
                        @if($deployment->notes)
                        <div class="col-md-6">
                            <strong>Notes:</strong> {{ $deployment->notes }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Interview Information -->
    @if($student->interviews->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="detail-section">
                <h5 class="section-title">Interview Information</h5>
                @foreach($student->interviews as $interview)
                <div class="document-item">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $interview->status === 'completed' ? 'success' : ($interview->status === 'scheduled' ? 'primary' : 'warning') }}">
                                {{ ucfirst($interview->status) }}
                            </span>
                        </div>
                        <div class="col-md-3">
                            <strong>Interviewer:</strong> {{ $interview->interviewer_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Scheduled:</strong> {{ $interview->scheduled_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Result:</strong>
                            @if($interview->result)
                                <span class="badge bg-{{ $interview->result === 'pass' ? 'success' : ($interview->result === 'fail' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($interview->result) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </div>
                    </div>
                    @if($interview->feedback)
                    <div class="row mt-2">
                        <div class="col-12">
                            <strong>Feedback:</strong> {{ $interview->feedback }}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Update Stage Modal -->
<div class="modal fade" id="updateStageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Application Stage - {{ $student->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('national-service-lms.students.update-stage', $student) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="application_stage" class="form-label">Application Stage</label>
                        <select name="application_stage" id="application_stage" class="form-select" required>
                            <option value="pending" {{ $student->application_stage == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ $student->application_stage == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved_for_interview" {{ $student->application_stage == 'approved_for_interview' ? 'selected' : '' }}>Approved for Interview</option>
                            <option value="interview_scheduled" {{ $student->application_stage == 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="interview_completed" {{ $student->application_stage == 'interview_completed' ? 'selected' : '' }}>Interview Completed</option>
                            <option value="approved" {{ $student->application_stage == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $student->application_stage == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason (if applicable)</label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" placeholder="Enter rejection reason if rejecting the application">{{ $student->rejection_reason }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stage</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Interview - {{ $student->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('national-service-lms.interviews.schedule') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scheduled_at" class="form-label">Interview Date & Time</label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="interviewer_name" class="form-label">Interviewer Name</label>
                        <input type="text" name="interviewer_name" id="interviewer_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any additional notes for the interview"></textarea>
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

<!-- Create Deployment Modal -->
<div class="modal fade" id="createDeploymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Deployment - {{ $student->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('national-service-lms.deployments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <select name="unit" id="unit" class="form-select" required>
                            <option value="mndf">MNDF</option>
                            <option value="police">Police</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unit_name" class="form-label">Unit Name</label>
                        <input type="text" name="unit_name" id="unit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" id="position" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="deployment_date" class="form-label">Deployment Date</label>
                        <input type="date" name="deployment_date" id="deployment_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any additional notes for the deployment"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Create Deployment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    // Set minimum date for interview scheduling to today
    document.getElementById('scheduled_at').min = new Date().toISOString().slice(0, 16);

    // Set minimum date for deployment to today
    document.getElementById('deployment_date').min = new Date().toISOString().split('T')[0];
</script>

<script>
    document.addEventListener('click', async function (e) {
      const btn = e.target.closest('.js-download');
      if (!btn) return;

      e.preventDefault(); // stop SPA/turbo/whatever from hijacking
      const url = btn.dataset.url;
      let filename = btn.dataset.filename || 'download';

      try {
        const res = await fetch(url, { credentials: 'same-origin' });
        if (!res.ok) {
          const text = await res.text().catch(() => '');
          throw new Error('Download failed: ' + (text || res.status + ' ' + res.statusText));
        }

        // Try to extract filename from Content-Disposition if server sets it
        const disp = res.headers.get('Content-Disposition') || '';
        const m = disp.match(/filename\*=UTF-8''([^;]+)|filename="?([^"]+)"?/i);
        if (m) {
          filename = decodeURIComponent(m[1] || m[2]);
        }

        const blob = await res.blob();
        const blobUrl = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(blobUrl);
      } catch (err) {
        alert(err.message || 'Could not download file.');
        console.error(err);
      }
    });
    </script>

@endpush
