<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>National Management School</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="https://mnu.edu.mv/wp-content/uploads/2021/12/MNU-Logo-Horizontal-Filled-01-e1638420030168.png" rel="icon">
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
</head>

<body class="contact-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-end">

      <a href="{{route('landing_page')}}" class="logo d-flex align-items-center me-auto" style="background:none !important;color:black;">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
         <img src="https://mnu.edu.mv/wp-content/uploads/2021/12/MNU-Logo-Horizontal-Filled-01-e1638420030168.png" alt="">
        <!-- <h1 class="sitename" style="color:black !important;font-size:10px;"></h1> -->
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
       class="{{ request()->is('student/register') || (request()->is('student/login') && !request()->query('job_portal')) ? 'active' : '' }}">
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

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Contact</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Contact</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="contact-main-wrapper">
          <div class="map-wrapper">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>

          <div class="contact-content">
            <div class="contact-cards-container" data-aos="fade-up" data-aos-delay="300">
              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <div class="contact-text">
                  <h4 style="
  background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
">Location</h4>
                  <p>8721 Broadway Avenue, New York, NY 10023</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="contact-text">
                  <h4 style="
  background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
">Email</h4>
                  <p>info@examplecompany.com</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-telephone"></i>
                </div>
                <div class="contact-text">
                  <h4 style="
  background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
">Call</h4>
                  <p>+1 (212) 555-7890</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-clock"></i>
                </div>
                <div class="contact-text">
                  <h4 style="
  background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
">Open Hours</h4>
                  <p>Monday-Friday: 9AM - 6PM</p>
                </div>
              </div>
            </div>

            <div class="contact-form-container" data-aos="fade-up" data-aos-delay="400">
              <h3 style="
  background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
">Get in Touch</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua consectetur adipiscing.</p>

              <form action="forms/contact.php" method="post" class="php-email-form">
                <div class="row">
                  <div class="col-md-6 form-group">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required="">
                  </div>
                  <div class="col-md-6 form-group mt-3 mt-md-0">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required="">
                  </div>
                </div>
                <div class="form-group mt-3">
                  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required="">
                </div>
                <div class="form-group mt-3">
                  <textarea class="form-control" name="message" rows="5" placeholder="Message" required=""></textarea>
                </div>

                <div class="my-3">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>
                </div>

                <div class="form-submit">
                  <button type="submit" style="background: linear-gradient(90deg, hsl(195deg 91.06% 30.27%) 0%, hsl(195, 85%, 45%) 100%);
           color: #fff; 
           border: none;
           padding: 10px 20px;
           border-radius: 6px;
           cursor: pointer;">Send Message</button>
                  <div class="social-links">
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4 justify-content-center">
        <div class="col-lg-4 col-md-6 footer-about" style="margin-right:180px !important;">
  <a href="{{route('landing_page')}}" class="logo d-flex align-items-center" style="background:none !important;">
    <span class="sitename">
      <img src="https://mnu.edu.mv/wp-content/uploads/2021/12/MNU-Logo-Horizontal-Filled-01-e1638420030168.png" alt=""> 
      National Service
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