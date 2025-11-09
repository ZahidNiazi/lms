@extends('layouts.admin')

@section('page-title')
    Students Management
@endsection

@push('css-page')
<style>
    :root {
        --bg: #f6f7fb;
        --card: #ffffff;
        --text: #1f2937; /* slate-800 */
        --muted: #64748b; /* slate-500 */
        --primary: #6c5ce7; /* violet */
        --primary-2: #7c3aed; /* violet-600 */
        --accent: #06b6d4; /* cyan */
        --success: #10b981; /* green */
        --warning: #f59e0b; /* amber */
        --danger: #ef4444; /* red */
        --ring: rgba(124,58,237,.20);
        --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        --border: 1px solid rgba(124,58,237,.08);
    }
    .dark {
        --bg: #0b1020;
        --card: #0f172a;
        --text: #e5e7eb;
        --muted: #94a3b8;
        --primary: #8b5cf6;
        --primary-2: #a78bfa;
        --accent: #22d3ee;
        --success: #34d399;
        --warning: #fbbf24;
        --danger: #f87171;
        --ring: rgba(167,139,250,.25);
        --shadow: 0 12px 36px rgba(0, 0, 0, 0.35);
        --border: 1px solid rgba(148,163,184,.18);
    }

    html, body { height: 100%; }
    body { background: var(--bg); color: var(--text); }

    /* Shell */
    .soft-card { background: var(--card); border-radius: 16px; padding: 18px; box-shadow: var(--shadow); border: var(--border); }
    .section-title { color: var(--primary); font-weight: 800; letter-spacing:.2px; margin-bottom: 14px; position: relative; }
    .section-title:after { content:""; position:absolute; left:0; bottom:-8px; width:56px; height:3px; border-radius:999px; background:linear-gradient(90deg,var(--primary),var(--primary-2)); }

    /* Filter bar */
    .filter-grid { display:grid; grid-template-columns: repeat(12, 1fr); gap: 12px; }
    .filter-grid .col-span-3 { grid-column: span 3 / span 3; }
    .filter-grid .col-span-2 { grid-column: span 2 / span 2; }
    .filter-grid .col-span-4 { grid-column: span 4 / span 4; }
    .filter-grid .col-span-12 { grid-column: 1 / -1; }
    @media (max-width: 1199px) { .filter-grid .col-span-3, .filter-grid .col-span-2, .filter-grid .col-span-4 { grid-column: span 6 / span 6; } }
    @media (max-width: 767px)  { .filter-grid > * { grid-column: 1 / -1; } }

    .form-label { font-weight: 700; color: var(--muted); }
    .form-control, .form-select { border-radius: 10px; border: 1px solid rgba(148,163,184,.35); padding: .6rem .8rem; }
    .btn-primary { background: linear-gradient(90deg, var(--primary), var(--primary-2)); border: none; font-weight: 700; }
    .btn-secondary { background: #e2e8f0; color: #0f172a; border: none; font-weight: 700; }
    .btn-outline { background: transparent; border: 1px solid rgba(124,58,237,.35); color: var(--primary); font-weight: 700; }

    /* Search + chips */
    .chip { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; font-weight:700; font-size:.9rem; background: rgba(6,182,212,.12); color: var(--accent); border: 1px solid rgba(6,182,212,.25); }

    /* Student card */
    .student-card { background: var(--card); border-radius: 16px; padding: 18px; margin-bottom: 16px; border: var(--border); transition: transform .15s ease, box-shadow .2s ease, border-color .2s ease; }
    .student-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); border-color: rgba(124,58,237,.22); }
    .student-head { display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }

    .avatar { width: 44px; height: 44px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-weight:800; color:#fff; background: linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow: 0 8px 18px var(--ring); }

    .status-badge { padding: 6px 12px; border-radius: 999px; font-size: .82rem; font-weight: 800; letter-spacing:.2px; }
    .status-pending { background: rgba(245,158,11,.16); color: var(--warning); }
    .status-approved { background: rgba(16,185,129,.16); color: var(--success); }
    .status-rejected { background: rgba(239,68,68,.16); color: var(--danger); }
    .status-under_review { background: rgba(99,102,241,.18); color: var(--primary-2); }
    .status-approved_for_interview { background: rgba(6,182,212,.18); color: var(--accent); }
    .status-interview_scheduled { background: rgba(148,163,184,.20); color: var(--muted); }
    .status-interview_completed { background: rgba(16,185,129,.16); color: var(--success); }

    .meta { color: var(--muted); font-weight:600; }
    .divider { height:1px; background: rgba(148,163,184,.25); margin: 12px 0; }

    .action-btn { padding: 8px 12px; border-radius: 10px; text-decoration:none; font-weight:700; font-size:.9rem; border: 1px solid rgba(124,58,237,.25); color: var(--primary); background: rgba(124,58,237,.06); }
    .action-btn:hover { filter: brightness(1.05); }
    .btn-view { border-color: rgba(6,182,212,.35); color: var(--accent); background: rgba(6,182,212,.08); }
    .btn-edit { border-color: rgba(124,58,237,.35); color: var(--primary); background: rgba(124,58,237,.08); }
    .btn-delete { border-color: rgba(239,68,68,.30); color: var(--danger); background: rgba(239,68,68,.08); }

    /* Pagination */
    .pagination { gap: 6px; }
    .page-link { border-radius: 10px !important; border: 1px solid rgba(124,58,237,.25); color: var(--primary); font-weight:700; }
    .page-item.active .page-link { background: linear-gradient(90deg, var(--primary), var(--primary-2)); border-color: transparent; color:#fff; }

    /* Modal */
    .modal-content { background: var(--card); color: var(--text); border: var(--border); border-radius: 16px; }
    .modal-header { border-bottom: 1px dashed rgba(148,163,184,.25); }
    .modal-footer { border-top: 1px dashed rgba(148,163,184,.25); }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="soft-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('national-service-lms.dashboard') }}">National Service LMS</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Students</li>
                            </ol>
                        </nav>
                        <h4 class="mb-0" style="font-weight:800; letter-spacing:.2px;">Students Management</h4>
                    </div>
                    <div>
                        <button class="btn btn-outline" id="themeToggle"><i class="far fa-moon"></i> Theme</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="soft-card">
                <h5 class="section-title">Filters</h5>
                <form method="GET" action="{{ route('national-service-lms.students') }}" class="filter-grid" role="search">
                    <div class="col-span-3">
                        <label for="stage" class="form-label">Application Stage</label>
                        <select name="stage" id="stage" class="form-select">
                            <option value="">All Stages</option>
                            <option value="pending" {{ request('stage') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ request('stage') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved_for_interview" {{ request('stage') == 'approved_for_interview' ? 'selected' : '' }}>Approved for Interview</option>
                            <option value="interview_scheduled" {{ request('stage') == 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="interview_completed" {{ request('stage') == 'interview_completed' ? 'selected' : '' }}>Interview Completed</option>
                            <option value="approved" {{ request('stage') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('stage') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label for="reachable" class="form-label">Reachability</label>
                        <select name="reachable" id="reachable" class="form-select">
                            <option value="">All</option>
                            <option value="1" {{ request('reachable') == '1' ? 'selected' : '' }}>Reachable</option>
                            <option value="0" {{ request('reachable') == '0' ? 'selected' : '' }}>Not Reachable</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label for="q" class="form-label">Search</label>
                        <div class="input-group">
                            <input type="text" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Name, Email, NID…">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    <div class="col-span-12 d-flex align-items-center justify-content-between mt-1">
                        <div class="d-flex flex-wrap gap-2">
                            @if(request('stage'))
                                <span class="chip"><i class="fas fa-stream"></i> Stage: {{ str_replace('_',' ', request('stage')) }}</span>
                            @endif
                            @if(request('status'))
                                <span class="chip"><i class="fas fa-flag"></i> Status: {{ request('status') }}</span>
                            @endif
                            @if(request('reachable') !== null && request('reachable') !== '')
                                <span class="chip"><i class="fas fa-phone"></i> {{ request('reachable')=='1' ? 'Reachable' : 'Not Reachable' }}</span>
                            @endif
                            @if(request('q'))
                                <span class="chip"><i class="fas fa-search"></i> "{{ request('q') }}"</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
                            <a href="{{ route('national-service-lms.students') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        @forelse($students as $student)
            <div class="col-xl-4 col-lg-6">
                <div class="student-card">
                    <div class="student-head mb-2">
                        <div class="d-flex align-items-center gap-2">
                            {{-- <div class="avatar">{{ strtoupper(substr($student->name,0,1)) }}</div> --}}
                            <div class="avatar bg-primary text-white d-flex justify-content-center align-items-center"
                                style="width: 45px; height: 45px; font-weight: 600; font-size: 18px; overflow: hidden; border-radius: 6px;">
                                
                                @if(isset($student->profile) && !empty($student->profile->profile_picture))
                                    <img src="{{ asset($student->profile->profile_picture) }}"
                                        alt="Profile Picture"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                @endif

                            </div>

                            <div>
                                <h5 class="mb-0" style="font-weight:800;">{{ $student->name }}</h5>
                                <div class="meta">ID: {{ $student->id }} • Applied {{ $student->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <span class="status-badge status-{{ $student->application_stage }}">
                            {{ str_replace('_', ' ', ucfirst($student->application_stage)) }}
                        </span>
                    </div>

                    <div class="divider"></div>

                    <div class="mb-2">
                        <p class="mb-1"><strong>Email:</strong> {{ $student->email }}</p>
                        @if($student->profile)
                            <p class="mb-1"><strong>Mobile:</strong> {{ $student->profile->mobile_no }}</p>
                            <p class="mb-1"><strong>NID:</strong> {{ $student->profile->nid }}</p>
                            <p class="mb-1"><strong>DOB:</strong>
                                @if($student->profile->dob && is_string($student->profile->dob))
                                    {{ \Carbon\Carbon::parse($student->profile->dob)->format('d/m/Y') }}
                                @elseif($student->profile->dob)
                                    {{ $student->profile->dob->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </p>
                        @endif
                        <p class="mb-1"><strong>Status:</strong>
                            <span class="status-badge {{ $student->status === 'approved' ? 'status-approved' : ($student->status === 'rejected' ? 'status-rejected' : 'status-pending') }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </p>
                        <p class="mb-1"><strong>Reachable:</strong>
                            <span class="status-badge {{ $student->is_reachable ? 'status-approved' : 'status-rejected' }}">
                                {{ $student->is_reachable ? 'Yes' : 'No' }}
                            </span>
                        </p>
                        @if($student->is_under_age_18)
                            <p class="mb-1"><span class="status-badge status-pending">Under Age 18</span></p>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="meta">Applied: {{ $student->created_at->format('d/m/Y') }}</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('national-service-lms.students.show', $student) }}" class="action-btn btn-view"><i class="fas fa-eye"></i></a>
                            <button type="button" class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#updateStageModal{{ $student->id }}">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Stage Modal -->
            <div class="modal fade" id="updateStageModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Application Stage – {{ $student->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('national-service-lms.students.update-stage', $student) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="application_stage_{{ $student->id }}" class="form-label">Application Stage</label>
                                    <select name="application_stage" id="application_stage_{{ $student->id }}" class="form-select" required>
                                        <option value="pending" {{ $student->application_stage == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="under_review" {{ $student->application_stage == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                        <option value="approved_for_interview" {{ $student->application_stage == 'approved_for_interview' ? 'selected' : '' }}>Approved for Interview</option>
                                        <option value="interview_scheduled" {{ $student->application_stage == 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                                        <option value="interview_completed" {{ $student->application_stage == 'interview_completed' ? 'selected' : '' }}>Interview Completed</option>
                                        <option value="approved" {{ $student->application_stage == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $student->application_stage == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="rejection_reason_{{ $student->id }}" class="form-label">Rejection Reason (if applicable)</label>
                                    <textarea name="rejection_reason" id="rejection_reason_{{ $student->id }}" class="form-control" rows="3" placeholder="Enter rejection reason if rejecting the application">{{ $student->rejection_reason }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Stage</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="soft-card text-center">
                    <h5 class="mb-1">No students found</h5>
                    <p class="meta mb-0">No students match the current filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $students->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('script-page')
<script>
    // Theme toggle with persistence
    (function() {
        const root = document.documentElement;
        const saved = localStorage.getItem('theme');
        if (saved === 'dark') root.classList.add('dark');
        const toggle = document.getElementById('themeToggle');
        if (toggle) {
            toggle.addEventListener('click', () => {
                root.classList.toggle('dark');
                localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
            });
        }
    })();

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(el => el.classList.add('fade'));
    }, 5000);
</script>
@endpush
