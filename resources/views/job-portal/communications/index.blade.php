<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Communications</title>
    
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

        .communication-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .communication-card:hover {
            transform: translateY(-2px);
        }

        .type-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-email { background: #d1ecf1; color: #0c5460; }
        .type-sms { background: #d4edda; color: #155724; }
        .type-whatsapp { background: #25d366; color: white; }
        .type-system { background: #e2e3e5; color: #383d41; }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-sent { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }

        .acknowledged-badge {
            background: #d4edda;
            color: #155724;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.7rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Job Portal - Communications
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
                <i class="bi bi-funnel me-2"></i>Filter Communications
            </h5>
            
            <form method="GET" action="{{ route('job-portal.communications.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
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
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Acknowledged</label>
                        <select name="acknowledged" class="form-select">
                            <option value="">All</option>
                            <option value="yes" {{ request('acknowledged') == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ request('acknowledged') == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
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

        <!-- Communications List -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>
                        <i class="bi bi-envelope me-2"></i>
                        Communications ({{ $communications->total() }})
                    </h4>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('job-portal.communications.create') }}" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Send Message
                        </a>
                        <a href="{{ route('job-portal.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($communications->count() > 0)
                    @foreach($communications as $communication)
                        <div class="communication-card">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <span class="type-badge type-{{ $communication->type }}">
                                        <i class="bi bi-{{ $communication->type == 'email' ? 'envelope' : ($communication->type == 'sms' ? 'chat' : 'whatsapp') }} me-1"></i>
                                        {{ ucfirst($communication->type) }}
                                    </span>
                                </div>
                                
                                <div class="col-md-3">
                                    <h6 class="mb-1">{{ $communication->application->student->name }}</h6>
                                    <small class="text-muted">{{ $communication->application->application_number }}</small>
                                    @if($communication->subject)
                                        <br>
                                        <strong>{{ $communication->subject }}</strong>
                                    @endif
                                </div>
                                
                                <div class="col-md-3">
                                    <p class="mb-1">{{ Str::limit($communication->message, 100) }}</p>
                                    <small class="text-muted">
                                        Sent: {{ $communication->created_at->format('M d, Y H:i') }}
                                    </small>
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="status-badge status-{{ $communication->status }}">
                                        {{ ucfirst($communication->status) }}
                                    </span>
                                    @if($communication->acknowledged_at)
                                        <br>
                                        <span class="acknowledged-badge">
                                            <i class="bi bi-check-circle me-1"></i>Acknowledged
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('job-portal.communications.show', $communication->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        
                                        @if($communication->status === 'failed')
                                            <button class="btn btn-sm btn-outline-warning" 
                                                    onclick="resendCommunication({{ $communication->id }})">
                                                <i class="bi bi-arrow-clockwise me-1"></i>Resend
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <nav>
                            <ul class="pagination">
                                <!-- Previous Page Link -->
                                @if ($communications->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                        <span class="page-link" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $communications->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                <!-- Pagination Elements -->
                                @foreach ($communications->getUrlRange(1, $communications->lastPage()) as $page => $url)
                                    @if ($page == $communications->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                <!-- Next Page Link -->
                                @if ($communications->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $communications->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                                        <span class="page-link" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No Communications Found</h4>
                        <p class="text-muted">No communications match your current filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function resendCommunication(id) {
            if (confirm('Are you sure you want to resend this communication?')) {
                fetch(`/job-portal/admin/communications/${id}/resend`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to resend communication');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while resending the communication');
                });
            }
        }
    </script>
</body>
</html>

