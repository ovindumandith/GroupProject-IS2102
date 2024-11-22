<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../assets/css/home.css" type="text/css" />

    <script src="../../assets/js/hero_slider.js" defer></script>
    <script src="../../assets/js/testimonial_slider.js" defer></script>
    <script src="../../assets/js/counter.js" defer></script>
  </head>
  <body>
    <!-- Header Section -->
    <header class="header">
      <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
        <h1>RelaxU</h1>
      </div>
      <nav class="navbar">
        <ul>
          <li><a href="../views/home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
              <li><a href="../views/relaxation_activities.php">Relaxation Activities</a></li>
              <li><a href="#">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="#">Academic Help</a></li>
          <li><a href="../controller/CounselorController.php?action=list">Counseling</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <!-- Content Section (for demonstration) -->

    <section class="hero">
      <div class="hero-content">
        <div class="hero-text">
          <h1>
            Healthy Minds, Happy Lives
            <span>Student Mental Health Consultancy</span>
          </h1>
          <p>Empowering you to live a balanced and fulfilling life</p>
          <button class="get-started-btn">Get started</button><br />

          <!-- Add the Counter here -->
          <div class="counter-container">
            <h1 class="counter" data-target="100">0+</h1>
            <p>Students Helped</p>
          </div>

          <div class="social-icons"></div>
        </div>

        <div class="image-slider">
          <div class="image-slide active">
            <img src="../../assets/images/hero_img2.jpg" alt="Image 1" />
            <h3>Image 1 Caption</h3>
          </div>
          <div class="image-slide">
            <img src="../../assets/images/hero_img.jpg" alt="Image 2" />
            <h3>Image 2 Caption</h3>
          </div>
          <div class="image-slide">
            <img src="../../assets/images/hero_img3.jpg" alt="Image 3" />
            <h3>Image 3 Caption</h3>
          </div>
          <div class="slider-buttons">
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
          </div>
        </div>
      </div>
    </section>

    <section class="why-choose-us">
      <h2>Why Our Stress Monitoring App is the Best Choice</h2>
      <div class="features">
        <div class="feature-item">
          <img src="../../assets/images/algo.png" alt="Holistic approach icon" />
          <h3>Holistic Approach</h3>
          <p>
            Use of advanced algorithms and techniques to monitor your stress
            level.
          </p>
        </div>
        <div class="feature-item highlight">
          <img src="../../assets/images/team.png" alt="Expertise Team icon" />
          <h3>Expertise Team</h3>
          <p>
            A team consisting of experienced counselors and staff to guide you.
          </p>
        </div>
        <div class="feature-item">
          <img src="../../assets/images/academic.png" alt="Accessibility icon" />
          <h3>Accessibility</h3>
          <p>
            Advanced academic tools and features for managing a balanced
            academic life.
          </p>
        </div>
      </div>
    </section>
    <section class="services-section">
      <h2>Services We Offer</h2>

      <div class="service-item">
        <div class="service-icon">
          <img
            src="../../assets/images/monitoring.png"
            alt="Real Time Monitoring Icon"
          />
        </div>
        <div class="service-content">
          <h3>Real Time Stress Monitoring and Management</h3>
          <p>
            We offer a comprehensive stress level monitoring and reporting
            system that helps you understand and manage your stress. By
            answering a set of questions and engaging in a typing exercise, you
            can receive personalized insights into your stress levels and track
            your progress over time.
          </p>
          <button class="explore-btn">Explore</button>
        </div>
      </div>

      <div class="service-item">
        <div class="service-icon">
          <img
            src="../../assets/images/support.png"
            alt="Mindfulness Tools Icon"
          />
        </div>
        <div class="service-content">
          <h3>Mindfulness, Relaxation, and Productivity Tools</h3>
          <p>
            Our platform includes a variety of mindfulness and relaxation tools
            designed to help you manage stress effectively. Access guided
            activities and stay organized and manage your academic workload with
            our productivity tools.
          </p>
          <button class="explore-btn">Explore</button>
        </div>
      </div>

      <div class="service-item">
        <div class="service-icon">
          <img
            src="../../assets/images/community.png"
            alt="Community Management Icon"
          />
        </div>
        <div class="service-content">
          <h3>User and Community Management</h3>
          <p>
            Engage with a supportive community of peers through our forums and
            support groups. Connect with fellow students, share experiences, and
            access valuable resources.
          </p>
          <button class="explore-btn">Explore</button>
        </div>
      </div>

      <div class="service-item">
        <div class="service-icon">
          <img
            src="../../assets/images/counsling.png"
            alt="Counseling Services Icon"
          />
        </div>
        <div class="service-content">
          <h3>Confidential Counseling Services</h3>
          <p>
            Access confidential counseling services from both student-level
            counselors and higher-level counselors/psychiatrists. Receive
            personalized support and guidance to address your mental health
            needs, ensuring your privacy and comfort at all times.
          </p>
          <button class="explore-btn">Explore</button>
        </div>
      </div>
      <div class="service-item">
        <div class="service-icon">
          <img
            src="../../assets/images/academic-apparel.png"
            alt="Academic Support Icon"
          />
        </div>
        <div class="service-content">
          <h3>Academic Support</h3>
          <p>
            Tell us your academic needs and we will provide you with the best
            solutions. We offer a variety of academic support services,
            including tutoring, study groups, and academic advising.
          </p>
          <button class="explore-btn">Explore</button>
        </div>
      </div>
    </section>
    <div class="testimonial-slider">
      <div class="testimonial active">
        <p>
          "The team at Mental Health Consultancy has truly changed my life. The
          support and guidance I've received are unparalleled. Highly
          recommend!"
        </p>
        <h3>– John Doe</h3>
      </div>
      <div class="testimonial">
        <p>
          "Their expertise and compassionate approach made all the difference.
          I’ve learned how to better manage my stress and live a more balanced
          life."
        </p>
        <h3>– Jane Smith</h3>
      </div>
      <div class="testimonial">
        <p>
          "The stress monitoring app they offer is such a game changer. I never
          knew how simple tracking my mental well-being could be!"
        </p>
        <h3>– Mark Wilson</h3>
      </div>
    </div>
    <div class="slider-dots">
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>

    <div class="slider-controls">
      <span class="prev">‹</span>
      <span class="next">›</span>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Relax and Refresh while Excelling in your Studies</p>
          <img
            id="footer-logo"
            src="../../assets/images/logo.jpg"
            alt="RelaxU Logo"
          />
        </div>
        <div class="footer-section">
          <h3>Services</h3>
          <ul>
            <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
            <li><a href="../views/relaxation_activities.php">Relaxation Activities</a></li>
            <li><a href="#">Academic Help</a></li>
            <li><a href="../views/counselling/counsellor_index.php">Counseling</a></li>
            <li><a href="#">Community</a></li>
            <li><a href="#">Workload Managment Tools</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h3>Contact</h3>
          <p><i class="fa fa-phone"></i> +14 5464 8272</p>
          <p><i class="fa fa-envelope"></i> rona@domain.com</p>
          <p><i class="fa fa-map-marker"></i> Lazy Tower 192, Burn Swiss</p>
        </div>
        <div class="footer-section">
          <h3>Links</h3>
          <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms Of Use</a></li>
          </ul>
        </div>
      </div>
      <div class="social-media">
        <ul>
          <li>
            <a href="#"
              ><img src="../../assets/images/facebook.png" alt="Facebook"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="../../assets/images/twitter.png" alt="Twitter"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="../../assets/images/instagram.png" alt="Instagram"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="../../assets/images/youtube.png" alt="YouTube"
            /></a>
          </li>
        </ul>
      </div>
      <div class="footer-bottom">
        <p>copyright 2024 @RelaxU all rights reserved</p>
      </div>
    </footer>
  </body>
</html>
