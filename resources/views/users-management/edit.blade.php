@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Edit User: {{ $user->name }}</h3>

    <form method="POST" action="{{ route('users-management.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Role</label>
                <select name="role" class="form-select">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 mt-4">
                <h5>Assign / Revoke Permissions</h5>
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    {{ $user->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ ucfirst($permission->name) }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('users-management.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
