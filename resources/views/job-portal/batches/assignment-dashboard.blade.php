<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Batch Assignment Dashboard</title>
    
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

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            text-align: center;
            border-left: 4px solid var(--primary-blue);
            margin-bottom: 1.5rem;
        }

        .stat-card.success { border-left-color: var(--success-green); }
        .stat-card.warning { border-left-color: var(--warning-yellow); }
        .stat-card.danger { border-left-color: var(--danger-red); }
        .stat-card.info { border-left-color: var(--primary-blue); }

        .batch-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
        }

        .batch-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-planning { background: #e3f2fd; color: #1976d2; }
        .status-open { background: #e8f5e8; color: #2e7d32; }
        .status-full { background: #ffebee; color: #c62828; }
        .status-in_progress { background: #f3e5f5; color: #7b1fa2; }
        .status-completed { background: #e0f2f1; color: #00695c; }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.batches.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Batch Assignment Dashboard
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
        <!-- Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card info">
                    <h4 class="text-primary">{{ $stats['total_batches'] }}</h4>
                    <p class="mb-0">Total Batches</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card success">
                    <h4 class="text-success">{{ $stats['active_batches'] }}</h4>
                    <p class="mb-0">Active Batches</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card warning">
                    <h4 class="text-warning">{{ $stats['available_students'] }}</h4>
                    <p class="mb-0">Available Students</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card danger">
                    <h4 class="text-danger">{{ $stats['assignment_rate'] }}%</h4>
                    <p class="mb-0">Assignment Rate</p>
                </div>
            </div>
        </div>

        <!-- Assignment Overview -->
        <div class="row">
            <div class="col-md-6">
                <div class="batch-card">
                    <h5 class="mb-3">
                        <i class="bi bi-people me-2"></i>Assignment Overview
                    </h5>
                    <div class="row">
                        <div class="col-6">
                            <strong>Total Selected:</strong><br>
                            <span class="text-primary">{{ $stats['total_selected'] }}</span>
                        </div>
                        <div class="col-6">
                            <strong>Total Assigned:</strong><br>
                            <span class="text-success">{{ $stats['total_assigned'] }}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $stats['assignment_rate'] }}%">
                                {{ $stats['assignment_rate'] }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="batch-card">
                    <h5 class="mb-3">
                        <i class="bi bi-lightning me-2"></i>Quick Actions
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('job-portal.batches.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create New Batch
                        </a>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#autoAssignModal">
                            <i class="bi bi-robot me-2"></i>Auto Assign Students
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batches List -->
        <div class="row">
            <div class="col-12">
                <div class="batch-card">
                    <h5 class="mb-4">
                        <i class="bi bi-list-ul me-2"></i>Training Batches
                    </h5>
                    
                    @if($batches->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Batch Name</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th>Capacity</th>
                                        <th>Enrolled</th>
                                        <th>Progress</th>
                                        <th>Start Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($batches as $batch)
                                        <tr>
                                            <td>
                                                <strong>{{ $batch->batch_name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $batch->batch_code }}</span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-{{ $batch->status }}">
                                                    {{ ucfirst($batch->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $batch->capacity }}</td>
                                            <td>{{ $batch->applications_count }}</td>
                                            <td>
                                                @php
                                                    $percentage = $batch->capacity > 0 ? ($batch->applications_count / $batch->capacity) * 100 : 0;
                                                @endphp
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" 
                                                         style="width: {{ $percentage }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ round($percentage, 1) }}%</small>
                                            </td>
                                            <td>{{ $batch->start_date->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('job-portal.batches.show', $batch->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('job-portal.batches.edit', $batch->id) }}" 
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-success" 
                                                            onclick="assignToBatch({{ $batch->id }}, '{{ $batch->batch_name }}')">
                                                        <i class="bi bi-person-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No batches found</h5>
                            <p class="text-muted">Create your first training batch to get started.</p>
                            <a href="{{ route('job-portal.batches.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create Batch
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Auto Assign Modal -->
    <div class="modal fade" id="autoAssignModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Auto Assign Students to Batches</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('job-portal.batches.auto-assign', 0) }}" id="autoAssignForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="batch_id" class="form-label">Select Batch</label>
                            <select class="form-select" id="batch_id" name="batch_id" required>
                                <option value="">Choose a batch...</option>
                                @foreach($batches as $batch)
                                    @if($batch->status !== 'completed' && $batch->status !== 'cancelled')
                                        <option value="{{ $batch->id }}" 
                                                data-capacity="{{ $batch->capacity }}" 
                                                data-enrolled="{{ $batch->applications_count }}">
                                            {{ $batch->batch_name }} ({{ $batch->applications_count }}/{{ $batch->capacity }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="assignment_type" class="form-label">Assignment Method</label>
                            <select class="form-select" id="assignment_type" name="assignment_type" required>
                                <option value="">Select assignment method...</option>
                                <option value="by_application_number">By Application Number (First Come, First Served)</option>
                                <option value="by_approval_date">By Approval Date (Earliest First)</option>
                                <option value="manual_selection">Manual Selection</option>
                            </select>
                        </div>

                        <div id="manualSelection" style="display: none;">
                            <label class="form-label">Select Students</label>
                            <div id="availableStudents" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                <!-- Students will be loaded here -->
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> Each batch will maintain 15% reserve capacity. Students will be assigned to main slots first, with reserves kept for special cases.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Students</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function assignToBatch(batchId, batchName) {
            document.getElementById('batch_id').value = batchId;
            document.getElementById('autoAssignModal').querySelector('.modal-title').textContent = `Assign Students to ${batchName}`;
            new bootstrap.Modal(document.getElementById('autoAssignModal')).show();
        }

        // Update form action when batch is selected
        document.getElementById('batch_id').addEventListener('change', function() {
            const batchId = this.value;
            const form = document.getElementById('autoAssignForm');
            form.action = form.action.replace(/\/\d+\/auto-assign/, `/${batchId}/auto-assign`);
            
            // Reload students if manual selection is visible
            const manualSelection = document.getElementById('manualSelection');
            if (manualSelection.style.display === 'block') {
                loadAvailableStudents();
            }
        });

        // Show/hide manual selection based on assignment type
        document.getElementById('assignment_type').addEventListener('change', function() {
            const manualSelection = document.getElementById('manualSelection');
            const availableStudents = document.getElementById('availableStudents');
            
            if (this.value === 'manual_selection') {
                manualSelection.style.display = 'block';
                loadAvailableStudents();
            } else {
                manualSelection.style.display = 'none';
            }
        });

        function loadAvailableStudents() {
            const batchId = document.getElementById('batch_id').value;
            const url = '{{ route("job-portal.batches.available-students") }}' + (batchId ? '?batch_id=' + batchId : '');
            
            fetch(url)
                .then(response => response.json())
                .then(students => {
                    const container = document.getElementById('availableStudents');
                    container.innerHTML = '';
                    
                    students.forEach(student => {
                        const div = document.createElement('div');
                        div.className = 'form-check';
                        
                        // Check if student is assigned to current batch
                        const isChecked = student.is_assigned_to_current_batch ? 'checked' : '';
                        const isDisabled = student.is_assigned && !student.is_assigned_to_current_batch ? 'disabled' : '';
                        
                        // Create status badge
                        let statusBadge = `<span class="badge bg-secondary">${student.status}</span>`;
                        if (student.is_assigned) {
                            if (student.is_assigned_to_current_batch) {
                                statusBadge = `<span class="badge bg-success">Assigned to ${student.batch_name}</span>`;
                            } else {
                                statusBadge = `<span class="badge bg-warning">Assigned to ${student.batch_name}</span>`;
                            }
                        }
                        
                        div.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="student_ids[]" value="${student.id}" id="student_${student.id}" ${isChecked} ${isDisabled}>
                            <label class="form-check-label" for="student_${student.id}">
                                <strong>${student.application_number}</strong> - ${student.name}
                                <br><small class="text-muted">${student.email}</small>
                                <br>${statusBadge}
                            </label>
                        `;
                        container.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    document.getElementById('availableStudents').innerHTML = '<p class="text-muted">Error loading students.</p>';
                });
        }
    </script>
</body>
</html>
