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
<link rel="stylesheet" href="/GroupProject-IS2102/assets/css/header_footer.css">
<link rel="stylesheet" href="/GroupProject-IS2102/assets/css/counselor_card.css">
    
 
    
  </head>
  <body>
        <!-- Toast Container -->

    <!-- Header Section -->
    <header class="header">
      <div class="logo">
        <img src="/GroupProject-IS2102/assets/images/logo.jpg" alt="RelaxU Logo" />
        <h1>RelaxU</h1>
      </div>
      <nav class="navbar">
        <ul>
          <li><a href="../../views/home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="../../views/stress_managment_form.php">Stress Monitoring</a></li>
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
<body>
        <h1>Counselors</h1>
<div class="counselor-list">
    <?php if (!empty($counselors)): ?>
        <?php foreach ($counselors as $counselor): ?>
            <div class="counselor-card">
                <!-- Counselor Image -->
                <div class="card-image">
                    <img src="<?= htmlspecialchars($counselor['profile_image']) ?>" alt="<?= htmlspecialchars($counselor['name']) ?>">
                </div>
                <!-- Counselor Name -->
                <h3><?= htmlspecialchars($counselor['name']) ?></h3>
                <!-- Counselor Type -->
                <p><strong>Type:</strong> <?= htmlspecialchars($counselor['type']) ?></p>
                <!-- Counselor Specialization -->
                <p>
                    <strong>Specialization:</strong>
                    <?= $counselor['specialization'] ? htmlspecialchars($counselor['specialization']) : 'N/A' ?>
                </p>
<form action="CounselorController.php" method="GET">
    <input type="hidden" name="action" value="viewCounselor">
    <input type="hidden" name="id" value="<?= $counselor['id'] ?>"> <!-- Counselor ID -->
    <button type="submit" class="button">View Profile</button>
</form>




            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No counselors available at the moment.</p>
    <?php endif; ?>
</div>


    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Relax and Refresh while Excelling in your Studies</p>
          <img
            id="footer-logo"
            src="/GroupProject-IS2102/assets/images/logo.jpg"
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
              ><img src="/GroupProject-IS2102/assets/images/facebook.png" alt="Facebook"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="/GroupProject-IS2102/assets/images/twitter.png" alt="Twitter"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="/GroupProject-IS2102/assets/images/instagram.png" alt="Instagram"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="/GroupProject-IS2102/assets/images/youtube.png" alt="YouTube"
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
