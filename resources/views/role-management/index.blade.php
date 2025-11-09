@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-2">Role Management</h4>
        <a href="{{ route('role-management.create') }}" class="btn btn-success mb-2">Add Role</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            {{-- <div class="table-responsive"> --}}
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Role Name</th>
                            <th class="text-end" style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $index => $role)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold text-capitalize">{{ $role->name }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('role-management.edit', $role->id) }}" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('role-management.destroy', $role->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            {{-- </div> --}}
        </div>
    </div>
</div>
@endsection
