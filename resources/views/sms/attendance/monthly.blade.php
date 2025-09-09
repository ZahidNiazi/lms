<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Monthly Attendance View</title>
    
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
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .card-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid #dee2e6;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        
        .card-title {
            color: var(--primary-blue);
            font-weight: 600;
            margin: 0;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .btn-primary:hover {
            background: #3d6bff;
            border-color: #3d6bff;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-blue), var(--purple));
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem 0.75rem;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(79, 124, 255, 0.05);
            transform: scale(1.01);
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(79, 124, 255, 0.25);
        }
        
        .attendance-present { background-color: var(--success-green) !important; color: white; }
        .attendance-absent { background-color: var(--danger-red) !important; color: white; }
        .attendance-late { background-color: var(--warning-yellow) !important; color: black; }
        .attendance-leave { background-color: #17a2b8 !important; color: white; }
        .attendance-medical-excuse { background-color: var(--purple) !important; color: white; }
        .attendance-official-leave { background-color: #fd7e14 !important; color: white; }
        
        .bg-light {
            background-color: rgba(248, 249, 250, 0.8) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-blue) !important;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Monthly Attendance View
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
                <h2 class="mb-1">Monthly Attendance Calendar</h2>
                <p class="text-muted mb-0">View and manage monthly attendance records</p>
            </div>
            <div>
                <a href="{{ route('sms.attendance.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Attendance
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attendance Calendar</h5>
                    </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form method="GET" class="d-flex gap-2">
                                <input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()">
                                <select name="batch_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Batches</option>
                                    @foreach($batches as $batch)
                                        <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>
                                            {{ $batch->batch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>

                    @if($students->count() > 0)
                        <!-- Attendance Calendar -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 200px;">Student</th>
                                        <th class="text-center" style="width: 100px;">Batch</th>
                                        @php
                                            $totalDays = \Carbon\Carbon::parse($month)->daysInMonth;
                                            $startDate = \Carbon\Carbon::parse($month . '-01');
                                        @endphp
                                        @for($day = 1; $day <= $totalDays; $day++)
                                            @php
                                                $date = $startDate->copy()->addDays($day - 1);
                                                $isWeekend = $date->isWeekend();
                                                $isToday = $date->isToday();
                                            @endphp
                                            <th class="text-center {{ $isWeekend ? 'bg-light' : '' }} {{ $isToday ? 'bg-primary text-white' : '' }}" 
                                                style="width: 30px; font-size: 0.8rem;">
                                                {{ $day }}
                                                <br>
                                                <small>{{ $date->format('D') }}</small>
                                            </th>
                                        @endfor
                                        <th class="text-center" style="width: 80px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($student->photo)
                                                        <img src="{{ asset('storage/' . $student->photo) }}" 
                                                             alt="{{ $student->full_name }}" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                             style="width: 32px; height: 32px;">
                                                            <i class="bi bi-person text-white" style="font-size: 0.8rem;"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-medium">{{ $student->full_name }}</div>
                                                        <small class="text-muted">{{ $student->student_id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($student->batch)
                                                    <span class="badge bg-info">{{ $student->batch->batch_name }}</span>
                                                @else
                                                    <span class="text-muted">No Batch</span>
                                                @endif
                                            </td>
                                            @php
                                                $totalPresent = 0;
                                                $totalDays = \Carbon\Carbon::parse($month)->daysInMonth;
                                            @endphp
                                            @for($day = 1; $day <= $totalDays; $day++)
                                                @php
                                                    $date = \Carbon\Carbon::parse($month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                                                    // Try both date formats since the grouped data might have different formats
                                                    $attendanceData = $attendances[$student->id][$date->format('Y-m-d')] ?? 
                                                                     $attendances[$student->id][$date->format('Y-m-d H:i:s')] ?? 
                                                                     $attendances[$student->id][$date->format('Y-m-d') . ' 00:00:00'] ?? null;
                                                    $attendance = null;
                                                    if (is_array($attendanceData) && count($attendanceData) > 0) {
                                                        $attendance = (object) $attendanceData[0]; // Convert array to object
                                                    }
                                                @endphp
                                                <td class="text-center">
                                                    @if($attendance)
                                                        @php
                                                            $statusClass = 'attendance-' . str_replace('_', '-', $attendance->status);
                                                            $statusLetter = match($attendance->status) {
                                                                'present' => 'P',
                                                                'absent' => 'A',
                                                                'late' => 'L',
                                                                'leave' => 'LV',
                                                                'medical_excuse' => 'ME',
                                                                'official_leave' => 'OL',
                                                                default => '?'
                                                            };
                                                            if ($attendance->status === 'present') {
                                                                $totalPresent++;
                                                            }
                                                        @endphp
                                                        <span class="badge {{ $statusClass }}" title="{{ ucfirst(str_replace('_', ' ', $attendance->status)) }}">
                                                            {{ $statusLetter }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endfor
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $totalPresent }}/{{ $totalDays }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Legend -->
                        <div class="mt-4">
                            <h6>Legend:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge attendance-present">P - Present</span>
                                <span class="badge attendance-absent">A - Absent</span>
                                <span class="badge attendance-late">L - Late</span>
                                <span class="badge attendance-leave">LV - Leave</span>
                                <span class="badge attendance-medical-excuse">ME - Medical Excuse</span>
                                <span class="badge attendance-official-leave">OL - Official Leave</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-calendar-x display-4"></i>
                                <h5 class="mt-3">No Students Found</h5>
                                <p>No students are available for the selected criteria.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
