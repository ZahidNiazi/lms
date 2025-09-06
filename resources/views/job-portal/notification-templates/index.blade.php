<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Notification Templates</title>
    
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

        .template-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .template-card:hover {
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

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }

        .trigger-badge {
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
                Job Portal - Notification Templates
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
                <i class="bi bi-funnel me-2"></i>Filter Templates
            </h5>
            
            <form method="GET" action="{{ route('job-portal.notification-templates.index') }}">
                <div class="row">
                    <div class="col-md-4">
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
                    
                    <div class="col-md-4">
                        <label class="form-label">Trigger Event</label>
                        <select name="trigger_event" class="form-select">
                            <option value="">All Events</option>
                            @foreach($triggerEvents as $event)
                                <option value="{{ $event }}" {{ request('trigger_event') == $event ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $event)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
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

        <!-- Templates List -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>
                        <i class="bi bi-envelope me-2"></i>
                        Notification Templates ({{ $templates->total() }})
                    </h4>
                    
                    <div>
                        <a href="{{ route('job-portal.notification-templates.create') }}" class="btn btn-primary me-2">
                            <i class="bi bi-plus-circle me-1"></i>Create Template
                        </a>
                        <a href="{{ route('job-portal.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                @if($templates->count() > 0)
                    @foreach($templates as $template)
                        <div class="template-card">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <span class="type-badge type-{{ $template->type }}">
                                        <i class="bi bi-{{ $template->type == 'email' ? 'envelope' : ($template->type == 'sms' ? 'chat' : 'whatsapp') }} me-1"></i>
                                        {{ ucfirst($template->type) }}
                                    </span>
                                </div>
                                
                                <div class="col-md-3">
                                    <h6 class="mb-1">{{ $template->name }}</h6>
                                    <span class="trigger-badge">{{ ucfirst(str_replace('_', ' ', $template->trigger_event)) }}</span>
                                </div>
                                
                                <div class="col-md-4">
                                    @if($template->subject)
                                        <strong>{{ $template->subject }}</strong>
                                        <br>
                                    @endif
                                    <p class="mb-1 text-muted">{{ Str::limit($template->body, 100) }}</p>
                                </div>
                                
                                <div class="col-md-2">
                                    <span class="status-badge status-{{ $template->is_active ? 'active' : 'inactive' }}">
                                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                
                                <div class="col-md-1">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('job-portal.notification-templates.edit', $template->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $templates->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-envelope display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No Notification Templates Found</h4>
                        <p class="text-muted">Create your first notification template to get started.</p>
                        <a href="{{ route('job-portal.notification-templates.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Create First Template
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
