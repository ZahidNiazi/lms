@extends('landing-page.student.dashboard')

        {{-- @section('content')
    <div class="form-section">
        <h2>Student Application Form</h2>
        
        @if(!Auth::guard('student')->user()->profile_completed)
            <div class="alert alert-info">
                Please complete your profile before proceeding to document upload.
            </div>
        @endif
        
        <form method="POST" action="{{ route('student.profile.submit') }}">
            @csrf
            
            <h3>Personal Information</h3>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="nid">National ID</label>
                <input type="text" id="nid" name="nid" value="{{ old('nid', $profile->nid ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="mobile_no">Mobile No</label>
                <input type="text" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $profile->mobile_no ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="{{ old('dob', $profile->dob ?? '') }}" required>
                <small>Age must be between 16 to 28 years</small>
            </div>
            
            <h3>Permanent Address</h3>
            <div class="form-group">
                <label for="permanent_atoll">Atoll</label>
                <input type="text" id="permanent_atoll" name="permanent_atoll" value="{{ old('permanent_atoll', $permanentAddress->atoll ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="permanent_island">Island</label>
                <input type="text" id="permanent_island" name="permanent_island" value="{{ old('permanent_island', $permanentAddress->island ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="permanent_district">District</label>
                <input type="text" id="permanent_district" name="permanent_district" value="{{ old('permanent_district', $permanentAddress->district ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="permanent_address">Address</label>
                <input type="text" id="permanent_address" name="permanent_address" value="{{ old('permanent_address', $permanentAddress->address ?? '') }}" required>
            </div>
            
            <h3>Present Address</h3>
            <div class="form-group">
                <label for="present_atoll">Atoll</label>
                <input type="text" id="present_atoll" name="present_atoll" value="{{ old('present_atoll', $presentAddress->atoll ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="present_island">Island</label>
                <input type="text" id="present_island" name="present_island" value="{{ old('present_island', $presentAddress->island ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="present_district">District</label>
                <input type="text" id="present_district" name="present_district" value="{{ old('present_district', $presentAddress->district ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="present_address">Address</label>
                <input type="text" id="present_address" name="present_address" value="{{ old('present_address', $presentAddress->address ?? '') }}" required>
            </div>
            
            <h3>Parent Details</h3>
            <div class="form-group">
                <label for="parent_name">Name</label>
                <input type="text" id="parent_name" name="parent_name" value="{{ old('parent_name', $parentDetail->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="parent_relation">Relation</label>
                <input type="text" id="parent_relation" name="parent_relation" value="{{ old('parent_relation', $parentDetail->relation ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="parent_atoll">Atoll</label>
                <input type="text" id="parent_atoll" name="parent_atoll" value="{{ old('parent_atoll', $parentDetail->atoll ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="parent_island">Island</label>
                <input type="text" id="parent_island" name="parent_island" value="{{ old('parent_island', $parentDetail->island ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="parent_address">Address</label>
                <input type="text" id="parent_address" name="parent_address" value="{{ old('parent_address', $parentDetail->address ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="parent_mobile_no">Mobile No</label>
                <input type="text" id="parent_mobile_no" name="parent_mobile_no" value="{{ old('parent_mobile_no', $parentDetail->mobile_no ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="parent_email">Email</label>
                <input type="email" id="parent_email" name="parent_email" value="{{ old('parent_email', $parentDetail->email ?? '') }}">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    @endsection --}}