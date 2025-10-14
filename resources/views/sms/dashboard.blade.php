<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Student Management System</title>

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
            margin-bottom: 1rem;
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
            <a class="navbar-brand" href="{{ route('sms.dashboard') }}">
                <i class="bi bi-people-fill me-2"></i>
                SMS - Student Management System
            </a>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('job-portal.dashboard') }}">
                            <i class="bi bi-briefcase me-2"></i>Job Portal
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
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['total_students'] ?? 0 }}</h3>
                            <p class="stats-label">Total Students</p>
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
                            <h3 class="stats-number">{{ $stats['active_students'] ?? 0 }}</h3>
                            <p class="stats-label">Active Students</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--warning-yellow);">
                            <i class="bi bi-collection"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['active_batches'] ?? 0 }}</h3>
                            <p class="stats-label">Active Batches</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background: var(--purple);">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['pending_leaves'] ?? 0 }}</h3>
                            <p class="stats-label">Pending Leaves</p>
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
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="feature-title">Student Management</h5>
                    <p class="feature-desc">Manage student records, personal information, and recruitment setup.</p>
                    <a href="{{ route('sms.students.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Manage Students
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--success-green);">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                    <h5 class="feature-title">Leave Management</h5>
                    <p class="feature-desc">Manage student leaves, approve/reject applications, and track leave types.</p>
                    <a href="{{ route('sms.leaves.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Manage Leaves
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--warning-yellow);">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h5 class="feature-title">Attendance</h5>
                    <p class="feature-desc">Mark and track student attendance, manage daily and monthly records.</p>
                    <a href="{{ route('sms.attendance.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Manage Attendance
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--purple);">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h5 class="feature-title">Performance</h5>
                    <p class="feature-desc">Track student performance, skills, counselling, and assessments.</p>
                    <a href="{{ route('sms.performance.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Manage Performance
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--danger-red);">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h5 class="feature-title">Medical Records</h5>
                    <p class="feature-desc">Manage student medical information, excuses, and health records.</p>
                    <a href="{{ route('sms.medical.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Medical Records
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background: var(--primary-blue);">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5 class="feature-title">Graduation & Posting</h5>
                    <p class="feature-desc">Manage student graduation, postings to Police, MNDF, and other units.</p>
                    <a href="{{ route('sms.graduation.index') }}" class="btn btn-feature">
                        <i class="bi bi-arrow-right me-1"></i>Graduation
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
                            <i class="bi bi-clock-history me-2"></i>Recent Students
                        </h5>
                        <a href="{{ route('sms.students.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>

                    @forelse($recentStudents as $student)
                        <div class="activity-item">
                            <div class="activity-icon" style="background: var(--primary-blue);">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-title">{{ $student->full_name }}</p>
                                <p class="activity-subtitle">ID: {{ $student->student_id }} | Batch: {{ $student->batch->batch_name ?? 'N/A' }}</p>
                                <p class="activity-time">{{ $student->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-content">
                                <p class="activity-title text-muted">No recent students</p>
                                <p class="activity-time">Check back later for updates</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-md-6">
                <div class="recent-activity">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-event me-2"></i>Recent Leave Applications
                        </h5>
                        <a href="{{ route('sms.leaves.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>

                    @forelse($recentLeaves as $leave)
                        <div class="activity-item">
                            <div class="activity-icon" style="background: {{ $leave->status === 'pending' ? 'var(--warning-yellow)' : ($leave->status === 'approved' ? 'var(--success-green)' : 'var(--danger-red)') }};">
                                <i class="bi bi-calendar-{{ $leave->status === 'pending' ? 'plus' : ($leave->status === 'approved' ? 'check' : 'x') }}"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-title">{{ $leave->student->name ?? 'Unknown Student' }}</p>
                                <p class="activity-subtitle">{{ $leave->leaveType->name ?? 'Unknown Type' }} - {{ ucfirst($leave->status) }}</p>
                                <p class="activity-time">{{ $leave->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-content">
                                <p class="activity-title text-muted">No recent leave applications</p>
                                <p class="activity-time">Check back later for updates</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
