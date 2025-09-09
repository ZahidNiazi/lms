<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Attendance Management</title>
    
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

        .card {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 12px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .attendance-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            box-shadow: var(--card-shadow);
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Attendance Management
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('sms.dashboard') }}">
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
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Attendance Management</h2>
                <p class="text-muted mb-0">Mark and track student attendance</p>
            </div>
            <div>
                <a href="{{ route('sms.attendance.monthly') }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-calendar3 me-2"></i>Monthly View
                </a>
                <a href="{{ route('sms.attendance.mark') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Mark Attendance
                </a>
            </div>
        </div>

        <!-- Attendance Statistics -->
        <div class="attendance-stats">
            <div class="stat-card">
                <div class="stat-number text-success">{{ $attendances->where('status', 'present')->count() }}</div>
                <div class="stat-label">Present</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-danger">{{ $attendances->where('status', 'absent')->count() }}</div>
                <div class="stat-label">Absent</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-warning">{{ $attendances->where('status', 'late')->count() }}</div>
                <div class="stat-label">Late</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-info">{{ $attendances->where('status', 'leave')->count() }}</div>
                <div class="stat-label">On Leave</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('sms.attendance.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date', today()->format('Y-m-d')) }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="medical_excuse" {{ request('status') == 'medical_excuse' ? 'selected' : '' }}>Medical Excuse</option>
                            <option value="official_leave" {{ request('status') == 'official_leave' ? 'selected' : '' }}>Official Leave</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->batch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="daily" {{ request('type', 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Batch</th>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Reasons</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td>
                                        <strong>{{ $attendance->student->full_name ?? 'Unknown Student' }}</strong>
                                    </td>
                                    <td>{{ $attendance->student->student_id ?? 'N/A' }}</td>
                                    <td>
                                        @if($attendance->student->batch)
                                            <span class="badge bg-info">{{ $attendance->student->batch->batch_name }}</span>
                                        @else
                                            <span class="text-muted">No Batch</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance->date->format('M d, Y') }}</td>
                                    <td>
                                        @if($attendance->check_in_time)
                                            {{ $attendance->check_in_time->format('h:i A') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_out_time)
                                            {{ $attendance->check_out_time->format('h:i A') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->status === 'present')
                                            <span class="badge bg-success">Present</span>
                                        @elseif($attendance->status === 'absent')
                                            <span class="badge bg-danger">Absent</span>
                                        @elseif($attendance->status === 'late')
                                            <span class="badge bg-warning">Late</span>
                                        @elseif($attendance->status === 'leave')
                                            <span class="badge bg-info">On Leave</span>
                                        @elseif($attendance->status === 'medical_excuse')
                                            <span class="badge bg-secondary">Medical Excuse</span>
                                        @else
                                            <span class="badge bg-primary">Official Leave</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->reasons)
                                            <small class="text-muted">{{ Str::limit($attendance->reasons, 30) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-action" title="View Details" onclick="viewAttendance({{ $attendance->id }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-action" title="Edit" onclick="editAttendance({{ $attendance->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
                                            <h5>No attendance records found</h5>
                                            <p>No attendance records match your current filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($attendances->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Make functions global to ensure they're accessible
        window.viewAttendance = function(attendanceId) {
            console.log('View attendance called with ID:', attendanceId);
            // For now, show an alert. You can implement a modal or redirect to a detail page
            alert('View attendance details for ID: ' + attendanceId + '\n\nThis feature can be implemented with a modal or dedicated view page.');
        };

        window.editAttendance = function(attendanceId) {
            console.log('Edit attendance called with ID:', attendanceId);
            // For now, show an alert. You can implement a modal or redirect to an edit page
            alert('Edit attendance for ID: ' + attendanceId + '\n\nThis feature can be implemented with a modal or dedicated edit page.');
        };

        // Test if functions are loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Attendance functions loaded successfully');
        });
    </script>
</body>
</html>