<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Training Batch Details</title>
    
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

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            text-align: center;
            border-left: 4px solid var(--primary-blue);
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

        .status-planning { background: #e3f2fd; color: #1976d2; }
        .status-active { background: #e8f5e8; color: #2e7d32; }
        .status-completed { background: #f3e5f5; color: #7b1fa2; }
        .status-cancelled { background: #ffebee; color: #c62828; }

        .student-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }

        .student-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.batches.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Batch Details: {{ $batch->batch_name }}
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
        <!-- Batch Information -->
        <div class="info-card">
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h3 class="mb-2">{{ $batch->batch_name }}</h3>
                    <p class="text-muted mb-0">{{ $batch->batch_code }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="status-badge status-{{ $batch->status }}">
                        {{ ucfirst($batch->status) }}
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong><i class="bi bi-calendar-event me-2"></i>Start Date:</strong>
                        {{ $batch->start_date->format('F d, Y') }}
                    </div>
                    <div class="mb-3">
                        <strong><i class="bi bi-calendar-check me-2"></i>End Date:</strong>
                        {{ $batch->end_date->format('F d, Y') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong><i class="bi bi-people me-2"></i>Capacity:</strong>
                        {{ $batch->capacity }} students
                    </div>
                    <div class="mb-3">
                        <strong><i class="bi bi-clock me-2"></i>Duration:</strong>
                        {{ $batch->start_date->diffInDays($batch->end_date) }} days
                    </div>
                </div>
            </div>

            @if($batch->description)
                <div class="mb-3">
                    <strong><i class="bi bi-file-text me-2"></i>Description:</strong>
                    <p class="mt-2">{{ $batch->description }}</p>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('job-portal.batches.edit', $batch->id) }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-pencil me-1"></i>Edit Batch
                    </a>
                    <a href="{{ route('job-portal.batches.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Batches
                    </a>
                </div>
                <div>
                    <small class="text-muted">
                        Created: {{ $batch->created_at->format('M d, Y') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <h4 class="text-primary">{{ $batch->applications->count() }}</h4>
                    <p class="mb-0">Enrolled Students</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h4 class="text-success">{{ $batch->capacity - $batch->applications->count() }}</h4>
                    <p class="mb-0">Available Spots</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h4 class="text-warning">{{ round(($batch->applications->count() / $batch->capacity) * 100, 1) }}%</h4>
                    <p class="mb-0">Capacity Used</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h4 class="text-info">{{ $batch->start_date->diffInDays(now()) }}</h4>
                    <p class="mb-0">Days Until Start</p>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="info-card">
            <h5 class="mb-3">
                <i class="bi bi-bar-chart me-2"></i>Batch Progress
            </h5>
            <div class="progress mb-3" style="height: 25px;">
                @php
                    $percentage = $batch->capacity > 0 ? ($batch->applications->count() / $batch->capacity) * 100 : 0;
                @endphp
                <div class="progress-bar bg-primary" role="progressbar" 
                     style="width: {{ $percentage }}%">
                    {{ round($percentage, 1) }}% Full
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-6">
                    <small class="text-muted">
                        {{ $batch->applications->count() }} of {{ $batch->capacity }} students enrolled
                    </small>
                </div>
                <div class="col-md-6">
                    <small class="text-muted">
                        {{ $batch->capacity - $batch->applications->count() }} spots remaining
                    </small>
                </div>
            </div>
        </div>

        <!-- Enrolled Students -->
        <div class="info-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>Enrolled Students ({{ $batch->applications->count() }})
                </h5>
                <div>
                    <input type="text" class="form-control form-control-sm" id="searchStudents" 
                           placeholder="Search students..." style="width: 200px;">
                </div>
            </div>

            @if($batch->applications->count() > 0)
                <div id="studentsList">
                    @foreach($batch->applications as $application)
                        <div class="student-card" data-name="{{ strtolower($application->student->name ?? '') }}">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <strong>{{ $application->student->name ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $application->student->email ?? 'N/A' }}</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-secondary">{{ $application->application_number }}</span>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">
                                        {{ $application->student->phone ?? 'N/A' }}
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-success">{{ ucfirst($application->status) }}</span>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">
                                        Assigned: {{ $application->updated_at->format('M d, Y') }}
                                    </small>
                                </div>
                                <div class="col-md-1 text-end">
                                    <a href="{{ route('job-portal.applications.show', $application->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">No students enrolled yet</h5>
                    <p class="text-muted">Students will appear here once they are assigned to this batch.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search functionality
        document.getElementById('searchStudents').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const studentCards = document.querySelectorAll('.student-card');
            
            studentCards.forEach(card => {
                const studentName = card.getAttribute('data-name');
                if (studentName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
