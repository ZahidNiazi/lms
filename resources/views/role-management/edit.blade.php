@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-2">Edit Role</h4>
        <a href="{{ route('role-management.index') }}" class="btn btn-secondary mb-2">Back</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <form action="{{ route('role-management.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Role Name</label>
                    <input type="text" name="name" id="name" 
                           class="form-control" value="{{ $role->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold d-block mb-2">Permissions</label>
                    <div class="row">
                        @foreach ($permissions->chunk(ceil($permissions->count() / 4)) as $chunk)
                            <div class="col-md-3 col-sm-6 mb-2">
                                @foreach ($chunk as $permission)
                                    <div class="form-check mb-1">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               id="permission-{{ $permission->id }}"
                                               class="form-check-input"
                                               {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                        <label for="permission-{{ $permission->id }}" class="form-check-label text-capitalize">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Role</button>
                    <a href="{{ route('role-management.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
