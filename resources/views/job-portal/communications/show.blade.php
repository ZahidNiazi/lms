<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Communication Details</title>
    
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

        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }

        .info-value {
            color: #212529;
            flex: 1;
            text-align: right;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-sent {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-acknowledged {
            background-color: #e8f5e8;
            color: #2e7d32;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .type-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .type-email {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .type-sms {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .type-whatsapp {
            background-color: #e8f5e8;
            color: #2e7d32;
        }

        .type-notification {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .message-content {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            border-left: 4px solid var(--primary-blue);
            margin: 1rem 0;
        }

        .student-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-briefcase me-2"></i>Job Portal - Communication Details
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('job-portal.communications.index') }}">
                    <i class="bi bi-arrow-left me-1"></i>Back to Communications
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0">Communication Details</h1>
                <p class="text-muted">View detailed information about this communication</p>
            </div>
        </div>

        <div class="row">
            <!-- Communication Details -->
            <div class="col-lg-8">
                <div class="detail-card">
                    <h5 class="mb-4">
                        <i class="bi bi-envelope me-2"></i>Communication Information
                    </h5>
                    
                    <div class="info-row">
                        <span class="info-label">Type:</span>
                        <span class="info-value">
                            <span class="type-badge type-{{ $communication->type }}">
                                <i class="bi bi-{{ $communication->type === 'email' ? 'envelope' : ($communication->type === 'sms' ? 'phone' : 'chat') }} me-1"></i>
                                {{ ucfirst($communication->type) }}
                            </span>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $communication->status }}">
                                <i class="bi bi-{{ $communication->status === 'acknowledged' ? 'check-circle' : ($communication->status === 'sent' ? 'send' : 'clock') }} me-1"></i>
                                {{ ucfirst($communication->status) }}
                            </span>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Subject:</span>
                        <span class="info-value">{{ $communication->subject ?? 'No subject' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Sent By:</span>
                        <span class="info-value">
                            {{ $communication->sender ? $communication->sender->name : 'System' }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Sent At:</span>
                        <span class="info-value">
                            {{ $communication->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    @if($communication->acknowledged_at)
                    <div class="info-row">
                        <span class="info-label">Acknowledged At:</span>
                        <span class="info-value">
                            {{ $communication->acknowledged_at->format('M d, Y h:i A') }}
                        </span>
                    </div>
                    @endif

                    @if($communication->message)
                    <div class="message-content">
                        <h6 class="mb-3">
                            <i class="bi bi-chat-text me-2"></i>Message Content
                        </h6>
                        <div class="text-muted">
                            {!! nl2br(e($communication->message)) !!}
                        </div>
                    </div>
                    @endif

                    @if($communication->attachments)
                    <div class="mt-3">
                        <h6 class="mb-3">
                            <i class="bi bi-paperclip me-2"></i>Attachments
                        </h6>
                        <div class="list-group">
                            @foreach(json_decode($communication->attachments, true) as $attachment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-file-earmark me-2"></i>
                                    {{ $attachment['name'] ?? 'Unknown file' }}
                                </div>
                                <a href="{{ $attachment['url'] ?? '#' }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Student Information -->
            <div class="col-lg-4">
                <div class="detail-card">
                    <h5 class="mb-4">
                        <i class="bi bi-person me-2"></i>Student Information
                    </h5>
                    
                    <div class="student-info">
                        <div class="info-row">
                            <span class="info-label">Name:</span>
                            <span class="info-value">
                                {{ $communication->application->student->first_name }} {{ $communication->application->student->last_name }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Application #:</span>
                            <span class="info-value">
                                {{ $communication->application->application_number }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">
                                {{ $communication->application->student->email }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">
                                {{ $communication->application->student->phone ?? 'Not provided' }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value">
                                <span class="badge bg-{{ $communication->application->status === 'approved' ? 'success' : ($communication->application->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $communication->application->status)) }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('job-portal.applications.show', $communication->application->id) }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-eye me-2"></i>View Application
                        </a>
                    </div>
                </div>

                <!-- Actions -->
                <div class="detail-card">
                    <h5 class="mb-4">
                        <i class="bi bi-gear me-2"></i>Actions
                    </h5>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('job-portal.communications.edit', $communication->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil me-2"></i>Edit Communication
                        </a>
                        
                        @if($communication->status !== 'acknowledged')
                        <form method="POST" action="{{ route('job-portal.communications.resend', $communication->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="bi bi-arrow-repeat me-2"></i>Resend
                            </button>
                        </form>
                        @endif
                        
                        <form method="POST" action="{{ route('job-portal.communications.destroy', $communication->id) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this communication?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



