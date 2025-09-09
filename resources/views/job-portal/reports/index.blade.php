<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Reports & Analytics</title>
    
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

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Job Portal - Reports & Analytics
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
        <!-- Date Range Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-calendar-range me-2"></i>Report Period
                        </h5>
                        
                        <form method="GET" action="{{ route('job-portal.reports.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">From Date</label>
                                    <input type="date" name="date_from" class="form-control" 
                                           value="{{ $dateFrom }}">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">To Date</label>
                                    <input type="date" name="date_to" class="form-control" 
                                           value="{{ $dateTo }}">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-1"></i>Update Report
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <a href="{{ route('job-portal.reports.export', ['type' => 'applications', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                                           class="btn btn-success">
                                            <i class="bi bi-download me-1"></i>Export Data
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--primary-blue);">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['total_applications'] ?? 0 }}</h3>
                            <p class="stats-label">Total Applications</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--success-green);">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['approved_applications'] ?? 0 }}</h3>
                            <p class="stats-label">Approved</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--warning-yellow);">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['interview_scheduled'] ?? 0 }}</h3>
                            <p class="stats-label">Interviews Scheduled</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--purple);">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['batch_assigned'] ?? 0 }}</h3>
                            <p class="stats-label">Batch Assigned</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--danger-red);">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['rejected_applications'] ?? 0 }}</h3>
                            <p class="stats-label">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--primary-blue);">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['total_communications'] ?? 0 }}</h3>
                            <p class="stats-label">Communications Sent</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--success-green);">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['cleared_vetting'] ?? 0 }}</h3>
                            <p class="stats-label">Vetting Cleared</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--warning-yellow);">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['pending_vetting'] ?? 0 }}</h3>
                            <p class="stats-label">Pending Vetting</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Export Options -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-download me-2"></i>Export Reports
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('job-portal.reports.export', ['type' => 'applications', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                                   class="btn btn-outline-primary w-100">
                                    <i class="bi bi-file-earmark-text me-2"></i>Applications
                                </a>
                            </div>
                            
                            <div class="col-md-3">
                                <a href="{{ route('job-portal.reports.export', ['type' => 'interviews', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                                   class="btn btn-outline-warning w-100">
                                    <i class="bi bi-calendar-event me-2"></i>Interviews
                                </a>
                            </div>
                            
                            <div class="col-md-3">
                                <a href="{{ route('job-portal.reports.export', ['type' => 'communications', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                                   class="btn btn-outline-info w-100">
                                    <i class="bi bi-envelope me-2"></i>Communications
                                </a>
                            </div>
                            
                            <div class="col-md-3">
                                <a href="{{ route('job-portal.reports.export', ['type' => 'vetting', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                                   class="btn btn-outline-success w-100">
                                    <i class="bi bi-shield-check me-2"></i>Vetting
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>

