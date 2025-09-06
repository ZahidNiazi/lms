<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Police & DIS Vetting</title>
    
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

        .vetting-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .vetting-card:hover {
            transform: translateY(-2px);
        }

        .type-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-police { background: #d1ecf1; color: #0c5460; }
        .type-dis { background: #d4edda; color: #155724; }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-in-progress { background: #d1ecf1; color: #0c5460; }
        .status-cleared { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .status-rejected { background: #f8d7da; color: #721c24; }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Job Portal - Police & DIS Vetting
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
                <i class="bi bi-funnel me-2"></i>Filter Vetting Records
            </h5>
            
            <form method="GET" action="{{ route('job-portal.vetting.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Vetting Type</label>
                        <select name="vetting_type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($vettingTypes as $type)
                                <option value="{{ $type }}" {{ request('vetting_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
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

        <!-- Vetting Records List -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>
                        <i class="bi bi-shield-check me-2"></i>
                        Vetting Records ({{ $vettings->total() }})
                    </h4>
                    
                    <div>
                        <a href="{{ route('job-portal.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                @if($vettings->count() > 0)
                    @foreach($vettings as $vetting)
                        <div class="vetting-card">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <span class="type-badge type-{{ $vetting->vetting_type }}">
                                        <i class="bi bi-shield me-1"></i>
                                        {{ strtoupper($vetting->vetting_type) }}
                                    </span>
                                </div>
                                
                                <div class="col-md-3">
                                    <h6 class="mb-1">{{ $vetting->application->student->name }}</h6>
                                    <small class="text-muted">{{ $vetting->application->application_number }}</small>
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="status-badge status-{{ str_replace('_', '-', $vetting->status) }}">
                                        {{ $vetting->getStatusDisplayName() }}
                                    </span>
                                </div>
                                
                                <div class="col-md-2">
                                    @if($vetting->reference_number)
                                        <strong>Ref:</strong> {{ $vetting->reference_number }}
                                    @else
                                        <span class="text-muted">No reference</span>
                                    @endif
                                </div>
                                
                                <div class="col-md-2">
                                    <small class="text-muted">
                                        Submitted: {{ $vetting->submitted_date ? \Carbon\Carbon::parse($vetting->submitted_date)->format('M d, Y') : 'N/A' }}
                                    </small>
                                    @if($vetting->completed_date)
                                        <br>
                                        <small class="text-muted">
                                            Completed: {{ \Carbon\Carbon::parse($vetting->completed_date)->format('M d, Y') }}
                                        </small>
                                    @endif
                                </div>
                                
                                <div class="col-md-1">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="updateVettingStatus({{ $vetting->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                            
                            @if($vetting->comments)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="alert alert-light">
                                            <strong>Comments:</strong> {{ $vetting->comments }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $vettings->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-shield-check display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No Vetting Records Found</h4>
                        <p class="text-muted">No vetting records match your current filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function updateVettingStatus(id) {
            // This would open a modal or redirect to update form
            alert('Update vetting status functionality will be implemented here');
        }
    </script>
</body>
</html>
