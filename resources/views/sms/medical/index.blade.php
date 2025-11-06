<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - Medical Records</title>
    
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
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('sms.dashboard') }}">
                <i class="bi bi-arrow-left me-2"></i>
                SMS - Medical Records
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            {{-- <div class="card-body text-center py-5">
                <i class="bi bi-heart-pulse display-1 text-danger mb-3"></i>
                <h3>Medical Records</h3>
                <p class="text-muted">Manage student medical information, excuses, and health records.</p>
                <p class="text-muted">This module is under development and will be available soon.</p>
            </div> --}}
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <i class="bi bi-heart-pulse display-6 text-danger me-2"></i>
                        <h3 class="d-inline">Medical Records</h3>
                    </div>
                    @can('create-medical-records')
                        <a href="{{ route('medical.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Medical Record
                        </a>
                    @endcan
                </div>

                @if($medicalRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Student Name</th>
                                    <th>Student ID</th>
                                    <th>Medical Condition</th>
                                    <th>Severity Level</th>
                                    <th>Medical Notes</th>
                                    {{-- <th>Record Date</th> --}}
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicalRecords as $record)
                                    <tr>
                                        <td>
                                            @if($record->student->photo)
                                                <img src="{{ asset($record->student->photo) }}" 
                                                    alt="{{ $record->student->first_name }}" 
                                                    class="rounded-circle"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('images/default-avatar.png') }}" 
                                                    alt="Default" 
                                                    class="rounded-circle"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $record->student->first_name }} {{ $record->student->last_name }}</strong>
                                        </td>
                                        <td>{{ $record->student->student_id }}</td>
                                        <td>{{ $record->medical_condition ?? 'N/A' }}</td>
                                        <td>
                                            @switch($record->medical_severity_level)
                                                @case('mild')
                                                    <span class="badge bg-success">Mild</span>
                                                    @break
                                                @case('moderate')
                                                    <span class="badge bg-warning">Moderate</span>
                                                    @break
                                                @case('severe')
                                                    <span class="badge bg-danger">Severe</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">Unknown</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($record->medical_notes ?? 'No notes', 50) }}</small>
                                        </td>
                                        {{-- <td>{{ $record->record_date != '0000-00-00' ? \Carbon\Carbon::parse($record->record_date)->format('M d, Y') : 'N/A' }}</td> --}}
                                        {{-- <td>
                                            <div class="btn-group" role="group">
                                                @can('view-medical-records')
                                                    <a href="{{ route('medical.show', $record->id) }}" 
                                                    class="btn btn-sm btn-info" 
                                                    title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('edit-medical-records')
                                                    <a href="{{ route('medical.edit', $record->id) }}" 
                                                    class="btn btn-sm btn-warning" 
                                                    title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('delete-medical-records')
                                                    <form action="{{ route('medical.destroy', $record->id) }}" 
                                                        method="POST" 
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this medical record?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $medicalRecords->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-heart-pulse display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">No Medical Records Found</h4>
                        <p class="text-muted">Start by adding student medical information.</p>
                        @can('create-medical-records')
                            <a href="{{ route('medical.create') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle"></i> Add First Medical Record
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>