<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Student Performance Summary</title>
    
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
                SMS - Student Performance Summary
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
                            <i class="bi bi-graph-up me-2"></i>
                            Performance Summary
                        </h2>
                        <p class="text-muted mb-0">Complete performance overview for {{ $student->full_name }}</p>
                    </div>
                    <div>
                        <a href="{{ route('sms.performance.create', ['student_id' => $student->id]) }}" class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add Performance
                        </a>
                        <a href="{{ route('sms.performance.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Performance
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    @if($student->photo)
                                        <img src="{{ Storage::url($student->photo) }}" 
                                             alt="{{ $student->full_name }}" 
                                             class="rounded-circle me-3" 
                                             width="80" height="80">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 80px; height: 80px;">
                                            <i class="bi bi-person text-white fs-2"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="mb-1">{{ $student->full_name }}</h3>
                                        <p class="text-muted mb-1">{{ $student->student_id }}</p>
                                        @if($student->batch)
                                            <span class="badge bg-primary fs-6">{{ $student->batch->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('sms.students.show', $student->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-person-lines-fill me-1"></i>
                                    View Student Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Statistics -->
        @if($fieldStats->count() > 0)
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $fieldStats->count() }}</h4>
                                    <p class="mb-0">Fields Evaluated</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clipboard-data fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ number_format($fieldStats->avg('average_score'), 1) }}%</h4>
                                    <p class="mb-0">Average Score</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-graph-up fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $fieldStats->where('latest_score', '>=', 90)->count() }}</h4>
                                    <p class="mb-0">Excellent Fields</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-star-fill fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $fieldStats->where('latest_score', '<', 60)->count() }}</h4>
                                    <p class="mb-0">Needs Improvement</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-exclamation-triangle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Performance Fields Overview -->
        @if($fieldStats->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-bar-chart me-2"></i>
                                Performance Fields Overview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($fieldStats as $stat)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">{{ $stat['field']->name }}</h6>
                                                    <span class="badge bg-primary">{{ $stat['field']->category }}</span>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="small text-muted">Latest Score</span>
                                                        <span class="fw-semibold">{{ number_format($stat['latest_score'], 1) }}%</span>
                                                    </div>
                                                    <div class="progress mt-1" style="height: 8px;">
                                                        <div class="progress-bar 
                                                            @if($stat['latest_score'] >= 90) bg-success
                                                            @elseif($stat['latest_score'] >= 70) bg-warning
                                                            @else bg-danger @endif" 
                                                             style="width: {{ $stat['latest_score'] }}%"></div>
                                                    </div>
                                                </div>

                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <div class="small text-muted">Average</div>
                                                        <div class="fw-semibold">{{ number_format($stat['average_score'], 1) }}%</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="small text-muted">Evaluations</div>
                                                        <div class="fw-semibold">{{ $stat['total_evaluations'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        Last: {{ $stat['latest_date']->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Performance History -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Performance History
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($performances->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Performance Field</th>
                                            <th>Score</th>
                                            <th>Grade</th>
                                            <th>Evaluation Date</th>
                                            <th>Evaluator</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($performances as $performance)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold">{{ $performance->performanceField->name }}</div>
                                                        <small class="text-muted">{{ $performance->performanceField->category }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <span class="fw-semibold">{{ $performance->score }}/{{ $performance->max_score }}</span>
                                                        <div class="progress mt-1" style="height: 6px;">
                                                            <div class="progress-bar 
                                                                @if($performance->percentage >= 90) bg-success
                                                                @elseif($performance->percentage >= 70) bg-warning
                                                                @else bg-danger @endif" 
                                                                 style="width: {{ $performance->percentage }}%"></div>
                                                        </div>
                                                        <small class="text-muted">{{ $performance->percentage }}%</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        @if($performance->grade == 'A+') bg-success
                                                        @elseif(in_array($performance->grade, ['A', 'B+'])) bg-warning
                                                        @elseif(in_array($performance->grade, ['B', 'C+'])) bg-info
                                                        @else bg-danger @endif">
                                                        {{ $performance->grade }}
                                                    </span>
                                                </td>
                                                <td>{{ $performance->evaluation_date->format('M d, Y') }}</td>
                                                <td>{{ $performance->evaluator->name ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('sms.performance.show', $performance->id) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('sms.performance.edit', $performance->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-clipboard-data display-1 text-muted"></i>
                                <h4 class="mt-3">No Performance Records Found</h4>
                                <p class="text-muted">This student hasn't been evaluated yet.</p>
                                <a href="{{ route('sms.performance.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Add First Performance Record
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>