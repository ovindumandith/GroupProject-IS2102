<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../models/CommunityEventsModel.php';
$eventModel = new Event();
$result = $eventModel->fetchAllEvents();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Notifications</title>
  <link rel="stylesheet" href="../../assets/css/User_events.css" type="text/css"/>
    <script src="../../assets/js/CommunityAdmin_notifications.js" defer></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../assets/css/header_footer.css"
      type="text/css"
    />
      


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
          <li><a href="../views/Academic_Help.php">Academic Help</a></li>
          <li><a href="../controller/CounselorController.php?action=list">Counseling</a></li>
          <li><a href="../controller/CommunityController.php?action=list">Community</a></li>
          <li><a href="../views/About_Us.php">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='../views/profile.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
  
    <main>
    <h2 class="page-title">Upcoming Community Events</h2>
    <div class="events-container">
        <?php
        if ($result && count($result) > 0) {
            foreach ($result as $row) {
                echo '<div class="event-card">';
                echo '  <div class="event-header">';
                echo '    <h3>' . htmlspecialchars($row['title']) . '</h3>';
                echo '  </div>';
                echo '  <div class="event-body">';
                echo '    <p class="event-description">' . htmlspecialchars($row['description']) . '</p>';
                echo '    <div class="event-date">';
                echo '   Date & Time:   <i class="fa fa-calendar"></i> ' . htmlspecialchars($row['date']);
                echo '    </div>';
                echo '    <div class="event-category">' . htmlspecialchars($row['category']) . '</div>';
                echo '    <a href="' . htmlspecialchars($row['link']) . '" class="event-link" target="_blank">Join Event</a>';
                echo '  </div>';
                echo '</div>';
            }
        } else {
            echo '<div class="no-events">No events available at this time.</div>';
        }
        ?>
    </div>
</main>



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
            <li><a href="./relaxation_activities.php">Relaxation Activities</a></li>
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
