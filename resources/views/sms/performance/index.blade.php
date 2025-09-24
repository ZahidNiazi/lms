<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Performance Management</title>
    
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
                SMS - Performance Management
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
                            Performance Management
                        </h2>
                        <p class="text-muted mb-0">Track student performance, skills, counselling, and assessments</p>
                    </div>
                    <div>
                        <a href="{{ route('sms.performance.create') }}" class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add Performance
                        </a>
                        <a href="{{ route('sms.performance.fields.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-gear me-1"></i>
                            Manage Fields
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['total_evaluations'] }}</h4>
                                <p class="mb-0">Total Evaluations</p>
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
                                <h4 class="mb-0">{{ number_format($stats['average_score'], 1) }}%</h4>
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
                                <h4 class="mb-0">{{ $stats['excellent_performers'] }}</h4>
                                <p class="mb-0">Excellent (A+)</p>
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
                                <h4 class="mb-0">{{ $stats['needs_improvement'] }}</h4>
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

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="student_id" class="form-label">Student</label>
                        <select name="student_id" id="student_id" class="form-select">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->full_name }} ({{ $student->student_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="performance_field_id" class="form-label">Performance Field</label>
                        <select name="performance_field_id" id="performance_field_id" class="form-select">
                            <option value="">All Fields</option>
                            @foreach($performanceFields as $field)
                                <option value="{{ $field->id }}" {{ request('performance_field_id') == $field->id ? 'selected' : '' }}>
                                    {{ $field->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="grade" class="form-label">Grade</label>
                        <select name="grade" id="grade" class="form-select">
                            <option value="">All Grades</option>
                            <option value="A+" {{ request('grade') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A" {{ request('grade') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B+" {{ request('grade') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B" {{ request('grade') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C+" {{ request('grade') == 'C+' ? 'selected' : '' }}>C+</option>
                            <option value="C" {{ request('grade') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ request('grade') == 'D' ? 'selected' : '' }}>D</option>
                            <option value="F" {{ request('grade') == 'F' ? 'selected' : '' }}>F</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('sms.performance.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Performance Records -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Performance Records
                </h5>
            </div>
            <div class="card-body p-0">
                @if($performances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
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
                                            <div class="d-flex align-items-center">
                                                @if($performance->student->photo)
                                                    <img src="{{ Storage::url($performance->student->photo) }}" 
                                                         alt="{{ $performance->student->full_name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="32" height="32">
                                                @else
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 32px; height: 32px;">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $performance->student->full_name }}</div>
                                                    <small class="text-muted">{{ $performance->student->student_id }}</small>
                                                </div>
                                            </div>
                                        </td>
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
                                                <form action="{{ route('sms.performance.destroy', $performance->id) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this performance record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="card-footer">
                        {{ $performances->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-data display-1 text-muted"></i>
                        <h4 class="mt-3">No Performance Records Found</h4>
                        <p class="text-muted">Start by adding performance records for students.</p>
                        <a href="{{ route('sms.performance.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add First Performance Record
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>