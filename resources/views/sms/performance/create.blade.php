<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Add Performance Record</title>

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
                SMS - Add Performance Record
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
                            <i class="bi bi-plus-circle me-2"></i>
                            Add Performance Record
                        </h2>
                        <p class="text-muted mb-0">Create a new performance evaluation for a student</p>
                    </div>
                    <div>
                        <a href="{{ route('sms.performance.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Performance
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-data me-2"></i>
                            Performance Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sms.performance.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                <!-- Student Selection -->
                                <div class="col-md-6">
                                    <label for="student_id" class="form-label">
                                        Student <span class="text-danger">*</span>
                                    </label>
                                    <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}"
                                                    {{ (old('student_id') == $student->id || ($selectedStudent && $selectedStudent->id == $student->id)) ? 'selected' : '' }}>
                                                {{ $student->full_name }} (ID: {{ $student->student_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Performance Field -->
                                <div class="col-md-6">
                                    <label for="performance_field_id" class="form-label">
                                        Performance Field <span class="text-danger">*</span>
                                    </label>
                                    <select name="performance_field_id" id="performance_field_id" class="form-select @error('performance_field_id') is-invalid @enderror" required>
                                        <option value="">Select Performance Field</option>
                                        @foreach($performanceFields as $field)
                                            <option value="{{ $field->id }}" {{ old('performance_field_id') == $field->id ? 'selected' : '' }}>
                                                {{ $field->name }} ({{ $field->category }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('performance_field_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Score -->
                                <div class="col-md-4">
                                    <label for="score" class="form-label">
                                        Score <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="score" id="score"
                                           class="form-control @error('score') is-invalid @enderror"
                                           value="{{ old('score') }}"
                                           min="0" step="0.01" required>
                                    @error('score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Max Score -->
                                <div class="col-md-4">
                                    <label for="max_score" class="form-label">
                                        Maximum Score <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="max_score" id="max_score"
                                           class="form-control @error('max_score') is-invalid @enderror"
                                           value="{{ old('max_score') }}"
                                           min="0" step="0.01" required>
                                    @error('max_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Evaluation Date -->
                                <div class="col-md-4">
                                    <label for="evaluation_date" class="form-label">
                                        Evaluation Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="evaluation_date" id="evaluation_date"
                                           class="form-control @error('evaluation_date') is-invalid @enderror"
                                           value="{{ old('evaluation_date', date('Y-m-d')) }}" required>
                                    @error('evaluation_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Comments -->
                                <div class="col-12">
                                    <label for="comments" class="form-label">Comments</label>
                                    <textarea name="comments" id="comments" rows="3"
                                              class="form-control @error('comments') is-invalid @enderror"
                                              placeholder="Enter general comments about the performance...">{{ old('comments') }}</textarea>
                                    @error('comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Counselling Notes -->
                                <div class="col-12">
                                    <label for="counselling_notes" class="form-label">Counselling Notes</label>
                                    <textarea name="counselling_notes" id="counselling_notes" rows="3"
                                              class="form-control @error('counselling_notes') is-invalid @enderror"
                                              placeholder="Enter counselling notes and recommendations...">{{ old('counselling_notes') }}</textarea>
                                    @error('counselling_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pay Step -->
                                <div class="col-md-6">
                                    <label for="pay_step" class="form-label">Pay Step</label>
                                    <input type="text" name="pay_step" id="pay_step"
                                           class="form-control @error('pay_step') is-invalid @enderror"
                                           value="{{ old('pay_step') }}"
                                           placeholder="e.g., Step 1, Step 2, etc.">
                                    @error('pay_step')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Performance Indicator -->
                                <div class="col-md-6">
                                    <label for="performance_indicator" class="form-label">Performance Indicator</label>
                                    <input type="text" name="performance_indicator" id="performance_indicator"
                                           class="form-control @error('performance_indicator') is-invalid @enderror"
                                           value="{{ old('performance_indicator') }}"
                                           placeholder="e.g., Excellent, Good, Needs Improvement">
                                    @error('performance_indicator')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Observation Notes -->
                                <div class="col-12">
                                    <label for="observation_notes" class="form-label">Observation Notes</label>
                                    <textarea name="observation_notes" id="observation_notes" rows="3"
                                              class="form-control @error('observation_notes') is-invalid @enderror"
                                              placeholder="Enter detailed observation notes...">{{ old('observation_notes') }}</textarea>
                                    @error('observation_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Document Upload -->
                                <div class="col-12">
                                    <label for="documents" class="form-label">Supporting Documents</label>
                                    <input type="file" name="documents[]" id="documents"
                                           class="form-control @error('documents') is-invalid @enderror"
                                           multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <div class="form-text">
                                        You can upload multiple files. Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max 10MB each)
                                    </div>
                                    @error('documents')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('sms.performance.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Create Performance Record
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Performance Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-semibold">Scoring Guidelines:</h6>
                            <ul class="list-unstyled small">
                                <li><span class="badge bg-success me-1">A+</span> 90-100% - Outstanding</li>
                                <li><span class="badge bg-warning me-1">A</span> 80-89% - Excellent</li>
                                <li><span class="badge bg-warning me-1">B+</span> 70-79% - Good</li>
                                <li><span class="badge bg-info me-1">B</span> 60-69% - Satisfactory</li>
                                <li><span class="badge bg-info me-1">C+</span> 50-59% - Below Average</li>
                                <li><span class="badge bg-danger me-1">C</span> 40-49% - Poor</li>
                                <li><span class="badge bg-danger me-1">D</span> 30-39% - Very Poor</li>
                                <li><span class="badge bg-dark me-1">F</span> Below 30% - Fail</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-semibold">Document Types:</h6>
                            <ul class="list-unstyled small">
                                <li><i class="bi bi-file-text me-1"></i> Assessment Reports</li>
                                <li><i class="bi bi-file-text me-1"></i> Observation Forms</li>
                                <li><i class="bi bi-file-text me-1"></i> Counselling Records</li>
                                <li><i class="bi bi-file-text me-1"></i> Performance Certificates</li>
                                <li><i class="bi bi-file-text me-1"></i> Training Records</li>
                            </ul>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-lightbulb me-2"></i>
                            <strong>Tip:</strong> Be specific and objective in your comments and observations. Include concrete examples when possible.
                        </div>
                    </div>
                </div>

                @if($selectedStudent)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-person me-2"></i>
                                Selected Student
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                @if($selectedStudent->photo)
                                    <img src="{{ Storage::url($selectedStudent->photo) }}"
                                         alt="{{ $selectedStudent->full_name }}"
                                         class="rounded-circle me-3"
                                         width="50" height="50">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $selectedStudent->full_name }}</h6>
                                    <small class="text-muted">{{ $selectedStudent->student_id }}</small>
                                    @if($selectedStudent->batch)
                                        <br><small class="text-muted">{{ $selectedStudent->batch->batch_name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-calculate percentage when score or max_score changes
        const scoreInput = document.getElementById('score');
        const maxScoreInput = document.getElementById('max_score');

        function calculatePercentage() {
            const score = parseFloat(scoreInput.value) || 0;
            const maxScore = parseFloat(maxScoreInput.value) || 0;

            if (maxScore > 0) {
                const percentage = (score / maxScore) * 100;
                console.log('Percentage:', percentage.toFixed(2) + '%');
            }
        }

        scoreInput.addEventListener('input', calculatePercentage);
        maxScoreInput.addEventListener('input', calculatePercentage);
    });
    </script>
</body>
</html>
