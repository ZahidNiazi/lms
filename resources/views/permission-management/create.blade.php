@extends('layouts.admin')

@section('content')
<h4>Add Permission</h4>
<form action="{{ route('permission-management.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Permission Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <button class="btn btn-primary">Save</button>
</form>
@endsection
