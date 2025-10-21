@extends('layouts.admin')

@section('page-title')
    Training Batches Management
@endsection

@push('css-page')
    <style>
        .batch-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-active { background: #d4edda; color: #155724; }
        .status-completed { background: #cce5ff; color: #004085; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            margin: 2px;
            display: inline-block;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('national-service-lms.dashboard') }}">National Service LMS</a></li>
                    <li class="breadcrumb-item active">Training Batches</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Training Batches</h5>
                <a href="{{ route('national-service-lms.training.batches.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Batch
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Batches List -->
    <div class="row">
        @forelse($batches as $batch)
        <div class="col-md-6 col-lg-4">
            <div class="batch-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="mb-0">{{ $batch->batch_name }}</h5>
                    <span class="status-badge status-{{ $batch->status }}">
                        {{ ucfirst($batch->status) }}
                    </span>
                </div>

                <div class="mb-3">
                    <p class="mb-1"><strong>Batch Code:</strong> {{ $batch->batch_code }}</p>
                    <p class="mb-1"><strong>Start Date:</strong> {{ $batch->start_date->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>End Date:</strong> {{ $batch->end_date->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>Capacity:</strong> {{ $batch->capacity }}</p>
                    {{-- <p class="mb-1"><strong>Enrolled:</strong> {{ $batch->enrolled_count }}</p> --}}
                     <p class="mb-1"><strong>Enrolled:</strong> {{ $batch->applications_count }}</p>
                    @if($batch->description)
                        <p class="mb-1"><strong>Description:</strong> {{ Str::limit($batch->description, 100) }}</p>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">Created: {{ $batch->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        {{-- <a href="#" class="action-btn btn-primary"><i class="fas fa-pen"></i></a>
                        <a href="#" class="action-btn btn-warning"><i class="fas fa-eye"></i></a> --}}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h5>No training batches found</h5>
                <p>No training batches have been created yet.</p>
                <a href="{{ route('national-service-lms.training.batches.create') }}" class="btn btn-primary mt-2">Create First Batch</a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($batches->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $batches->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
