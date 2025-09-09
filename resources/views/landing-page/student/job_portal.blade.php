
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://www.iconexperience.com/_img/v_collection_png/256x256/shadow/school.png">
    <title>Job Portal - Application Review</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #4f7cff;
            --warning-yellow: #ffc107;
            --success-green: #28a745;
            --purple: #8b5cf6;
            --light-bg: #f8f9fa;
            --card-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            color: #333 !important;
            font-size: 1.25rem;
        }

        .navbar-icons {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .navbar-icons i {
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.3s;
        }

        .navbar-icons i:hover {
            color: #333;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: none;
            height: 100%;
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stats-icon.blue { background-color: var(--primary-blue); }
        .stats-icon.yellow { background-color: var(--warning-yellow); }
        .stats-icon.green { background-color: var(--success-green); }
        .stats-icon.purple { background-color: var(--purple); }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stats-change {
            font-size: 0.85rem;
            color: var(--success-green);
            font-weight: 500;
        }

        .nav-tabs-custom {
            background: white;
            border-radius: 12px;
            padding: 0.5rem;
            box-shadow: var(--card-shadow);
            border: none;
            margin: 2rem 0;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: 8px;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s;
        }

        .nav-tabs-custom .nav-link.active {
            background-color: var(--light-bg);
            color: #333;
        }

        .nav-tabs-custom .nav-link:hover {
            background-color: #f1f3f4;
            color: #333;
        }

        .main-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: none;
        }

        .content-header {
            margin-bottom: 2rem;
        }

        .content-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .content-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .search-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.9rem;
        }

        .search-box i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .btn-outline-secondary {
            border-radius: 8px;
            border-color: #e9ecef;
            color: #6c757d;
            font-weight: 500;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            color: #6c757d;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.75rem;
        }

        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
            font-size: 0.9rem;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-approve {
            background-color: green;
            color: #856404;
        }
        .status-reject {
            background-color: red;
            color: #856404;
        }

        .status-document {
            background-color: #cce7ff;
            color: #0066cc;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.85rem;
        }

        .action-btn.view {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .action-btn.edit {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .action-btn.delete {
            background-color: #ffebee;
            color: #d32f2f;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .search-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
            }

            .navbar-icons {
                gap: 10px;
            }

            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">Job Portal - Application Review</a>
            <a href="{{ url('student/dashboard') }}"></a>

            <div class="navbar-icons">
                <i class="bi bi-bell"></i>
                <i class="bi bi-gear"></i>
                <a href="{{ route('job.logout') }}">
    <i class="bi bi-box-arrow-right"></i>
</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon blue">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="stats-number">{{$totalApplications}}</div>
                    <div class="stats-label">Total Applications</div>
                    <div class="stats-change">+23% from last month</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon yellow">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stats-number">312</div>
                    <div class="stats-label">Pending Review</div>
                    <div class="stats-change">+8% from last month</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon green">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stats-number">856</div>
                    <div class="stats-label">Approved</div>
                    <div class="stats-change">+15% from last month</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon purple">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stats-number">127</div>
                    <div class="stats-label">Interview Scheduled</div>
                    <div class="stats-change">+12% from last month</div>
                </div>
            </div>
        </div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-tab="applications">Applications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="interviews">Interviews</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="batch">Batch Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="communications">Communications</a>
            </li>
        </ul>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h2 class="content-title">Application Review</h2>
                <p class="content-subtitle">Review and process student applications</p>
            </div>

            <!-- Search Controls -->
            <div class="search-controls">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Search applications...">
                </div>
                <select class="form-select" style="width: auto;">
                    <option>All Status</option>
                    <option>Pending Review</option>
                    <option>Document Review</option>
                    <option>Approved</option>
                </select>
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-download me-2"></i>Export
                </button>
            </div>

            <!-- Applications Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Name</th>
                            <th>NID</th>
                            <th>Age</th>
                            <th>Location</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        <tr>
                            <td><strong>NS-2024-001</strong></td>
                            <td>Ahmed Mohamed</td>
                            <td>A123456</td>
                            <td>22</td>
                            <td>Male, Kaafu</td>
                            <td>2024-01-15</td>
                            <td><span class="status-badge status-pending">Pending Review</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view"><i class="bi bi-eye"></i></button>
                                    <button class="action-btn edit"><i class="bi bi-pencil"></i></button>
                                    <button class="action-btn delete"><i class="bi bi-star"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>NS-2024-002</strong></td>
                            <td>Aminath Ali</td>
                            <td>A789012</td>
                            <td>17</td>
                            <td>Rasdhoo, Alifu Alifu</td>
                            <td>2024-01-14</td>
                            <td><span class="status-badge status-document">Document Review</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view"><i class="bi bi-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>NS-2024-003</strong></td>
                            <td>Ibrahim Hassan</td>
                            <td>A345678</td>
                            <td>25</td>
                            <td>Eydhafushi, Baa</td>
                            <td>2024-01-13</td>
                            <td><span class="status-badge status-approved">Approved</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view"><i class="bi bi-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody> --}}
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $application = $student->jobPortalApplication;
                                $statusClass = 'status-pending';
                                if ($application) {
                                    switch($application->status) {
                                        case 'approved':
                                        case 'selected':
                                        case 'batch_assigned':
                                            $statusClass = 'status-approved';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'status-reject';
                                            break;
                                        case 'document_review':
                                            $statusClass = 'status-document';
                                            break;
                                    }
                                }
                            @endphp
                            <tr>
                                <td><strong>{{ $application ? $application->application_number : 'NS-' . $student->id }}</strong></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->profile->nid ?? 'N/A' }}</td>
                                <td>
                                    @if(!empty($student->profile->dob))
                                        {{ \Carbon\Carbon::parse($student->profile->dob)->age }} years
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $address = $student->addresses->first();
                                    @endphp
                                    {{ $address->island ?? 'N/A' }}, {{ $address->atoll ?? '' }}
                                </td>
                                <td>{{ $student->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $application ? ucfirst(str_replace('_', ' ', $application->status)) : 'Pending Review' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        {{-- View --}}
                                        <button class="action-btn view border-0 bg-transparent p-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#studentModal"
                                            data-name="{{ $student->name }}"
                                            data-nid="{{ $student->profile->nid ?? 'N/A' }}"
                                            data-mobile="{{ $student->profile->mobile_no ?? 'N/A' }}"
                                            data-dob="{{ $student->profile->dob ?? 'N/A' }}"
                                            data-age="{{ !empty($student->profile->dob) ? \Carbon\Carbon::parse($student->profile->dob)->age : 'N/A' }}"
                                            data-address="{{ optional($student->addresses->first())->island ?? 'N/A' }}, {{ optional($student->addresses->first())->atoll ?? '' }}"
                                            data-applied="{{ $student->profile->applied_field ?? 'N/A' }}"
                                            data-submitted="{{ $student->created_at->format('Y-m-d') }}"
                                            data-status="{{ $application ? ucfirst(str_replace('_', ' ', $application->status)) : 'Pending' }}"
                                            data-application-id="{{ $application ? $application->id : $student->id }}"
                                        >
                                            <i class="bi bi-eye text-primary"></i>
                                        </button>
                                       
                                        @if($application && in_array($application->status, ['pending_review', 'document_review']))
                                            <a href="{{ route('job-portal.applications.status', [$application->id]) }}" 
                                               class="bg-transparent btn-sm me-1" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-original-title="Review Application">
                                                <i class="bi bi-check-circle text-success"></i>
                                            </a>
                                        @endif

                                        @if($application && $application->status === 'approved')
                                            <a href="{{ route('job-portal.applications.schedule-interview', [$application->id]) }}" 
                                               class="bg-transparent btn-sm me-1" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-original-title="Schedule Interview">
                                                <i class="bi bi-calendar-plus text-info"></i>
                                            </a>
                                        @endif

                                        @if($application && $application->status === 'selected')
                                            <form method="POST" action="{{ route('job-portal.applications.assign-batch', $application->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="bg-transparent btn-sm me-1" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-original-title="Assign to Batch">
                                                    <i class="bi bi-people text-warning"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
<!-- Student Detail Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="studentModalLabel">Student Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-2"><strong>Name:</strong> <span id="modal-name"></span></div>
                <div class="col-md-6 mb-2"><strong>NID:</strong> <span id="modal-nid"></span></div>
                <div class="col-md-6 mb-2"><strong>Mobile:</strong> <span id="modal-mobile"></span></div>
                <div class="col-md-6 mb-2"><strong>Date of Birth:</strong> <span id="modal-dob"></span></div>
                <div class="col-md-6 mb-2"><strong>Age:</strong> <span id="modal-age"></span></div>
                <div class="col-md-6 mb-2"><strong>Address:</strong> <span id="modal-address"></span></div>
                <div class="col-md-6 mb-2"><strong>Applied Field:</strong> <span id="modal-applied"></span></div>
                <div class="col-md-6 mb-2"><strong>Submitted Date:</strong> <span id="modal-submitted"></span></div>
                <div class="col-md-6 mb-2"><strong>Status:</strong> <span id="modal-status"></span></div>
            </div>
        </div>
      </div>
    </div>
  </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all tabs
                document.querySelectorAll('.nav-link').forEach(tab => {
                    tab.classList.remove('active');
                });

                // Add active class to clicked tab
                this.classList.add('active');

                // Here you could implement tab content switching
                const tabName = this.getAttribute('data-tab');
                console.log('Switched to tab:', tabName);
            });
        });

        // Search functionality
        document.querySelector('.search-box input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Status filter functionality
        document.querySelector('select').addEventListener('change', function(e) {
            const selectedStatus = e.target.value;
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const statusBadge = row.querySelector('.status-badge');
                if (selectedStatus === 'All Status' || statusBadge.textContent.trim() === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Action button functionality
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const action = this.className.includes('view') ? 'view' :
                              this.className.includes('edit') ? 'edit' : 'star';
                const row = this.closest('tr');
                const applicationId = row.querySelector('td:first-child strong').textContent;

                console.log(`${action} action for application: ${applicationId}`);

                // Add visual feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Navbar icon functionality
        document.querySelectorAll('.navbar-icons i').forEach(icon => {
            icon.addEventListener('click', function() {
                const iconClass = this.className;
                if (iconClass.includes('bell')) {
                    console.log('Notifications clicked');
                } else if (iconClass.includes('gear')) {
                    console.log('Settings clicked');
                } else if (iconClass.includes('box-arrow-right')) {
                    console.log('Logout clicked');
                }

                // Add visual feedback
                this.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });
        });

        // Export functionality
        document.querySelector('.btn-outline-secondary').addEventListener('click', function() {
            console.log('Export functionality triggered');

            // Create a simple CSV export simulation
            const tableData = [];
            const headers = Array.from(document.querySelectorAll('thead th')).map(th => th.textContent.trim());
            tableData.push(headers);

            document.querySelectorAll('tbody tr').forEach(row => {
                if (row.style.display !== 'none') {
                    const rowData = Array.from(row.querySelectorAll('td')).map(td => {
                        // For status column, get the badge text
                        const badge = td.querySelector('.status-badge');
                        if (badge) return badge.textContent.trim();

                        // For actions column, return 'Actions Available'
                        if (td.querySelector('.action-buttons')) return 'Actions Available';

                        return td.textContent.trim();
                    });
                    tableData.push(rowData);
                }
            });

            console.log('Exported data:', tableData);
            alert('Export functionality simulated. Check console for data.');
        });

        // Add smooth animations for cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stats-card, .main-content').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('studentModal');

            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                document.getElementById('modal-name').textContent = button.getAttribute('data-name');
                document.getElementById('modal-nid').textContent = button.getAttribute('data-nid');
                document.getElementById('modal-mobile').textContent = button.getAttribute('data-mobile');
                document.getElementById('modal-dob').textContent = button.getAttribute('data-dob');
                document.getElementById('modal-age').textContent = button.getAttribute('data-age');
                document.getElementById('modal-address').textContent = button.getAttribute('data-address');
                document.getElementById('modal-applied').textContent = button.getAttribute('data-applied');
                document.getElementById('modal-submitted').textContent = button.getAttribute('data-submitted');
                document.getElementById('modal-status').textContent = button.getAttribute('data-status');
            });
        });
        </script>

</body>
</html>

