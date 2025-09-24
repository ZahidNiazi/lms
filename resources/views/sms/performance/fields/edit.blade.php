<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Edit Performance Field</title>
    
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
                SMS - Edit Performance Field
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
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">
                            <i class="bi bi-pencil me-2"></i>
                            Edit Performance Field
                        </h2>
                        <p class="text-muted mb-0">Update performance field information</p>
                    </div>
                    <div>
                        <a href="{{ route('sms.performance.fields.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Fields
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-gear me-2"></i>
                            Field Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sms.performance.fields.update', $performanceField->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-3">
                                <!-- Field Name -->
                                <div class="col-12">
                                    <label for="name" class="form-label">
                                        Field Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $performanceField->name) }}" 
                                           placeholder="e.g., Technical Skills, Leadership, Communication" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label for="category" class="form-label">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ old('category', $performanceField->category) == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Max Score -->
                                <div class="col-md-6">
                                    <label for="max_score" class="form-label">
                                        Maximum Score <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="max_score" id="max_score" 
                                           class="form-control @error('max_score') is-invalid @enderror" 
                                           value="{{ old('max_score', $performanceField->max_score) }}" 
                                           min="1" max="1000" step="0.01" required>
                                    @error('max_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" rows="4" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Describe what this performance field evaluates...">{{ old('description', $performanceField->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                               {{ old('is_active', $performanceField->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active (Available for use in performance evaluations)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('sms.performance.fields.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Update Field
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Current Field Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Current Field Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Field Name</label>
                            <div class="p-2 bg-light rounded">
                                {{ $performanceField->name }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-primary">{{ $performanceField->category }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Max Score</label>
                            <div class="p-2 bg-light rounded">
                                {{ $performanceField->max_score }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge {{ $performanceField->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $performanceField->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Usage</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-info">{{ $performanceField->performances->count() }} performance records</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage Warning -->
                @if($performanceField->performances->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Usage Warning
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="bi bi-info-circle me-2"></i>
                                This field is currently used in <strong>{{ $performanceField->performances->count() }}</strong> performance records. 
                                Changing the maximum score may affect existing evaluations.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Guidelines -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-lightbulb me-2"></i>
                            Editing Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-semibold">Important Notes:</h6>
                            <ul class="list-unstyled small">
                                <li><i class="bi bi-exclamation-triangle text-warning me-1"></i> Changing max score affects existing records</li>
                                <li><i class="bi bi-exclamation-triangle text-warning me-1"></i> Deactivating hides field from new evaluations</li>
                                <li><i class="bi bi-exclamation-triangle text-warning me-1"></i> Category changes affect field grouping</li>
                            </ul>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-lightbulb me-2"></i>
                            <strong>Tip:</strong> Consider creating a new field instead of modifying heavily used ones to maintain data integrity.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>