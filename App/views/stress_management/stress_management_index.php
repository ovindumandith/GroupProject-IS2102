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
      href="../../../assets/css/header_footer.css"
    />
    <link rel="stylesheet" href="../../../assets/css/stress_management_index.css" type="text/css" />
    
 
    
  </head>
  <body>
        <!-- Toast Container -->

    <!-- Header Section -->
    <header class="header">
      <div class="logo">
        <img src="../../../assets/images/logo.jpg" alt="RelaxU Logo" />
        <h1>RelaxU</h1>
      </div>
      <nav class="navbar">
        <ul>
          <li><a href="../../views/home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="../../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
              <li><a href="../../views/relaxation_activities.php">Relaxation Activities</a></li>
              <li><a href="#">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="../../views/Academic_Help.php">Academic Help</a></li>
          <li><a href="../../controller/CounselorController.php?action=list">Counseling</a></li>
          <li><a href="../../controller/CommunityController.php?action=list">Community</a></li>
          <li><a href="../../views/About_Us.php">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
        <form action="../../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
<div id="stress-management-options">
  <h2>Stress Management Options</h2>
  <div class="card-container">
    <div class="card" onclick="window.location.href='stress_management_form.php'">
      <h3>Stress Assessment</h3>
      <p>Complete an assessment to monitor your stress levels.</p>
      <img src="../../../assets/images/stress_monitoring.jpg" alt="Stress Assessment Icon">
    </div>
    <div class="card" onclick="window.location.href='../../controller/StressAssessmentController.php?action=view_records'">
      <h3>Your Assessment History</h3>
      <p>View your history of stress assessments and results.</p>
      <img src="../../../assets/images/past_stress_records.jpg" alt="Past Stress History Icon">
    </div>
    <div class="card" onclick="window.location.href='../../controller/StressAssessmentController.php?action=view_trend'">
      <h3>Stress Trend Analysis</h3>
      <p>Analyze your stress trends over time with interactive visualizations.</p>
      <img src="../../../assets/images/stress_report.jpg" alt="Stress Trend Icon">   
    </div>
  </div>
</div>
<br/>

    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Relax and Refresh while Excelling in your Studies</p>
          <img
            id="footer-logo"
            src="../../../assets/images/logo.jpg"
            alt="RelaxU Logo"
          />
        </div>
        <div class="footer-section">
          <h3>Services</h3>
          <ul>
            <li><a href="../../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
            <li><a href="../../views/relaxation_activities.php">Relaxation Activities</a></li>
            <li><a href="../../views/Academic_Help.php">Academic Help</a></li>
            <li><a href="../../controller/CounselorController.php?action=list">Counseling</a></li>
            <li><a href="../../controller/CommunityController.php?action=list">Community</a></li>
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
              ><img src="../../../assets/images/facebook.png" alt="Facebook"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="../../../assets/images/twitter.png" alt="Twitter"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="../../../assets/images/instagram.png" alt="Instagram"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="../../../assets/images/youtube.png" alt="YouTube"
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
