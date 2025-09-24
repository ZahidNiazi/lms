<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Performance Record Details</title>
    
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

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Performance Record Details
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
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">
                            <i class="bi bi-eye me-2"></i>
                            Performance Record Details
                        </h2>
                        <p class="text-muted mb-0">View detailed performance evaluation information</p>
                    </div>
                    <div>
                        <a href="{{ route('sms.performance.edit', $performance->id) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil me-1"></i>
                            Edit Record
                        </a>
                        <a href="{{ route('sms.performance.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Performance
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Student Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-person me-2"></i>
                            Student Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    @if($performance->student->photo)
                                        <img src="{{ Storage::url($performance->student->photo) }}" 
                                             alt="{{ $performance->student->full_name }}" 
                                             class="rounded-circle me-3" 
                                             width="60" height="60">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-person text-white fs-4"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="mb-1">{{ $performance->student->full_name }}</h4>
                                        <p class="text-muted mb-1">{{ $performance->student->student_id }}</p>
                                        @if($performance->student->batch)
                                            <span class="badge bg-primary">{{ $performance->student->batch->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('sms.students.show', $performance->student->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-person-lines-fill me-1"></i>
                                    View Student Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-data me-2"></i>
                            Performance Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Performance Field</label>
                                <div class="p-2 bg-light rounded">
                                    <div class="fw-semibold">{{ $performance->performanceField->name }}</div>
                                    <small class="text-muted">{{ $performance->performanceField->category }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Evaluation Date</label>
                                <div class="p-2 bg-light rounded">
                                    {{ $performance->evaluation_date->format('F d, Y') }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Score</label>
                                <div class="p-2 bg-light rounded">
                                    <span class="fs-4 fw-bold">{{ $performance->score }}/{{ $performance->max_score }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Percentage</label>
                                <div class="p-2 bg-light rounded">
                                    <span class="fs-4 fw-bold">{{ $performance->percentage }}%</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Grade</label>
                                <div class="p-2 bg-light rounded">
                                    <span class="badge fs-5 
                                        @if($performance->grade == 'A+') bg-success
                                        @elseif(in_array($performance->grade, ['A', 'B+'])) bg-warning
                                        @elseif(in_array($performance->grade, ['B', 'C+'])) bg-info
                                        @else bg-danger @endif">
                                        {{ $performance->grade }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-3">
                            <label class="form-label fw-semibold">Performance Progress</label>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar 
                                    @if($performance->percentage >= 90) bg-success
                                    @elseif($performance->percentage >= 70) bg-warning
                                    @else bg-danger @endif" 
                                     style="width: {{ $performance->percentage }}%">
                                    {{ $performance->percentage }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments and Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-chat-text me-2"></i>
                            Comments & Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if($performance->comments)
                                <div class="col-12">
                                    <label class="form-label fw-semibold">General Comments</label>
                                    <div class="p-3 bg-light rounded">
                                        {{ $performance->comments }}
                                    </div>
                                </div>
                            @endif

                            @if($performance->counselling_notes)
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Counselling Notes</label>
                                    <div class="p-3 bg-light rounded">
                                        {{ $performance->counselling_notes }}
                                    </div>
                                </div>
                            @endif

                            @if($performance->observation_notes)
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Observation Notes</label>
                                    <div class="p-3 bg-light rounded">
                                        {{ $performance->observation_notes }}
                                    </div>
                                </div>
                            @endif

                            @if($performance->pay_step)
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Pay Step</label>
                                    <div class="p-2 bg-light rounded">
                                        {{ $performance->pay_step }}
                                    </div>
                                </div>
                            @endif

                            @if($performance->performance_indicator)
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Performance Indicator</label>
                                    <div class="p-2 bg-light rounded">
                                        {{ $performance->performance_indicator }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                @if($performance->documents->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-paperclip me-2"></i>
                                Supporting Documents
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($performance->documents as $document)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if(str_contains($document->mime_type, 'pdf'))
                                                            <i class="bi bi-file-pdf text-danger fs-2"></i>
                                                        @elseif(str_contains($document->mime_type, 'image'))
                                                            <i class="bi bi-file-image text-info fs-2"></i>
                                                        @elseif(str_contains($document->mime_type, 'word'))
                                                            <i class="bi bi-file-word text-primary fs-2"></i>
                                                        @else
                                                            <i class="bi bi-file-text text-secondary fs-2"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $document->document_name }}</h6>
                                                        <small class="text-muted">
                                                            {{ number_format($document->file_size / 1024, 1) }} KB
                                                            • Uploaded {{ $document->upload_date->format('M d, Y') }}
                                                        </small>
                                                    </div>
                                                    <div class="ms-2">
                                                        <a href="{{ route('sms.performance.download-document', $document->id) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Evaluation Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Evaluation Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Evaluated By</label>
                            <div class="p-2 bg-light rounded">
                                {{ $performance->evaluator->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Evaluation Date</label>
                            <div class="p-2 bg-light rounded">
                                {{ $performance->evaluation_date->format('F d, Y') }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Created</label>
                            <div class="p-2 bg-light rounded">
                                {{ $performance->created_at->format('F d, Y g:i A') }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Last Updated</label>
                            <div class="p-2 bg-light rounded">
                                {{ $performance->updated_at->format('F d, Y g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Field Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-gear me-2"></i>
                            Performance Field Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Field Name</label>
                            <div class="p-2 bg-light rounded">
                                {{ $performance->performanceField->name }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-primary">{{ $performance->performanceField->category }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Maximum Score</label>
                            <div class="p-2 bg-light rounded">
                                {{ $performance->performanceField->max_score }}
                            </div>
                        </div>
                        @if($performance->performanceField->description)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <div class="p-2 bg-light rounded">
                                    {{ $performance->performanceField->description }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-tools me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('sms.performance.edit', $performance->id) }}" 
                               class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>
                                Edit Record
                            </a>
                            <a href="{{ route('sms.performance.student-performance', $performance->student->id) }}" 
                               class="btn btn-info">
                                <i class="bi bi-graph-up me-1"></i>
                                View Student Performance
                            </a>
                            <form action="{{ route('sms.performance.destroy', $performance->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this performance record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash me-1"></i>
                                    Delete Record
                                </button>
                            </form>
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