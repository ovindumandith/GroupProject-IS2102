<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php?error=unauthorized');
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
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../assets/css/about_us.css" />
    <script src="../../assets/js/about_us.js"></script>
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
          <li><a href="home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="#">Stress Monitoring</a></li>
              <li><a href="#">Relaxation Activities</a></li>
              <li><a href="#">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="#">Academic Help</a></li>
          <li><a href="#">Counseling</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup"><b>Profile</b></button>
        <button class="login"><b>Log Out</b></button>
      </div>
    </header>

    <div class="about-hero">
        <h1>About Our Stress Management Project</h1>
        <div class="hero-wave"></div>
    </div>

    <section class="about-content">
        <div class="about-card">
            <h2>Who We Are</h2>
            <p>The Stress Management and Monitoring system is a dedicated support system designed to empower students throughout their academic journey. We understand the challenges that students face and are committed to providing comprehensive assistance to ensure their success.
              This is done based in the modules IS-2102 as 2nd Year Undegraduates of the University of Colombo School of Computing.</p>
            </p>
        </div>

        <div class="vision-mission">
            <div class="vision">
                <div class="icon">👁️</div>
                <h2>Our Vision</h2>
                <p>We aim to create a nurturing space where students can seamlessly monitor and manage stress while excelling academically and thriving in their university journey.</p>
            </div>
            <div class="mission">
                <div class="icon">🎯</div>
                <h2>Our Mission</h2>
                <p>Delivering innovative, science-driven stress management solutions to promote mental well-being and academic success globally.</p>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-item">
                <span class="stat-number" data-target="5000">0</span>
                <span class="stat-label">Students Helped</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="100">0</span>
                <span class="stat-label">Expert Advisors</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-target="95">0</span>
                <span class="stat-label">Success Rate</span>
            </div>
        </div>

        <div class="team-section">
            <h2>Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=John" alt="John Smith">
                    </div>
                    <h3>John Smith</h3>
                    <p class="role">Academic Director</p>
                    <p class="bio">Ph.D. in Education with 15+ years of experience in academic counseling.</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=Sarah" alt="Sarah Johnson">
                    </div>
                    <h3>Sarah Johnson</h3>
                    <p class="role">Student Support Lead</p>
                    <p class="bio">Specialized in student mentoring and academic guidance.</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=Michael" alt="Michael Chen">
                    </div>
                    <h3>Michael Chen</h3>
                    <p class="role">Technical Advisor</p>
                    <p class="bio">Expert in educational technology and digital learning solutions.</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=Emma" alt="Emma Davis">
                    </div>
                    <h3>Emma Davis</h3>
                    <p class="role">Resource Coordinator</p>
                    <p class="bio">Manages academic resources and support materials.</p>
                </div>
                                <div class="team-member">
                    <div class="member-image">
                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=Emma" alt="Emma Davis">
                    </div>
                    <h3>Emma Davis</h3>
                    <p class="role">Resource Coordinator</p>
                    <p class="bio">Manages academic resources and support materials.</p>
                </div>
                                <div class="team-member">
                    <div class="member-image">
                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=Barry" alt="Emma Davis">
                    </div>
                    <h3>Emma Davis</h3>
                    <p class="role">Resource Coordinator</p>
                    <p class="bio">Manages academic resources and support materials.</p>
                </div>
            </div>
        </div>
    </section>
    

    <!-- Footer Section -->
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Relax and Refresh while Excelling in your Studies</p>
          <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
        </div>
        <div class="footer-section">
          <h3>Services</h3>
          <ul>
            <li><a href="#">Stress Monitoring</a></li>
            <li><a href="#">Relaxation Activities</a></li>
            <li><a href="#">Academic Help</a></li>
            <li><a href="#">Counseling</a></li>
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
            <a href="#"><img src="../../assets/images/facebook.png" alt="Facebook" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/twitter.png" alt="Twitter" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/instagram.png" alt="Instagram" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/youtube.png" alt="YouTube" /></a>
          </li>
        </ul>
      </div>
      <div class="footer-bottom">
        <p>copyright 2024 @RelaxU all rights reserved</p>
      </div>
    </footer>
  </body>
</html>
