<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Admin Dashboard</title>

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
            border: none;
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

        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: none;
            transition: all 0.2s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .btn-feature {
            background: var(--primary-blue);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .btn-feature:hover {
            background: #3d6bff;
            color: white;
            transform: translateY(-1px);
        }

        .recent-activity {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            margin-right: 1rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0;
        }

        .activity-subtitle {
            font-size: 0.8rem;
            color: #6c757d;
            margin: 0.25rem 0;
            font-weight: 400;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #6c757d;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-briefcase-fill me-2"></i>
                Job Portal - Admin Dashboard
            </a>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('job-portal.reports.index') }}">
                            <i class="bi bi-graph-up me-2"></i>Reports
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
                            <h3 class="stats-number">{{ $stats['approved'] ?? 0 }}</h3>
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

        <!-- Main Features -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--primary-blue);">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <h5 class="feature-title">Application Management</h5>
                    <p class="feature-desc">Review, approve, and manage all job applications with document verification.</p>
                    <a href="{{ route('job-portal.applications.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Manage Applications
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--success-green);">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                    <h5 class="feature-title">Interview Management</h5>
                    <p class="feature-desc">Schedule interviews, manage venues, and record interview results.</p>
                    <a href="{{ route('job-portal.interview-locations.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Manage Interviews
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--warning-yellow);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="feature-title">Batch Management</h5>
                    <p class="feature-desc">Create and manage training batches with 350 students per batch.</p>
                    <a href="{{ route('job-portal.batches.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Manage Batches
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--purple);">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <h5 class="feature-title">Communications</h5>
                    <p class="feature-desc">Send emails, SMS, and WhatsApp messages to students.</p>
                    <a href="{{ route('job-portal.communications.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>View Communications
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--danger-red);">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="feature-title">Vetting System</h5>
                    <p class="feature-desc">Manage police and DIS vetting for selected candidates.</p>
                    <a href="{{ route('job-portal.vetting.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Manage Vetting
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--primary-blue);">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h5 class="feature-title">Reports & Analytics</h5>
                    <p class="feature-desc">View comprehensive reports and export data.</p>
                    <a href="{{ route('job-portal.reports.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-md-6">
                <div class="recent-activity">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>Recent Activity
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshRecentActivity()" title="Refresh">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>

                    @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon" style="background: {{ $activity['icon_color'] }};">
                                <i class="{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-title">{{ $activity['title'] }}</p>
                                <p class="activity-subtitle">{{ $activity['subtitle'] }}</p>
                                <p class="activity-time">{{ $activity['time']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-content">
                                <p class="activity-title text-muted">No recent activities</p>
                                <p class="activity-time">Check back later for updates</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-md-6">
                <div class="recent-activity">
                    <h5 class="mb-3">
                        <i class="bi bi-list-ul me-2"></i>Quick Actions
                    </h5>

                    <div class="d-grid gap-2">
                        <a href="{{ route('job-portal.applications.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-eye me-2"></i>View All Applications
                        </a>
                        <a href="{{ route('job-portal.batches.create') }}" class="btn btn-outline-success">
                            <i class="bi bi-plus-circle me-2"></i>Create New Batch
                        </a>
                        <a href="{{ route('job-portal.batches.assignment.dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people-fill me-2"></i>Assign Students to Batches
                        </a>
                        <a href="{{ route('sms.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-people me-2"></i>SMS - Student Management
                        </a>
                        <a href="{{ route('job-portal.interview-locations.create') }}" class="btn btn-outline-warning">
                            <i class="bi bi-geo-alt me-2"></i>Add Interview Location
                        </a>
                        <a href="{{ route('job-portal.notification-templates.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-envelope me-2"></i>Manage Templates
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function refreshRecentActivity() {
            const refreshBtn = document.querySelector('[onclick="refreshRecentActivity()"]');
            const icon = refreshBtn.querySelector('i');

            // Add spinning animation
            icon.classList.add('bi-arrow-clockwise');
            icon.style.animation = 'spin 1s linear infinite';

            // Reload the page to get fresh data
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }

        // Auto-refresh every 5 minutes
        setInterval(() => {
            window.location.reload();
        }, 300000); // 5 minutes
    </script>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</body>
</html>
