<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storage Settings - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .storage-option { display: none; }
        .storage-option.active { display: block; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('settings.index') }}">
                <i class="bi bi-arrow-left me-2"></i>Storage Settings
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
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Storage Settings</h4>
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('settings.storage.update') }}" method="POST">
                            @csrf
                            
                            <!-- Storage Driver Selection -->
                            <div class="mb-4">
                                <label class="form-label">Storage Driver <span class="text-danger">*</span></label>
                                <select name="storage_driver" id="storage_driver" class="form-select" required onchange="toggleStorageOptions()">
                                    <option value="">Select Storage Driver</option>
                                    <option value="local" {{ old('storage_driver', 'local') == 'local' ? 'selected' : '' }}>Local Storage</option>
                                    <option value="aws_s3" {{ old('storage_driver') == 'aws_s3' ? 'selected' : '' }}>AWS S3</option>
                                    <option value="wasabi" {{ old('storage_driver') == 'wasabi' ? 'selected' : '' }}>Wasabi</option>
                                </select>
                            </div>

                            <!-- Max Upload Size -->
                            <div class="mb-4">
                                <label class="form-label">Max Upload Size (KB) <span class="text-danger">*</span></label>
                                <input type="number" name="max_upload_size" class="form-control" value="{{ old('max_upload_size', 2048) }}" required min="1">
                                <small class="form-text text-muted">Maximum file size in kilobytes</small>
                            </div>

                            <!-- Allowed File Types -->
                            <div class="mb-4">
                                <label class="form-label">Allowed File Types <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="allowed_file_types[]" value="csv" class="form-check-input" id="csv" {{ in_array('csv', old('allowed_file_types', ['csv', 'jpeg', 'jpg', 'pdf', 'png', 'xls', 'xlsx'])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="csv">CSV</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="allowed_file_types[]" value="jpeg" class="form-check-input" id="jpeg" {{ in_array('jpeg', old('allowed_file_types', ['csv', 'jpeg', 'jpg', 'pdf', 'png', 'xls', 'xlsx'])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jpeg">JPEG</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="allowed_file_types[]" value="jpg" class="form-check-input" id="jpg" {{ in_array('jpg', old('allowed_file_types', ['csv', 'jpeg', 'jpg', 'pdf', 'png', 'xls', 'xlsx'])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jpg">JPG</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="allowed_file_types[]" value="pdf" class="form-check-input" id="pdf" {{ in_array('pdf', old('allowed_file_types', ['csv', 'jpeg', 'jpg', 'pdf', 'png', 'xls', 'xlsx'])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pdf">PDF</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="allowed_file_types[]" value="png" class="form-check-input" id="png" {{ in_array('png', old('allowed_file_types', ['csv', 'jpeg', 'jpg', 'pdf', 'png', 'xls', 'xlsx'])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="png">PNG</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="allowed_file_types[]" value="xls" class="form-check-input" id="xls" {{ in_array('xls', old('allowed_file_types', ['csv', 'jpeg', 'jpg', 'pdf', 'png', 'xls', 'xlsx'])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="xls">XLS</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="allowed_file_types[]" value="xlsx" class="form-check-input" id="xlsx" {{ in_array('xlsx', old('allowed_file_types', ['csv', 'jpeg', 'jpg', 'pdf', 'png', 'xls', 'xlsx'])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="xlsx">XLSX</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- AWS S3 Settings -->
                            <div id="aws_s3_options" class="storage-option">
                                <h5 class="mb-3">AWS S3 Configuration</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">S3 Key <span class="text-danger">*</span></label>
                                        <input type="text" name="s3_key" class="form-control" value="{{ old('s3_key') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">S3 Region <span class="text-danger">*</span></label>
                                        <input type="text" name="s3_region" class="form-control" value="{{ old('s3_region') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">S3 URL <span class="text-danger">*</span></label>
                                        <input type="url" name="s3_url" class="form-control" value="{{ old('s3_url') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">S3 Secret Key <span class="text-danger">*</span></label>
                                        <input type="password" name="s3_secret_key" class="form-control" value="{{ old('s3_secret_key') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">S3 Bucket <span class="text-danger">*</span></label>
                                        <input type="text" name="s3_bucket" class="form-control" value="{{ old('s3_bucket') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">S3 Endpoint <span class="text-danger">*</span></label>
                                        <input type="url" name="s3_endpoint" class="form-control" value="{{ old('s3_endpoint') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Wasabi Settings -->
                            <div id="wasabi_options" class="storage-option">
                                <h5 class="mb-3">Wasabi Configuration</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wasabi Key <span class="text-danger">*</span></label>
                                        <input type="text" name="wasabi_key" class="form-control" value="{{ old('wasabi_key') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wasabi Secret <span class="text-danger">*</span></label>
                                        <input type="password" name="wasabi_secret" class="form-control" value="{{ old('wasabi_secret') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wasabi Region <span class="text-danger">*</span></label>
                                        <input type="text" name="wasabi_region" class="form-control" value="{{ old('wasabi_region') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wasabi Bucket <span class="text-danger">*</span></label>
                                        <input type="text" name="wasabi_bucket" class="form-control" value="{{ old('wasabi_bucket') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wasabi URL <span class="text-danger">*</span></label>
                                        <input type="url" name="wasabi_url" class="form-control" value="{{ old('wasabi_url') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wasabi Root</label>
                                        <input type="text" name="wasabi_root" class="form-control" value="{{ old('wasabi_root') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Storage Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleStorageOptions() {
            const driver = document.getElementById('storage_driver').value;
            
            // Hide all options
            document.getElementById('aws_s3_options').classList.remove('active');
            document.getElementById('wasabi_options').classList.remove('active');
            
            // Show selected option
            if (driver === 'aws_s3') {
                document.getElementById('aws_s3_options').classList.add('active');
            } else if (driver === 'wasabi') {
                document.getElementById('wasabi_options').classList.add('active');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleStorageOptions();
        });
    </script>
</body>
</html>
