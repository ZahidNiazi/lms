<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Training Batches</title>
    
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

        .batch-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .batch-card:hover {
            transform: translateY(-2px);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-planning { background: #fff3cd; color: #856404; }
        .status-active { background: #d4edda; color: #155724; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .progress-bar {
            height: 8px;
            border-radius: 4px;
        }

        .capacity-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Job Portal - Training Batches
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
        <!-- Batches List -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>
                        <i class="bi bi-people me-2"></i>
                        Training Batches ({{ $batches->total() }})
                    </h4>
                    
                    <div>
                        <a href="{{ route('job-portal.batches.create') }}" class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle me-1"></i>Create Batch
                        </a>
                        <a href="{{ route('job-portal.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                @if($batches->count() > 0)
                    @foreach($batches as $batch)
                        <div class="batch-card">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h5 class="mb-1">{{ $batch->batch_name }}</h5>
                                    <p class="mb-1 text-muted">{{ $batch->batch_code }}</p>
                                    <small class="text-muted">
                                        {{ $batch->start_date->format('M d, Y') }} - {{ $batch->end_date->format('M d, Y') }}
                                    </small>
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="status-badge status-{{ $batch->status }}">
                                        {{ ucfirst($batch->status) }}
                                    </span>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="capacity-info">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Enrolled: {{ $batch->applications_count ?? 0 }}</span>
                                            <span>Capacity: {{ $batch->capacity }}</span>
                                        </div>
                                        
                                        @php
                                            $percentage = $batch->capacity > 0 ? (($batch->applications_count ?? 0) / $batch->capacity) * 100 : 0;
                                        @endphp
                                        
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                 style="width: {{ $percentage }}%">
                                                {{ round($percentage, 1) }}%
                                            </div>
                                        </div>
                                        
                                        @if($batch->description)
                                            <p class="mt-2 mb-0 text-muted">{{ Str::limit($batch->description, 100) }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('job-portal.batches.show', $batch->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                        
                                        <a href="{{ route('job-portal.batches.edit', $batch->id) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $batches->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No Training Batches Found</h4>
                        <p class="text-muted">Create your first training batch to get started.</p>
                        <a href="{{ route('job-portal.batches.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Create First Batch
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



