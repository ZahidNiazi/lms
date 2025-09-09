<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Edit Communication</title>
    
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

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(79, 124, 255, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #3d6bff;
            border-color: #3d6bff;
        }

        .btn-secondary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-briefcase me-2"></i>Job Portal - Edit Communication
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
                <h1 class="h3 mb-0">Edit Communication</h1>
                <p class="text-muted">Update communication details</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-card">
                    <form method="POST" action="{{ route('job-portal.communications.update', $communication->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="application_id" class="form-label">Student Application</label>
                                <select class="form-select @error('application_id') is-invalid @enderror" 
                                        id="application_id" name="application_id" required>
                                    <option value="">Select Application...</option>
                                    @foreach($applications as $app)
                                        <option value="{{ $app->id }}" 
                                                {{ old('application_id', $communication->application_id) == $app->id ? 'selected' : '' }}>
                                            {{ $app->student->first_name }} {{ $app->student->last_name }} 
                                            ({{ $app->application_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('application_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Communication Type</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">Select Type...</option>
                                    <option value="email" {{ old('type', $communication->type) == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="sms" {{ old('type', $communication->type) == 'sms' ? 'selected' : '' }}>SMS</option>
                                    <option value="whatsapp" {{ old('type', $communication->type) == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                    <option value="notification" {{ old('type', $communication->type) == 'notification' ? 'selected' : '' }}>Notification</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" 
                                   value="{{ old('subject', $communication->subject) }}" 
                                   placeholder="Enter communication subject...">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="6" 
                                      placeholder="Enter your message..." required>{{ old('message', $communication->message) }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">Select Status...</option>
                                <option value="draft" {{ old('status', $communication->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ old('status', $communication->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="delivered" {{ old('status', $communication->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="acknowledged" {{ old('status', $communication->status) == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                <option value="failed" {{ old('status', $communication->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('job-portal.communications.show', $communication->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update Communication
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Communication Info -->
            <div class="col-lg-4">
                <div class="form-card">
                    <h5 class="mb-4">
                        <i class="bi bi-info-circle me-2"></i>Communication Information
                    </h5>
                    
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        <span class="text-muted">{{ $communication->created_at->format('M d, Y h:i A') }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <span class="text-muted">{{ $communication->updated_at->format('M d, Y h:i A') }}</span>
                    </div>

                    @if($communication->sender)
                    <div class="mb-3">
                        <strong>Created By:</strong><br>
                        <span class="text-muted">{{ $communication->sender->name }}</span>
                    </div>
                    @endif

                    @if($communication->acknowledged_at)
                    <div class="mb-3">
                        <strong>Acknowledged:</strong><br>
                        <span class="text-muted">{{ $communication->acknowledged_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



