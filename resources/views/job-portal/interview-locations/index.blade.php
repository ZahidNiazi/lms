<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Interview Locations</title>
    
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

        .location-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .location-card:hover {
            transform: translateY(-2px);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }

        .facility-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.7rem;
            margin: 0.2rem;
            display: inline-block;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Job Portal - Interview Locations
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
                <i class="bi bi-funnel me-2"></i>Filter Locations
            </h5>
            
            <form method="GET" action="{{ route('job-portal.interview-locations.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Location name or address"
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">City</label>
                        <select name="city" class="form-select">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
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

        <!-- Locations List -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>
                        <i class="bi bi-geo-alt me-2"></i>
                        Interview Locations ({{ $locations->total() }})
                    </h4>
                    
                    <div>
                        <a href="{{ route('job-portal.interview-locations.create') }}" class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle me-1"></i>Add Location
                        </a>
                        <a href="{{ route('job-portal.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                @if($locations->count() > 0)
                    @foreach($locations as $location)
                        <div class="location-card">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h6 class="mb-1">{{ $location->name }}</h6>
                                    <p class="mb-1 text-muted">{{ $location->address }}</p>
                                    <small class="text-muted">{{ $location->city }}, {{ $location->atoll }}</small>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-2">
                                        <strong>Capacity:</strong> {{ $location->capacity }}
                                    </div>
                                    @if($location->contact_person)
                                        <div>
                                            <strong>Contact:</strong> {{ $location->contact_person }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-3">
                                    @if($location->available_facilities && count($location->available_facilities) > 0)
                                        <div class="mb-2">
                                            <strong>Facilities:</strong>
                                        </div>
                                        <div>
                                            @foreach($location->available_facilities as $facility)
                                                <span class="facility-badge">{{ ucfirst(str_replace('_', ' ', $facility)) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="status-badge status-{{ $location->is_active ? 'active' : 'inactive' }}">
                                        {{ $location->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('job-portal.interview-locations.edit', $location->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        
                                        @if($location->contact_phone)
                                            <a href="tel:{{ $location->contact_phone }}" 
                                               class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-telephone me-1"></i>Call
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $locations->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-geo-alt display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No Interview Locations Found</h4>
                        <p class="text-muted">No locations match your current filters.</p>
                        <a href="{{ route('job-portal.interview-locations.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Add First Location
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



