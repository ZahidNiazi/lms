<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Settings - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('settings.index') }}">
                <i class="bi bi-arrow-left me-2"></i>Theme Settings
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('job-portal.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('job-portal.reports.index') }}"><i class="bi bi-graph-up me-2"></i>Reports</a></li>
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
                        <h4 class="card-title mb-4">Theme Customization</h4>
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('settings.theme.update') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Primary Color <span class="text-danger">*</span></label>
                                <input type="color" name="primary_color" class="form-control form-control-color" value="{{ old('primary_color', '#4f7cff') }}" required>
                                <small class="form-text text-muted">Choose the primary color for your application</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="transparent_layout" class="form-check-input" id="transparent_layout" {{ old('transparent_layout') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="transparent_layout">Transparent Layout</label>
                                        <small class="form-text text-muted d-block">Enable transparent sidebar and navigation</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="dark_layout" class="form-check-input" id="dark_layout" {{ old('dark_layout') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dark_layout">Dark Layout</label>
                                        <small class="form-text text-muted d-block">Enable dark mode for the application</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Theme Settings</button>
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