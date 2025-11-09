@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Add New User</h3>
    <form method="POST" action="{{ route('users-management.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Assign Role</label>
                <select name="role" class="form-select" required>
                    <option value="">Select Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="{{ route('users-management.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
