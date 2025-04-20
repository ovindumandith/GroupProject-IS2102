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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/home.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/workload.css" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Font Awesome CDN link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!--<script src="../../assets/js/workload.js" defer></script>-->
</head>

<i>
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

  <header class="hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>"Stay Organized, Stay Sane"</h1>
            
            <p class="description">
            Stay organized, stay balanced. 
            Our workload management tools are designed to help you plan, prioritize, and manage your academic responsibilities with ease. 
            Whether you're juggling assignments, exams, or extracurriculars, we've got the support you need to reduce stress and stay on track
            </p>
        </div>
    </header>

  <div class="main-content">
  <div class="card-container">
        <div class="service-card">
            <div class="card-image">
                <img src="../../assets/images/workload/104.png" alt="Physical therapist working with a patient">
            </div>
            <div class="card-content">
                <h2 class="card-title">Task Planner</h2>
                <p class="card-description">
                
                Our Task Planner helps you break down your academic workload into manageable steps. 
                Create daily to-do lists, set priorities, and check off tasks as you complete them. 
                It’s your personal assistant for staying organized and focused.

</p>
                <a href="to_do_list.php" class="read-more-btn">Explore</a>
            </div>
        </div>
    </div>

    <div class="card-container">
        <div class="service-card">
            <div class="card-image">
                <img src="../../assets/images/workload/101.png" alt="Physical therapist working with a patient">
            </div>
            <div class="card-content">
                <h2 class="card-title">Event scheduler</h2>
                <p class="card-description">
                Use the Event Scheduler to keep track of classes, deadlines, study sessions, and social activities—all in one place
                Set reminders so you never miss an important event and maintain a balanced schedule that works for you


                </p>
                <a href="schedule_event.php" class="read-more-btn">Explore</a>
            </div>
        </div>
    </div>
    <div class="card-container">
        <div class="service-card">
            <div class="card-image">
                <img src="../../assets/images/workload/103.png" alt="Physical therapist working with a patient">
            </div>
            <div class="card-content">
                <h2 class="card-title">Time Tracker</h2>
                <p class="card-description">
                Set a goal time for each task and track your real-time progress with a built-in timer. 
                Hit "Start" when you begin, and "Stop" when you're done. 
                Compare your actual time with your goal and receive badges or friendly suggestions to improve time management
                </p>
                <a href="time_tracking.php" class="read-more-btn">Explore</a>
            </div>
        </div>
    </div>
  </div>

  
  
 </body>
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