<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Mark Attendance</title>
    
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

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .attendance-status {
            min-width: 120px;
        }

        .time-input {
            width: 100px;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.attendance.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Mark Attendance
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
                <h2 class="mb-1">Mark Attendance</h2>
                <p class="text-muted mb-0">Mark attendance for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
            </div>
            <div>
                <a href="{{ route('sms.attendance.monthly') }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-calendar3 me-2"></i>Monthly View
                </a>
                <a href="{{ route('sms.attendance.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Attendance
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('sms.attendance.mark') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $date }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->batch_name }}
                                </option>
                            @endforeach
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

        <!-- Attendance Form -->
        <form action="{{ route('sms.attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Student ID</th>
                                    <th>Batch</th>
                                    <th class="attendance-status">Status</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Reasons</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    @php
                                        $existingAttendance = $existingAttendance[$student->id] ?? null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $student->full_name }}</strong>
                                        </td>
                                        <td>{{ $student->student_id }}</td>
                                        <td>
                                            @if($student->batch)
                                                <span class="badge bg-info">{{ $student->batch->batch_name }}</span>
                                            @else
                                                <span class="text-muted">No Batch</span>
                                            @endif
                                        </td>
                                        <td class="attendance-status">
                                            <select name="attendance[{{ $student->id }}][status]" class="form-select form-select-sm" required>
                                                <option value="present" {{ ($existingAttendance && $existingAttendance->status === 'present') ? 'selected' : '' }}>Present</option>
                                                <option value="absent" {{ ($existingAttendance && $existingAttendance->status === 'absent') ? 'selected' : '' }}>Absent</option>
                                                <option value="late" {{ ($existingAttendance && $existingAttendance->status === 'late') ? 'selected' : '' }}>Late</option>
                                                <option value="leave" {{ ($existingAttendance && $existingAttendance->status === 'leave') ? 'selected' : '' }}>On Leave</option>
                                                <option value="medical_excuse" {{ ($existingAttendance && $existingAttendance->status === 'medical_excuse') ? 'selected' : '' }}>Medical Excuse</option>
                                                <option value="official_leave" {{ ($existingAttendance && $existingAttendance->status === 'official_leave') ? 'selected' : '' }}>Official Leave</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="time" name="attendance[{{ $student->id }}][check_in_time]" 
                                                   class="form-control form-control-sm time-input" 
                                                   value="{{ $existingAttendance && $existingAttendance->check_in_time ? \Carbon\Carbon::parse($existingAttendance->check_in_time)->format('H:i') : '' }}">
                                        </td>
                                        <td>
                                            <input type="time" name="attendance[{{ $student->id }}][check_out_time]" 
                                                   class="form-control form-control-sm time-input" 
                                                   value="{{ $existingAttendance && $existingAttendance->check_out_time ? \Carbon\Carbon::parse($existingAttendance->check_out_time)->format('H:i') : '' }}">
                                        </td>
                                        <td>
                                            <input type="text" name="attendance[{{ $student->id }}][reasons]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Reasons..." 
                                                   value="{{ $existingAttendance ? $existingAttendance->reasons : '' }}">
                                        </td>
                                    </tr>
                                    <input type="hidden" name="attendance[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-people display-4 d-block mb-3"></i>
                                                <h5>No students found</h5>
                                                <p>No students match your current filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($students->count() > 0)
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Attendance
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-fill check-in time for present/late status
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelects = document.querySelectorAll('select[name*="[status]"]');
            
            statusSelects.forEach(function(select) {
                select.addEventListener('change', function() {
                    const row = this.closest('tr');
                    const checkInInput = row.querySelector('input[name*="[check_in_time]"]');
                    const checkOutInput = row.querySelector('input[name*="[check_out_time]"]');
                    
                    if (this.value === 'present' || this.value === 'late') {
                        if (!checkInInput.value) {
                            checkInInput.value = '08:00'; // Default check-in time
                        }
                        if (!checkOutInput.value) {
                            checkOutInput.value = '17:00'; // Default check-out time
                        }
                    } else if (this.value === 'absent') {
                        checkInInput.value = '';
                        checkOutInput.value = '';
                    }
                });
            });
        });
    </script>
</body>
</html>

