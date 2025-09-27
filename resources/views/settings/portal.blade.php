<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Settings - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('settings.index') }}">
                <i class="bi bi-arrow-left me-2"></i>Portal Settings
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('job-portal.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('job-portal.reports.index') }}"><i class="bi bi-graph-up me-2"></i>Reports</a></li>
                        <li><a class="dropdown-item" href="{{ route('student.login') }}"><i class="bi bi-person me-2"></i>Student Login</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><form method="POST" action="{{ route('job-portal.logout') }}" style="display: inline;"><button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Portal Settings</h4>
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('settings.portal.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Logo Dark</label>
                                    <input type="file" name="logo_dark" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Logo Light</label>
                                    <input type="file" name="logo_light" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Favicon</label>
                                <input type="file" name="favicon" class="form-control" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Title Text <span class="text-danger">*</span></label>
                                <input type="text" name="title_text" class="form-control" value="{{ old('title_text', 'LMS System') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Footer Text</label>
                                <textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Default Language <span class="text-danger">*</span></label>
                                    <select name="default_language" class="form-select" required>
                                        <option value="en" {{ old('default_language', 'en') == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="dv" {{ old('default_language') == 'dv' ? 'selected' : '' }}>Dhivehi</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contact Details</label>
                                <textarea name="contact_details" class="form-control" rows="2">{{ old('contact_details') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="enable_rtl" class="form-check-input" id="enable_rtl" {{ old('enable_rtl') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_rtl">Enable RTL</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="enable_landing_page" class="form-check-input" id="enable_landing_page" {{ old('enable_landing_page') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_landing_page">Enable Landing Page</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="enable_sign_up" class="form-check-input" id="enable_sign_up" {{ old('enable_sign_up') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_sign_up">Enable Sign Up</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="email_verification" class="form-check-input" id="email_verification" {{ old('email_verification') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_verification">Email Verification</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
