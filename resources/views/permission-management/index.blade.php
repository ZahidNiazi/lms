@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Permission Management</h4>
    <a href="{{ route('permission-management.create') }}" class="btn btn-primary">Add Permission</a>
</div>
@if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Permission</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permissions as $perm)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $perm->name }}</td>
                <td>
                    <a href="{{ route('permission-management.edit', $perm->id) }}" class="btn btn-success btn-sm">Edit</a>
                    <form action="{{ route('permission-management.destroy', $perm->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
