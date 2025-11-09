<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: #004080;
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: white;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h5 class="text-white">SMS Module</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('sms.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('sms.recruitment') }}">
                            <i class="bi bi-people me-2"></i>Recruitment
                        </a>
                        <a class="nav-link active" href="{{ route('sms.leave') }}">
                            <i class="bi bi-calendar-check me-2"></i>Leave Management
                        </a>
                        <a class="nav-link" href="{{ route('sms.attendance') }}">
                            <i class="bi bi-person-check me-2"></i>Attendance
                        </a>
                        <a class="nav-link" href="{{ route('sms.performance') }}">
                            <i class="bi bi-graph-up me-2"></i>Performance
                        </a>
                        <a class="nav-link" href="{{ route('sms.medical') }}">
                            <i class="bi bi-heart-pulse me-2"></i>Medical
                        </a>
                        <a class="nav-link" href="{{ route('sms.hr') }}">
                            <i class="bi bi-award me-2"></i>HR Admin
                        </a>
                        <a class="nav-link" href="{{ route('sms.deployment') }}">
                            <i class="bi bi-geo-alt me-2"></i>Deployment
                        </a>
                        <a class="nav-link" href="{{ route('job-portal.dashboard') }}">
                            <i class="bi bi-arrow-left me-2"></i>Back to Job Portal
                        </a>
                    </nav>
                </div>
            </div>
            <div class="col-md-10">
                <div class="p-4">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('job-portal.dashboard') }}">Job Portal</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('sms.dashboard') }}">SMS</a></li>
                        <li class="breadcrumb-item active">Leave Management</li>
                    </ol>
                </div>
                <h4 class="page-title">Leave Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Leave Applications</h5>
                    <p class="text-muted">Manage student leave applications and approvals</p>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-check display-1 text-muted"></i>
                        <h5 class="mt-3">Leave Managementasdsadsa</h5>
                        <p class="text-muted">Leave management functionality will be implemented here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
