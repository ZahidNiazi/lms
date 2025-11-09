<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Leave Application Details</title>
    
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

        .info-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .info-item {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }

        .info-value {
            color: #6c757d;
        }

        .badge {
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.leaves.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Leave Application Details
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
                <h2 class="mb-1">Leave Application Details</h2>
                <p class="text-muted mb-0">Complete information about this leave application</p>
            </div>
            <a href="{{ route('sms.leaves.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Leaves
            </a>
        </div>

        <div class="row">
            <!-- Application Information -->
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-calendar-event me-2"></i>Application Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Student:</span>
                                <span class="info-value">{{ $leave->student->name ?? 'Unknown Student' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Student ID:</span>
                                <span class="info-value">{{ $leave->student->student_id ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Leave Type:</span>
                                <span class="info-value">{{ $leave->leaveType->name ?? 'Unknown Type' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Applied On:</span>
                                <span class="info-value">{{ $leave->applied_on->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Start Date:</span>
                                <span class="info-value">{{ $leave->start_date->format('M d, Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">End Date:</span>
                                <span class="info-value">{{ $leave->end_date->format('M d, Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Total Days:</span>
                                <span class="info-value">
                                    <span class="badge bg-info">{{ $leave->total_days }} days</span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status:</span>
                                <span class="info-value">
                                    @if($leave->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($leave->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leave Reasons -->
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-chat-text me-2"></i>Leave Reasons
                    </h5>
                    <p class="mb-0">{{ $leave->leave_reasons }}</p>
                </div>

                <!-- Approval Information -->
                @if($leave->status !== 'pending')
                    <div class="info-section">
                        <h5 class="section-title">
                            <i class="bi bi-check-circle me-2"></i>Approval Information
                        </h5>
                        
                        @if($leave->status === 'approved')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Approved By:</span>
                                        <span class="info-value">{{ $leave->approvedBy->name ?? 'Unknown' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Approved At:</span>
                                        <span class="info-value">{{ $leave->approved_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($leave->status === 'rejected')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Rejected By:</span>
                                        <span class="info-value">{{ $leave->rejectedBy->name ?? 'Unknown' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Rejected At:</span>
                                        <span class="info-value">{{ $leave->rejected_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($leave->rejection_reason)
                                <div class="info-item">
                                    <span class="info-label">Rejection Reason:</span>
                                    <span class="info-value">{{ $leave->rejection_reason }}</span>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            <!-- Actions Sidebar -->
            <div class="col-md-4">
                <div class="info-section">
                    <h5 class="section-title">
                        <i class="bi bi-gear me-2"></i>Actions
                    </h5>
                    
                    @if($leave->status === 'pending')
                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('sms.leaves.approve', $leave->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this leave application?')">
                                    <i class="bi bi-check-circle me-2"></i>Approve Leave
                                </button>
                            </form>
                            
                            <button type="button" class="btn btn-danger w-100" onclick="rejectLeave({{ $leave->id }})">
                                <i class="bi bi-x-circle me-2"></i>Reject Leave
                            </button>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            This leave application has already been {{ $leave->status }}.
                        </div>
                    @endif
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('sms.leaves.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Leaves
                        </a>
                    </div>
                </div>
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

