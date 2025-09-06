<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Create Notification Template</title>
    
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
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .btn-primary {
            background: var(--primary-blue);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #3d6bff;
            transform: translateY(-1px);
        }

        .variable-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.7rem;
            margin: 0.2rem;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .variable-badge:hover {
            background: #dee2e6;
            transform: translateY(-1px);
        }

        .preview-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.notification-templates.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Create Notification Template
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('job-portal.dashboard') }}">
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
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="form-card">
                    <h4 class="mb-4">
                        <i class="bi bi-envelope me-2"></i>Create Notification Template
                    </h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('job-portal.notification-templates.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Template Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name') }}" required>
                                    <div class="form-text">e.g., Application Status Update - Email</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="trigger_event" class="form-label">Trigger Event</label>
                            <select class="form-select" id="trigger_event" name="trigger_event" required>
                                <option value="">Select Trigger Event</option>
                                @foreach($triggerEvents as $key => $label)
                                    <option value="{{ $key }}" {{ old('trigger_event') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" id="subject-field" style="display: none;">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" 
                                   value="{{ old('subject') }}">
                            <div class="form-text">Email subject line (for email templates only)</div>
                        </div>

                        <div class="mb-3">
                            <label for="body" class="form-label">Message Body</label>
                            <textarea class="form-control" id="body" name="body" rows="8" 
                                      required>{{ old('body') }}</textarea>
                            <div class="form-text">Use variables below to personalize messages</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Available Variables</label>
                            <div class="row">
                                @foreach($variables as $key => $label)
                                    <div class="col-md-3">
                                        <span class="variable-badge" onclick="insertVariable('{{ $key }}')">
                                            {{ $key }} - {{ $label }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text">Click on variables to insert them into your message</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Template
                                </label>
                                <div class="form-text">Uncheck to disable this template</div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="preview-card">
                            <h6 class="mb-3">
                                <i class="bi bi-eye me-2"></i>Preview
                            </h6>
                            <div id="preview-content">
                                <p class="text-muted">Enter template content to see preview</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('job-portal.notification-templates.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Create Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show/hide subject field based on type
        document.getElementById('type').addEventListener('change', function() {
            const subjectField = document.getElementById('subject-field');
            if (this.value === 'email') {
                subjectField.style.display = 'block';
                document.getElementById('subject').required = true;
            } else {
                subjectField.style.display = 'none';
                document.getElementById('subject').required = false;
            }
        });

        // Insert variable into message body
        function insertVariable(variable) {
            const bodyField = document.getElementById('body');
            const cursorPos = bodyField.selectionStart;
            const textBefore = bodyField.value.substring(0, cursorPos);
            const textAfter = bodyField.value.substring(cursorPos);
            const variableText = '{{' + variable + '}}';
            
            bodyField.value = textBefore + variableText + textAfter;
            bodyField.focus();
            bodyField.setSelectionRange(cursorPos + variableText.length, cursorPos + variableText.length);
            
            updatePreview();
        }

        // Update preview
        function updatePreview() {
            const body = document.getElementById('body').value;
            const subject = document.getElementById('subject').value;
            const type = document.getElementById('type').value;
            const previewContent = document.getElementById('preview-content');
            
            let preview = '';
            
            if (type === 'email' && subject) {
                preview += `<strong>Subject:</strong> ${subject}<br><br>`;
            }
            
            if (body) {
                // Replace variables with sample data
                let previewBody = body
                    .replace(/\{\{student_name\}\}/g, 'Ahmed Ali')
                    .replace(/\{\{application_number\}\}/g, 'NS-2025-001')
                    .replace(/\{\{status\}\}/g, 'Approved')
                    .replace(/\{\{message\}\}/g, 'Your application has been reviewed and approved.')
                    .replace(/\{\{date\}\}/g, new Date().toLocaleDateString())
                    .replace(/\{\{interview_date\}\}/g, 'March 15, 2025')
                    .replace(/\{\{venue\}\}/g, 'National Service Training Center')
                    .replace(/\{\{batch_name\}\}/g, 'Batch 2025-01')
                    .replace(/\{\{batch_code\}\}/g, 'NS-2025-01')
                    .replace(/\{\{start_date\}\}/g, 'April 1, 2025');
                
                preview += `<strong>Message:</strong><br>${previewBody.replace(/\n/g, '<br>')}`;
            }
            
            previewContent.innerHTML = preview || '<p class="text-muted">Enter template content to see preview</p>';
        }

        // Update preview on input
        document.getElementById('body').addEventListener('input', updatePreview);
        document.getElementById('subject').addEventListener('input', updatePreview);
        document.getElementById('type').addEventListener('change', updatePreview);

        // Initial preview update
        updatePreview();
    </script>
</body>
</html>
