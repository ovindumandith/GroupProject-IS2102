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
    <link rel="stylesheet" href="../../../assets/css/stress_management_form.css" />
    
    <style>
        .question-step { display: none; }
        .question-step.active { display: block; }
    </style>
    
  </head>
  <body>
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
          <li><a href="#">Academic Help</a></li>
          <li><a href="#">Counseling</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
        <form action="../../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <div id="form-container">
    <form id="stress-form" action="../../controller/StressManagementController.php" method="POST">
        <div id="questionnaire-container">
            <h2>Stress Management Questionnaire</h2>
            <div id="question-flow">
                <div class="question-box">
                    <label for="sleep">How much sleep did you get in the last 24 hours (in hours)?</label>
                    <input type="number" id="sleep" name="sleep" min="0" max="24" required>
                </div>

                <div class="question-box" style="display: none;">
                    <label for="exercise">How many hours of physical exercise did you have in the last 24 hours?</label>
                    <input type="number" id="exercise" name="exercise" min="0" max="24" required>
                </div>

                <div class="question-box" style="display: none;">
                    <label for="workload">How many hours did you spend on work/study in the last 24 hours?</label>
                    <input type="number" id="workload" name="workload" min="0" max="24" required>
                </div>

                <div class="question-box" style="display: none;">
                    <label for="mood">How would you rate your mood on a scale of 1 to 10 (1 being very low, 10 being very high)?</label>
                    <input type="number" id="mood" name="mood" min="1" max="10" required>
                </div>
            </div>
            
        <div id="question-image-container">
            <img id="question-image" src="../../../assets/images/sleep.jpg" alt="Question Image">
        </div>
        <div id="progress-container">
            <div id="progress-bar"></div>
        </div>
        <p id="question-count">Question 1 of 4</p>
            <button type="button" id="next-question">Next</button>
            <button type="submit" id="submit-questionnaire" style="display: none;">Submit</button>
        </div>
    </form>
</div>

    
<script src="../../../assets/js/stress_management_form.js" type="text/javascript"></script>

    <!-- Footer Section -->
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
