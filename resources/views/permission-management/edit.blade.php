@extends('layouts.admin')

@section('content')
<h4>Edit Permission</h4>
<form action="{{ route('permission-management.update', $permission->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Permission Name</label>
        <input type="text" name="name" value="{{ $permission->name }}" class="form-control" required>
    </div>
    <button class="btn btn-primary">Update</button>
</form>
@endsection
