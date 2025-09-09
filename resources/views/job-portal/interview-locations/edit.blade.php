<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Edit Interview Location</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #4f7cff;
            --warning-yellow: #ffc107;
            --success-green: #28a745;
            --danger-red: #dc3545;
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

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .btn-primary {
            background: var(--primary-blue);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #3d6bff;
            transform: translateY(-1px);
        }

        .facility-checkbox {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
            margin: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .facility-checkbox:hover {
            background: #e9ecef;
        }

        .facility-checkbox input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .facility-checkbox.checked {
            background: #d1ecf1;
            border-color: var(--primary-blue);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('job-portal.interview-locations.index') }}">
                <i class="bi bi-arrow-left me-2"></i>
                Edit Interview Location
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('job-portal.dashboard') }}">
                            <i class="bi bi-house me-2"></i>Dashboard
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('job-portal.logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-card">
                    <h4 class="mb-4">
                        <i class="bi bi-geo-alt me-2"></i>Edit Interview Location: {{ $location->name }}
                    </h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('job-portal.interview-locations.update', $location->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Location Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $location->name) }}" required>
                            <div class="form-text">e.g., National Service Training Center - Male</div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" 
                                      required>{{ old('address', $location->address) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                           value="{{ old('city', $location->city) }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="atoll" class="form-label">Atoll</label>
                                    <input type="text" class="form-control" id="atoll" name="atoll" 
                                           value="{{ old('atoll', $location->atoll) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">Contact Person</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                           value="{{ old('contact_person', $location->contact_person) }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Contact Phone</label>
                                    <input type="tel" class="form-control" id="contact_phone" name="contact_phone" 
                                           value="{{ old('contact_phone', $location->contact_phone) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                   value="{{ old('contact_email', $location->contact_email) }}">
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" 
                                   value="{{ old('capacity', $location->capacity) }}" min="1" required>
                            <div class="form-text">Maximum number of candidates that can be interviewed at once</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Available Facilities</label>
                            <div class="row">
                                @php
                                    $selectedFacilities = old('available_facilities', $location->available_facilities ?? []);
                                @endphp
                                
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('parking', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="parking" 
                                               {{ in_array('parking', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-car-front me-1"></i>Parking
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('accommodation', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="accommodation" 
                                               {{ in_array('accommodation', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-house me-1"></i>Accommodation
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('cafeteria', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="cafeteria" 
                                               {{ in_array('cafeteria', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-cup-hot me-1"></i>Cafeteria
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('medical_room', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="medical_room" 
                                               {{ in_array('medical_room', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-heart-pulse me-1"></i>Medical Room
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('swimming_pool', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="swimming_pool" 
                                               {{ in_array('swimming_pool', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-water me-1"></i>Swimming Pool
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('running_track', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="running_track" 
                                               {{ in_array('running_track', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-activity me-1"></i>Running Track
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('computer_lab', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="computer_lab" 
                                               {{ in_array('computer_lab', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-laptop me-1"></i>Computer Lab
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('auditorium', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="auditorium" 
                                               {{ in_array('auditorium', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-mic me-1"></i>Auditorium
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="facility-checkbox {{ in_array('wifi', $selectedFacilities) ? 'checked' : '' }}">
                                        <input type="checkbox" name="available_facilities[]" value="wifi" 
                                               {{ in_array('wifi', $selectedFacilities) ? 'checked' : '' }}>
                                        <i class="bi bi-wifi me-1"></i>WiFi
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $location->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Location
                                </label>
                                <div class="form-text">Uncheck to disable this location for new interviews</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('job-portal.interview-locations.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update Location
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-fill atoll based on city
        const cityAtollMap = {
            'Male': 'Kaafu',
            'Addu': 'Seenu',
            'Kulhudhuffushi': 'Haa Dhaalu',
            'Thinadhoo': 'Gaafu Dhaalu',
            'Fuvahmulah': 'Gnaviyani',
            'Eydhafushi': 'Baa',
            'Mahibadhoo': 'Alifu Dhaalu',
            'Dhidhdhoo': 'Haa Alifu',
            'Kulhudhuffushi': 'Haa Dhaalu',
            'Thulusdhoo': 'Kaafu',
            'Hulhumale': 'Kaafu'
        };

        document.getElementById('city').addEventListener('input', function() {
            const city = this.value;
            const atollField = document.getElementById('atoll');
            
            if (cityAtollMap[city] && !atollField.value) {
                atollField.value = cityAtollMap[city];
            }
        });

        // Format phone number
        document.getElementById('contact_phone').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('960')) {
                value = '+' + value;
            } else if (value.length > 0) {
                value = '+960' + value;
            }
            this.value = value;
        });

        // Handle facility checkbox styling
        document.querySelectorAll('.facility-checkbox input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const label = this.closest('.facility-checkbox');
                if (this.checked) {
                    label.classList.add('checked');
                } else {
                    label.classList.remove('checked');
                }
            });
        });
    </script>
</body>
</html>



