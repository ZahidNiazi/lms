<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Leave Management</title>

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
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Leave Management
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
                <h2 class="mb-1">Leave Management</h2>
                <p class="text-muted mb-0">Manage student leave applications and approvals</p>
            </div>
            <div>
                <a href="{{ route('sms.leave-types.index') }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-plus-circle me-2"></i>Create Leave Type
                </a>
                <a href="{{ route('sms.leave-applications.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>New Leave Application
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('sms.leaves.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-select">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
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

        <!-- Leaves Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Leave Type</th>
                                <th>Applied On</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $leave->student->name ?? 'Unknown Student' }}</strong>
                                            <br><small class="text-muted">ID: {{ $leave->student->id ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $leave->leaveType->name ?? 'Unknown Type' }}</td>
                                    <td>{{ $leave->applied_on->format('M d, Y') }}</td>
                                    <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $leave->total_days }} days</span>
                                    </td>
                                    <td>
                                        @if($leave->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($leave->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sms.leave-applications.show', $leave->id) }}" class="btn btn-outline-primary btn-action" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($leave->status === 'pending')
                                                <form method="POST" action="{{ route('sms.leaves.approve', $leave->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-action" title="Approve" onclick="return confirm('Are you sure you want to approve this leave application?')">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-outline-danger btn-action" title="Reject" onclick="rejectLeave({{ $leave->id }})">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
                                            <h5>No leave applications found</h5>
                                            <p>No leave applications match your current filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($leaves->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $leaves->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function rejectLeave(leaveId) {
            const reason = prompt('Please provide a reason for rejection:');
            if (reason && reason.trim() !== '') {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('sms.leaves.index') }}/${leaveId}/reject`;

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add rejection reason
                const reasonField = document.createElement('input');
                reasonField.type = 'hidden';
                reasonField.name = 'rejection_reason';
                reasonField.value = reason;
                form.appendChild(reasonField);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
