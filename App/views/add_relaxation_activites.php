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
    <link rel="stylesheet" href="../../assets/css/relaxation_activities.css" />
    <link rel="stylesheet" href="../../assets/css/add_relaxation_activities.css" type="text/css" />
  </head>
  <body>
  <?php

    require_once "../controller/RelaxationActivityController.php";

    $controller = new RelaxationActivityController();
    $message = $controller->handleRequest();

?>
    <!-- Header Section -->
    <header class="header">
      <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
        <h1>RelaxU</h1>
      </div>
      <nav class="navbar">
        <ul>
          <li><a href="./admin_home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="#">Stress Monitoring</a></li>
              <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
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
    <!-- Content Section (for demonstration) -->

    <?php if (isset($_SESSION['error'])): ?>
<div class="alert error creative-error">
    <div class="error-header">
        <span class="error-emoji">🚨</span>
    </div>
    <div class="error-content">
        <?= str_replace('<br>', "\n", htmlspecialchars($_SESSION['error'])) ?>
    </div>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

    <div class="content">
        <h1>Add Relaxation Activities</h1>
         
    
    <form method="post"  action="./add_relaxation_activites.php" id="updateform" enctype="multipart/form-data">
        <label for="activity_name">Activity Title:</label>
        <input type="text" id="activity_name" name="activity_name" required>

        <label for="description">description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="playlist">Source:</label>
        <input type="text" id="playlist" name="playlist_url" required></input>

        <label for="image_url">Image:</label>
            <div class="image-preview-container">
                <label for="image" class="file-input-label">Choose Image</label>
                <input type="file" id="image_url" name="image_url" class="file-input" required >
                <img id="newImagePreview" class="new-image-preview" src="#" alt="Image Preview">
            </div>

        <label>Recommended Stress Level:</label>
        <div class="radio-group">
          <label for="low"><input type="radio" value="low" id="low" name="stress_level" required>Low</label>
          <label for="moderate"><input type="radio" value="moderate" id="moderate" name="stress_level">Moderate</label>
          <label for="high"><input type="radio" value="high" id="high" name="stress_level">High</label>
        </div>

        <input type="submit" name="submit" value="Add Activity">
    </form>
      <p></p>
    </div>
    <div id="toast" class="toast">Profile updated successfully!</div>


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
          <p><i class="fa fa-image"></i> +14 5464 8272</p>
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

  <script src="../../assets/js/update_relaxation_activities.js"></script>
    // Simple image preview functionality
   
</html>