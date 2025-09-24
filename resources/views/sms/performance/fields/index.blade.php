@extends('admin.layouts.app')

@section('title', 'Performance Fields Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="bi bi-gear me-2"></i>
                        Performance Fields Management
                    </h2>
                    <p class="text-muted mb-0">Manage performance evaluation fields and categories</p>
                </div>
                <div>
                    <a href="{{ route('sms.performance.fields.create') }}" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Field
                    </a>
                    <a href="{{ route('sms.performance.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Performance
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Search by name or description...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('sms.performance.fields.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Performance Fields -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Performance Fields
            </h5>
        </div>
        <div class="card-body p-0">
            @if($performanceFields->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Field Name</th>
                                <th>Category</th>
                                <th>Max Score</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Usage</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($performanceFields as $field)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $field->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $field->category }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $field->max_score }}</span>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            {{ Str::limit($field->description, 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $field->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $field->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $field->performances->count() }} records
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sms.performance.fields.edit', $field->id) }}"
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('sms.performance.fields.toggle-status', $field->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $field->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                                        title="{{ $field->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-{{ $field->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('sms.performance.fields.destroy', $field->id) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this performance field?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{ $performanceFields->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-gear display-1 text-muted"></i>
                    <h4 class="mt-3">No Performance Fields Found</h4>
                    <p class="text-muted">Start by creating performance fields for evaluations.</p>
                    <a href="{{ route('sms.performance.fields.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Create First Field
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
