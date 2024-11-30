<?php
session_start();
require_once '../../models/StressManagementModel.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$userId = $_SESSION['user_id'];

// Create an instance of the model
$model = new StressManagementModel();

// Fetch stress management records for the logged-in user
$records = $model->getStressRecords($userId);

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
      type="text/css"
    />
    <link
      rel="stylesheet"
      href="../../../assets/css/view_stress_records.css"
      type="text/css"/>

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
<body>
    <div class="container">
        <h1>Your Stress Records</h1>
        <?php if ($records && count($records) > 0): ?>
            <div class="card-container">
<?php foreach ($records as $record): ?>
    <div class="card">
        <h3>Record ID: <?= htmlspecialchars($record['response_id'] ?? 'N/A'); ?></h3>
        <p><strong>Sleep Hours:</strong> <?= htmlspecialchars($record['sleep_hours']); ?></p>
        <p><strong>Exercise Hours:</strong> <?= htmlspecialchars($record['exercise_hours']); ?></p>
        <p><strong>Work Hours:</strong> <?= htmlspecialchars($record['work_hours']); ?></p>
        <p><strong>Mood Status:</strong> <?= htmlspecialchars($record['mood_status']); ?></p>
        <p><strong>Recorded On:</strong> <?= htmlspecialchars($record['response_date']); ?></p>
    </div>
<?php endforeach; ?>

            </div>
        <?php else: ?>
            <p>No records found.</p>
        <?php endif; ?>
    </div>

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

</body>
</html>
