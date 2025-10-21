@php
  use App\Models\Utility;
  $title_text = \App\Models\Utility::getValByName('title_text') ?? 'National Management School';
  $footer_text = \App\Models\Utility::getValByName('footer_text') ?? 'National Service';
  $display_landing_page = \App\Models\Utility::getValByName('display_landing_page');
  
    $setting = \App\Models\Utility::settings();
    $logo = \App\Models\Utility::get_file('uploads/logo');

    $company_logo = $setting['company_logo_dark'] ?? '';
    $company_logos = $setting['company_logo_light'] ?? '';
    $company_small_logo = $setting['company_small_logo'] ?? '';

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>{{ $title_text }}</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link rel="icon"
        href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png')  . '?' . time() }}"
        type="image" sizes="16x16">
  <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: School
  * Template URL: https://bootstrapmade.com/School-bootstrap-education-template/
  * Updated: Jun 19 2025 with Bootstrap v5.3.6
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    .custom-sparkle {
      color: hsla(195, 85%, 55%, 1);
      animation: float 6s ease-in-out infinite;
      width: 4rem;
      height: 4rem;
    }

    /* floating animation */
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .color{
      color: hsla(195, 85%, 55%, 1);
    }

    .bg-color{
      background:hsla(195, 85%, 55%, 1) !important;
      /* background-color: hsla(195, 85%, 55%, 1); */
    }
    .get-started-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%; /* full width */
        margin-top: 20px;
        padding: 12px 16px;
        font-size: 16px;
        font-weight: 600;
        border: none; /* no border */
        border-radius: 12px; /* rounded corners */
        cursor: pointer;
        color: #fff;
        transition: 0.3s ease;
      }

      .get-started-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
}

  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-end">

      <a href="{{route('landing_page')}}" class="logo d-flex align-items-center me-auto" style="background:none !important;color:black;">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        {{-- <img src="https://mnu.edu.mv/wp-content/uploads/2021/12/MNU-Logo-Horizontal-Filled-01-e1638420030168.png" alt=""> --}}
        @if ($setting['cust_darklayout'] && $setting['cust_darklayout'] == 'on')
            <img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png') . '?' . time() }}"
                alt="{{ config('app.name', 'ERPGo-SaaS') }}" class="logo logo-lg">
        @else
            <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png') . '?' . time() }}"
                alt="{{ config('app.name', 'ERPGo-SaaS') }}" class="logo logo-lg">
        @endif
        <h1 class="sitename" style="color:black !important;font-size:20px;"></h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{route('landing_page')}}" class="{{ request()->is('landing-page') ? 'active' : '' }}">Home</a></li>
          <!-- <li class="dropdown"><a href="about.html"><span>About</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="about.html">About Us</a></li>
              <li><a href="admissions.html">Admissions</a></li>
              <li><a href="academics.html">Academics</a></li>
              <li><a href="faculty-staff.html">Faculty &amp; Staff</a></li>
              <li><a href="campus-facilities.html">Campus &amp; Facilities</a></li>
            </ul>
          </li> -->
          <!-- <li><a href="students-life.html">About</a></li> -->
          <!-- <li><a href="students-life.html">Students Portal</a></li> -->
          <!-- <li><a href="news.html">News</a></li> -->
          <!-- <li><a href="events.html">Events</a></li> -->
          <!-- <li><a href="alumni.html">Alumni</a></li> -->
          <!-- <li class="dropdown"><a href="#"><span>More Pages</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="news-details.html">News Details</a></li>
              <li><a href="event-details.html">Event Details</a></li>
              <li><a href="privacy.html">Privacy</a></li>
              <li><a href="terms-of-service.html">Terms of Service</a></li>
              <li><a href="404.html">Error 404</a></li>
              <li><a href="starter-page.html">Starter Page</a></li>
            </ul>
          </li> -->

          <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li> -->
<li>
    <a href="{{ route('student.login') }}"
       class="{{ request()->is('student/register') || request()->is('student/login') ? 'active' : '' }}">
       Student Portal
    </a>
</li>

<li>
    <a href="{{ route('job.login') }}"
       class="{{ request()->is('job-portal') ? 'active' : '' }}">
       Job Portal
    </a>
</li>
          <li><a href="{{route('student_contact')}}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>
          <!-- Using anchor tag (recommended for navigation) -->
          <!-- <a href="{{ route('student.register') }}" class="btn btn-success px-4 py-3 fw-bold text-white">
            Sign Up
          </a> -->
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="hero-wrapper">
        <div class="container">
          <div class="row align-items-center" style="margin-top: -30px;">
            <div class="col-12 hero-content" data-aos="fade-right" data-aos-delay="100">
              <div class="d-flex justify-content-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="custom-sparkle"
                    width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-sparkles w-16 h-16 text-primary animate-float">
                  <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                  <path d="M20 3v4"></path>
                  <path d="M22 5h-4"></path>
                  <path d="M4 17v2"></path>
                  <path d="M5 18H3"></path>
                </svg>
              </div>
              <h1 style="text-align:center;" class="color">National Service</h1>
              <p style="text-align:center;">Building stronger communities through service, education, and opportunity. Join <br> thousands of young Maldivians in shaping our nation's future with purpose and pride.</p>
             <div class="action-buttons mt-3 d-flex justify-content-center">
              <a href="#" class="btn btn-primary me-2" style="background: hsla(195, 85%, 55%, 1) !important;border:none;padding:10px 36px !important;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rocket mr-3 w-5 h-5" data-lov-id="src/pages/Index.tsx:71:18" data-lov-name="Rocket" data-component-path="src/pages/Index.tsx" data-component-line="71" data-component-file="Index.tsx" data-component-name="Rocket" data-component-content="%7B%22className%22%3A%22mr-3%20w-5%20h-5%22%7D"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path></svg>
              Apply Now</a>
              <a href="{{ route('student.login') }}" class="btn btn-secondary" style="padding:10px 36px !important;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user mr-3 w-5 h-5" data-lov-id="src/pages/Index.tsx:77:18" data-lov-name="User" data-component-path="src/pages/Index.tsx" data-component-line="77" data-component-file="Index.tsx" data-component-name="User" data-component-content="%7B%22className%22%3A%22mr-3%20w-5%20h-5%22%7D"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              Student Portal</a>
            </div>

            </div>
          </div>
        </div>
      </div>

      <div class="feature-cards-wrapper mt-1 mb-1" data-aos="fade-up" data-aos-delay="300">
  <div class="container-fluid card" style="padding: 0px !important;border: none !important;border-radius: 0px !important;">
    <div class="row gy-5 mt-5 mb-5 justify-content-center">
      <div class="col-lg-8"> <!-- 👈 ye center aligned container -->
        <div class="row justify-content-center">

          <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="feature-card" style="box-shadow:none !important; padding:20px !important; border-radius:12px !important; background:#fff !important; text-align:center !important;">
              <div class="feature-content d-flex flex-column align-items-center text-center" style="gap:10px !important;">

                <!-- SVG with card styling -->
                <div style="padding:15px !important; border-radius:50% !important; background:rgb(59 130 246 / 0.1) !important; display:inline-flex !important; align-items:center !important; justify-content:center !important; margin-bottom:12px !important;">
                  <svg style="color: rgb(59 130 246 / var(--tw-text-opacity, 1)) !important; width:40px !important; height:40px !important;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  </svg>
                </div>

                <!-- Bold 4200 -->
                <p style="font-size:26px !important; font-weight:700 !important; margin:0 !important;">4,200</p>

                <!-- Faded Active Students -->
                <span style="font-size:14px !important; color:#6c757d !important; opacity:0.8 !important;">Active Students</span>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="feature-card" style="box-shadow:none !important; padding:20px !important; border-radius:12px !important; background:#fff !important; text-align:center !important;">
              <div class="feature-content d-flex flex-column align-items-center text-center" style="gap:10px !important;">

                <!-- SVG with card styling -->
                <div style="padding:15px !important; border-radius:50% !important; background:rgb(59 130 246 / 0.1) !important; display:inline-flex !important; align-items:center !important; justify-content:center !important; margin-bottom:12px !important;">
                  <svg style="color: rgb(34 197 94 / var(--tw-text-opacity, 1)) !important; width:40px !important; height:40px !important;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-8 h-8 text-green-500" data-lov-id="src/pages/Index.tsx:93:18" data-lov-name="stat.icon" data-component-path="src/pages/Index.tsx" data-component-line="93" data-component-file="Index.tsx" data-component-name="stat.icon" data-component-content="%7B%7D"><path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path><circle cx="12" cy="8" r="6"></circle></svg>
                </div>

                <!-- Bold 4200 -->
                <p style="font-size:26px !important; font-weight:700 !important; margin:0 !important;">8,945</p>

                <!-- Faded Active Students -->
                <span style="font-size:14px !important; color:#6c757d !important; opacity:0.8 !important;">Completed Training
</span>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="feature-card" style="box-shadow:none !important; padding:20px !important; border-radius:12px !important; background:#fff !important; text-align:center !important;">
              <div class="feature-content d-flex flex-column align-items-center text-center" style="gap:10px !important;">

                <!-- SVG with card styling -->
                <div style="padding:15px !important; border-radius:50% !important; background:rgb(59 130 246 / 0.1) !important; display:inline-flex !important; align-items:center !important; justify-content:center !important; margin-bottom:12px !important;">
                  <svg style="color: rgb(168 85 247 / var(--tw-text-opacity, 1)) !important; width:40px !important; height:40px !important;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase w-8 h-8 text-purple-500" data-lov-id="src/pages/Index.tsx:93:18" data-lov-name="stat.icon" data-component-path="src/pages/Index.tsx" data-component-line="93" data-component-file="Index.tsx" data-component-name="stat.icon" data-component-content="%7B%7D"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path><rect width="20" height="14" x="2" y="6" rx="2"></rect></svg>
                </div>

                <!-- Bold 4200 -->
                <p style="font-size:26px !important; font-weight:700 !important; margin:0 !important;">6,811</p>

                <!-- Faded Active Students -->
                <span style="font-size:14px !important; color:#6c757d !important; opacity:0.8 !important;">Job Placements</span>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="feature-card" style="box-shadow:none !important; padding:20px !important; border-radius:12px !important; background:#fff !important; text-align:center !important;">
              <div class="feature-content d-flex flex-column align-items-center text-center" style="gap:10px !important;">

                <!-- SVG with card styling -->
                <div style="padding:15px !important; border-radius:50% !important; background:rgb(59 130 246 / 0.1) !important; display:inline-flex !important; align-items:center !important; justify-content:center !important; margin-bottom:12px !important;">
                  <svg style="color: rgb(249 115 22 / var(--tw-text-opacity, 1)) !important; width:40px !important; height:40px !important;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 w-8 h-8 text-orange-500" data-lov-id="src/pages/Index.tsx:93:18" data-lov-name="stat.icon" data-component-path="src/pages/Index.tsx" data-component-line="93" data-component-file="Index.tsx" data-component-name="stat.icon" data-component-content="%7B%7D"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
                </div>

                <!-- Bold 4200 -->
                <p style="font-size:26px !important; font-weight:700 !important; margin:0 !important;">12</p>

                <!-- Faded Active Students -->
                <span style="font-size:14px !important; color:#6c757d !important; opacity:0.8 !important;">Training Centers
</span>
              </div>
            </div>
          </div>

          <!-- Repeat 3 more col-lg-3 for other cards -->

        </div>
      </div>
    </div>
  </div>
</div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    @if($display_landing_page == 'on')
    <section id="about" class="about section" style="padding: 0px 0px 70px 0px !important;">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row mt-5">
          <div class="col-lg-12">
            <div class="core-values" data-aos="fade-up" data-aos-delay="500">
              <h3 class="text-center mb-4">Your Journey Starts Here</h3>
              <p style="text-align:center !important; margin: 40px 0px !important;">Access all the tools and resources you need for your National Service experience</p>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                  <div class="value-card">
                    <div class="value-icon"
                        style="border-radius:12px !important;
                                padding:15px !important;
                                display:inline-flex !important;
                                align-items:center !important;
                                justify-content:center !important;
                                background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%) !important;">
                      <svg xmlns="http://www.w3.org/2000/svg"
                          width="32" height="32"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="lucide lucide-rocket text-white"
                          style="color:#fff !important;">
                        <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
                        <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
                        <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path>
                        <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path>
                      </svg>
                    </div>
                    <h5><b>Student Registration</b></h5>
                    <p class="m-2">Apply for National Service training programs with streamlined process</p>
                    <button class="get-started-btn" style="background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%);">
                        Get Started <span style="margin:5px;"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-sparkles ml-2 w-4 h-4">
                          <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                          <path d="M20 3v4"></path>
                          <path d="M22 5h-4"></path>
                          <path d="M4 17v2"></path>
                          <path d="M5 18H3"></path>
                        </svg>
                      </button>

                  </div>
                </div>

                <div class="col">
                  <div class="value-card">
                    <div class="value-icon"
                        style="border-radius:12px !important;
                                padding:15px !important;
                                display:inline-flex !important;
                                align-items:center !important;
                                justify-content:center !important;
                                background: linear-gradient(90deg, hsl(86.69deg 85% 55%) 0%, hsl(195, 85%, 45%) 100%) !important;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-8 h-8 text-white" data-lov-id="src/pages/Index.tsx:120:20" data-lov-name="feature.icon" data-component-path="src/pages/Index.tsx" data-component-line="120" data-component-file="Index.tsx" data-component-name="feature.icon" data-component-content="%7B%22className%22%3A%22w-8%20h-8%20text-white%22%7D"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <h5><b>Student Dashboard</b></h5>
                    <p class="m-2">Track your application status and monitor your progress journey</p>
                    <button class="get-started-btn" style="background: linear-gradient(90deg, hsl(86.69deg 85% 55%) 0%, hsl(195, 85%, 45%) 100%);">
                        Get Started <span style="margin:5px;"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-sparkles ml-2 w-4 h-4">
                          <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                          <path d="M20 3v4"></path>
                          <path d="M22 5h-4"></path>
                          <path d="M4 17v2"></path>
                          <path d="M5 18H3"></path>
                        </svg>
                      </button>

                  </div>
                </div>
                <div class="col">
                  <div class="value-card">
                    <div class="value-icon"
                        style="border-radius:12px !important;
                                padding:15px !important;
                                display:inline-flex !important;
                                align-items:center !important;
                                justify-content:center !important;
                                background: linear-gradient(90deg, hsl(298.38deg 85% 55%) 0%, hsl(195, 85%, 45%) 100%) !important;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase w-8 h-8 text-white" data-lov-id="src/pages/Index.tsx:120:20" data-lov-name="feature.icon" data-component-path="src/pages/Index.tsx" data-component-line="120" data-component-file="Index.tsx" data-component-name="feature.icon" data-component-content="%7B%22className%22%3A%22w-8%20h-8%20text-white%22%7D"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path><rect width="20" height="14" x="2" y="6" rx="2"></rect></svg>
                    </div>
                    <h5><b>Job Portal</b></h5>
                    <p class="m-2">AppDiscover employment opportunities and launch your career</p>
                    <button class="get-started-btn" style="background: linear-gradient(90deg, hsl(298.38deg 85% 55%) 0%, hsl(195, 85%, 45%) 100%);">
                        Get Started <span style="margin:5px;"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-sparkles ml-2 w-4 h-4">
                          <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                          <path d="M20 3v4"></path>
                          <path d="M22 5h-4"></path>
                          <path d="M4 17v2"></path>
                          <path d="M5 18H3"></path>
                        </svg>                      </button>

                  </div>
                </div>
                <div class="col">
                  <div class="value-card">
                    <div class="value-icon"
                        style="border-radius:12px !important;
                                padding:15px !important;
                                display:inline-flex !important;
                                align-items:center !important;
                                justify-content:center !important;
                                background: linear-gradient(90deg, hsl(17.77deg 100% 59.81%) 0%, hsl(195, 85%, 45%) 100%) !important;">
                      <svg xmlns="http://www.w3.org/2000/svg"
                          width="32" height="32"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="lucide lucide-rocket text-white"
                          style="color:#fff !important;">
                        <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
                        <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
                        <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path>
                        <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path>
                      </svg>
                    </div>
                    <h5><b>Admin Portal</b></h5>
                    <p class="m-2">Comprehensive administrative management and system oversight</p>
                    <button class="get-started-btn" style="background: linear-gradient(90deg, hsl(17.77deg 100% 59.81%) 0%, hsl(195, 85%, 45%) 100%);">
                        Get Started <span style="margin:5px;"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-sparkles ml-2 w-4 h-4">
                          <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                          <path d="M20 3v4"></path>
                          <path d="M22 5h-4"></path>
                          <path d="M4 17v2"></path>
                          <path d="M5 18H3"></path>
                        </svg>
                      </button>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
    @endif

  </main>

  <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4 justify-content-center">
        <div class="col-lg-4 col-md-6 footer-about" style="margin-right:180px !important;">
  <a href="{{route('landing_page')}}" class="logo d-flex align-items-center" style="background:none !important;">
    <span class="sitename">
      @if ($setting['cust_darklayout'] && $setting['cust_darklayout'] == 'on')
            <img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png') . '?' . time() }}"
                alt="{{ config('app.name', 'ERPGo-SaaS') }}" class="logo logo-lg">
      @else
          <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png') . '?' . time() }}"
              alt="{{ config('app.name', 'ERPGo-SaaS') }}" class="logo logo-lg">
      @endif
      {{-- <img src="https://mnu.edu.mv/wp-content/uploads/2021/12/MNU-Logo-Horizontal-Filled-01-e1638420030168.png" alt=""> --}}
      {{ $footer_text }}
    </span>
  </a>
  <div class="footer-contact pt-3">
    <p>Building stronger communities through service, education, and opportunity for all young Maldivians.</p>
  </div>
</div>


        <div class="col-lg-2 col-md-6 footer-links">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="#">Apply Now</a></li>
            <li><a href="{{ route('student.register') }}">Student Portal</a></li>
            <li><a href="#">Job Portal</a></li>
            <li><a href="#">Admin Portal</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-6 footer-links">
          <h4>Contact</h4>
          <ul>
            <li><a href="#">+960 123-4567</a></li>
            <li><a href="#">info@ns.gov.mv</a></li>
            <li><a href="#">Malé, National School</a></li>
          </ul>
        </div>

      </div>
    </div>p

    <div class="container copyright text-center mt-4">
      <p>© 2024 National Service. All rights reserved.</p>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" style="background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%);
           color: #fff;
           border: none;
           padding: 10px 20px;
           border-radius: 6px;
           cursor: pointer;" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
  <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
  <script src="{{asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
  <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{asset('assets/js-front/main.js')}}"></script>

</body>

</html>
