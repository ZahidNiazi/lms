<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Send Message</title>
    
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
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(79, 124, 255, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3d6bff;
            border-color: #3d6bff;
            transform: translateY(-1px);
        }

        .btn-secondary {
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }

        .student-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .communication-type {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .communication-type:hover {
            border-color: var(--primary-blue);
            background-color: #f8f9ff;
        }

        .communication-type.selected {
            border-color: var(--primary-blue);
            background-color: #f0f4ff;
        }

        .communication-type input[type="radio"] {
            display: none;
        }

        .communication-type .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .communication-type.email .icon { color: var(--primary-blue); }
        .communication-type.sms .icon { color: var(--success-green); }
        .communication-type.whatsapp .icon { color: #25d366; }
        .communication-type.notification .icon { color: var(--warning-yellow); }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.dashboard') }}">
                <i class="bi bi-briefcase me-2"></i>Job Portal Admin
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('job-portal.dashboard') }}">
                    <i class="bi bi-house me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="{{ route('job-portal.communications.index') }}">
                    <i class="bi bi-chat-dots me-1"></i>Communications
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="form-card">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-send me-3" style="font-size: 2rem; color: var(--primary-blue);"></i>
                        <div>
                            <h2 class="mb-0">Send Message to Student</h2>
                            <p class="text-muted mb-0">Send a communication to a student about their application</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('job-portal.communications.store') }}">
                        @csrf
                        
                        <!-- Student Selection -->
                        <div class="mb-4">
                            <label for="application_id" class="form-label">Select Student</label>
                            <select class="form-select" id="application_id" name="application_id" required>
                                <option value="">Choose a student...</option>
                                @foreach($applications as $application)
                                    <option value="{{ $application->id }}" 
                                            {{ $selectedApplicationId == $application->id ? 'selected' : '' }}
                                            data-student-name="{{ $application->student->profile ? $application->student->profile->first_name . ' ' . $application->student->profile->last_name : $application->student->name }}"
                                            data-student-email="{{ $application->student->email }}"
                                            data-student-phone="{{ $application->student->profile->mobile_no ?? 'N/A' }}"
                                            data-application-number="{{ $application->application_number }}">
                                        {{ $application->application_number }} - {{ $application->student->profile ? $application->student->profile->first_name . ' ' . $application->student->profile->last_name : $application->student->name }}
                                        ({{ ucfirst(str_replace('_', ' ', $application->status)) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Student Info Display -->
                        <div id="studentInfo" class="student-info" style="display: none;">
                            <h6 class="mb-2">Student Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Name:</strong> <span id="studentName">-</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Email:</strong> <span id="studentEmail">-</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Phone:</strong> <span id="studentPhone">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Communication Type -->
                        <div class="mb-4">
                            <label class="form-label">Communication Type</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="communication-type email" for="type_email">
                                        <input type="radio" id="type_email" name="type" value="email" required>
                                        <div class="text-center">
                                            <i class="bi bi-envelope icon"></i>
                                            <div class="fw-semibold">Email</div>
                                            <small class="text-muted">Send via email</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="communication-type notification" for="type_notification">
                                        <input type="radio" id="type_notification" name="type" value="notification" required>
                                        <div class="text-center">
                                            <i class="bi bi-bell icon"></i>
                                            <div class="fw-semibold">Notification</div>
                                            <small class="text-muted">Show in student portal</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Subject (for email) -->
                        <div class="mb-4" id="subjectField" style="display: none;">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter message subject">
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="6" 
                                      placeholder="Enter your message here..." required></textarea>
                            <div class="form-text">
                                <span id="charCount">0</span> characters
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                            <a href="{{ route('job-portal.communications.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Student selection handler
        document.getElementById('application_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const studentInfo = document.getElementById('studentInfo');
            
            if (this.value) {
                document.getElementById('studentName').textContent = selectedOption.dataset.studentName;
                document.getElementById('studentEmail').textContent = selectedOption.dataset.studentEmail;
                document.getElementById('studentPhone').textContent = selectedOption.dataset.studentPhone;
                studentInfo.style.display = 'block';
            } else {
                studentInfo.style.display = 'none';
            }
        });

        // Communication type handler
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all types
                document.querySelectorAll('.communication-type').forEach(type => {
                    type.classList.remove('selected');
                });
                
                // Add selected class to current type
                this.closest('.communication-type').classList.add('selected');
                
                // Show/hide subject field for email
                const subjectField = document.getElementById('subjectField');
                if (this.value === 'email') {
                    subjectField.style.display = 'block';
                    document.getElementById('subject').required = true;
                } else {
                    subjectField.style.display = 'none';
                    document.getElementById('subject').required = false;
                }
            });
        });

        // Character count for message
        document.getElementById('message').addEventListener('input', function() {
            const charCount = document.getElementById('charCount');
            charCount.textContent = this.value.length;
            
            // Update color based on length
            if (this.value.length > 1000) {
                charCount.style.color = 'var(--danger-red)';
            } else if (this.value.length > 500) {
                charCount.style.color = 'var(--warning-yellow)';
            } else {
                charCount.style.color = '#6c757d';
            }
        });

        // Show student info if application is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            const applicationSelect = document.getElementById('application_id');
            if (applicationSelect.value) {
                applicationSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>
