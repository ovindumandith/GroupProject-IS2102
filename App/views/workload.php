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
    <link rel="stylesheet" href="../../assets/css/workload.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!--<script src="../../assets/js/workload.js" defer></script>-->
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
        <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
    
    <div class="header-container">
    <div class="header_overlay">
    <div class="overlay-text">
      Trust in your ability to manage – you’ve got this!
      
    </div>
  </div>
  <img src="../../assets/images/workload/93.png" alt="Header Image" class="header-image active" id="headerImage1">
</div>      
    
  

    
    <!-- Content Section (for demonstration) -->
  <div class="main-content">
 
<div class="content">
  <div class="split left">
    <h2>
    <span class="highlight">Stay organized and in control!</span>
    </h2>
    <div class="description">
    <p>
    Our To-Do List helps you break down your tasks, prioritize with ease, 
    and keep track of everything that matters. 
    Conquer your day, one checkbox at a time!
    </p>
    </div>
    <button class="arrow-btn" onclick="location.href='to_do_list.php'">
      <img src="../../assets/images/workload/forward.png" alt="Arrow Icon" class="arrow-icon">
    </button>
  </div>
  <div class="split right">
    <div class="graphic-section">
      <h3>To-Do-List</h3>
      <div class="illustration">
      <img src="../../assets/images/workload/calendar.png" alt="Illustration">
      </div>
    </div>
  </div>

</div>

<div class="content">
  <div class="split left">
    <h4>
    <span class="highlight">Plan ahead, stay at ease</span>
    </h4>
    <div class="description">
      <p>The Event Scheduler lets you map out your commitments, set reminders, and manage deadlines seamlessly. 
        Never miss a moment of success!
      </p>
    </div>
    <button class="arrow-btn" onclick="location.href='schedule_event.php'">
    <img src="../../assets/images/workload/forward.png" alt="Arrow Icon" class="arrow-icon">
    </button>
  </div>
  
  <div class="split right">
    <div class="graphic-section">
      <h3>Schedule Events</h3>
      <div class="illustration">
      <img src="../../assets/images/workload/calendar.png" alt="Illustration">
      </div>
    </div>
  </div>

</div>

  <div class="content">
    <div class="split left">
      <h4>
      <span class="highlight">Master your time, master your life</span>
      </h4>
    
      <div class="description">
      <p>Use our Time Tracking feature to monitor how you spend your hours, stay productive, and balance work with well-deserved breaks. 
        Your time, your rules!
      </p>
      </div>
      <button class="arrow-btn" onclick="location.href='time_tracking.php'">
      <img src="../../assets/images/workload/forward.png" alt="Arrow Icon" class="arrow-icon">
      </button>
    </div>
  
    <div class="split right">
      <div class="graphic-section">
        <h3>Time Tracking</h3>
        <div class="illustration">
        <img src="../../assets/images/workload/timer.png" alt="Illustration">
        </div>
      </div>
    </div>
  </div>

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
