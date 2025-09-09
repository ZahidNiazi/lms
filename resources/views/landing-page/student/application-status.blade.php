@extends('landing-page.student.dashboard')
@section('content')
<div class="form-section">
    <h2>Application Status</h2>
    
    @if($status)
        <div class="status-card">
            <div class="status-header">
                <h3>Current Status:
                    <span class="status-label bg-gree {{ $student->status === 'approved' ? 'bg-green' : 'bg-default' }}">
                        {{ ucfirst(str_replace('_', ' ', $student->status)) }}
                    </span>
                </h3>
            </div>            
            
            @if($status->feedback)
                <div class="feedback">
                    <h4>Feedback:</h4>
                    <p>{{ $status->feedback }}</p>
                </div>
            @endif
            
            @if($status->interview_date)
                <div class="interview-details">
                    <h4>Interview Details:</h4>
                    <p><strong>Date & Time:</strong> {{ $status->interview_date->format('Y-m-d H:i') }}</p>
                    <p><strong>Location:</strong> [Interview location details will be provided here]</p>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-info">
            Your application is being processed. Please check back later for updates.
        </div>
    @endif
</div>
@endsection

