<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Applications Management</title>
    
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

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .application-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .application-card:hover {
            transform: translateY(-2px);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-document { background: #d1ecf1; color: #0c5460; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-interview { background: #e2e3e5; color: #383d41; }
        .status-selected { background: #d1ecf1; color: #0c5460; }
        .status-batch { background: #cce5ff; color: #004085; }

        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            margin: 0.2rem;
            transition: all 0.2s ease;
        }

        .btn-review { background: var(--primary-blue); color: white; }
        .btn-approve { background: var(--success-green); color: white; }
        .btn-reject { background: var(--danger-red); color: white; }
        .btn-interview { background: var(--warning-yellow); color: white; }
        .btn-batch { background: var(--purple); color: white; }

        .action-btn:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Job Portal - Applications Management
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
        <!-- Filters -->
        <div class="filter-card">
            <h5 class="mb-3">
                <i class="bi bi-funnel me-2"></i>Filter Applications
            </h5>
            
            <form method="GET" action="{{ route('job-portal.applications.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Name, Email, or Application Number"
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" 
                               value="{{ request('date_to') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Applications List -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Applications ({{ $applications->total() }})
                    </h4>
                    
                    <div>
                        <a href="{{ route('job-portal.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                @if($applications->count() > 0)
                    @foreach($applications as $application)
                        <div class="application-card">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <strong>{{ $application->application_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $application->created_at->format('M d, Y') }}</small>
                                </div>
                                
                                <div class="col-md-3">
                                    <h6 class="mb-1">{{ $application->student->name }}</h6>
                                    <small class="text-muted">{{ $application->student->email }}</small>
                                    <br>
                                    <small class="text-muted">{{ $application->student->profile->mobile_no ?? 'N/A' }}</small>
                                </div>
                                
                                <div class="col-md-2">
                                    @php
                                        $address = $application->student->addresses->first();
                                    @endphp
                                    <small>
                                        {{ $address->island ?? 'N/A' }}, {{ $address->atoll ?? '' }}
                                    </small>
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="status-badge status-{{ str_replace('_', '-', $application->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="d-flex flex-wrap">
                                        @if(in_array($application->status, ['pending_review', 'document_review']))
                                            <a href="{{ route('job-portal.applications.show', $application->id) }}" 
                                               class="action-btn btn-review">
                                                <i class="bi bi-eye me-1"></i>Review
                                            </a>
                                        @endif

                                        @if($application->status === 'approved')
                                            <a href="{{ route('job-portal.applications.schedule-interview', $application->id) }}" 
                                               class="action-btn btn-interview">
                                                <i class="bi bi-calendar-plus me-1"></i>Schedule Interview
                                            </a>
                                        @endif

                                        @if($application->status === 'selected')
                                            <form method="POST" action="{{ route('job-portal.applications.assign-batch', $application->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="action-btn btn-batch">
                                                    <i class="bi bi-people me-1"></i>Assign Batch
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('job-portal.applications.show', $application->id) }}" 
                                           class="action-btn btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $applications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No Applications Found</h4>
                        <p class="text-muted">No applications match your current filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



