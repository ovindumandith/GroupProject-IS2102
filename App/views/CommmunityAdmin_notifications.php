<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}
require_once '../models/CommunitynotificationsModel.php';
$notificationModel = new Notification();
$result = $notificationModel->fetchAllNotifications();
?>
<?php if (isset($_GET['status'])): ?>
  <div class="status-message <?php echo htmlspecialchars($_GET['status']); ?>">
    <?php
      if ($_GET['status'] === 'success') echo "Notification sent and post deleted successfully.";
      elseif ($_GET['status'] === 'fail') echo "Error: Failed to send notification.";
      elseif ($_GET['status'] === 'invalid') echo "Please fill in all fields.";
    ?>
  </div>
<?php endif; ?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Notifications</title>
  <link rel="stylesheet" href="../../assets/css/CommunityAdmin_notifications.css" type="text/css"/>
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
        <li><a href="CommmunityAdmin_home.php">Home</a></li>
          <li><a href="../controller/CommunityAdminController.php?action=list">Community</a></li>
          <li><a href="CommmunityAdmin_notifications.php">Notifications</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='CommunityAdmin_profile.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
  
<main>
  <br>
    <h2>Admin Sent Notifications</h2>
    <section class="notification-form-section">
  <h3>Send Notification to User</h3>
  <form action="../controller/CommunitynotificationsController.php?action=sendNotification" method="POST" class="notification-form">
    <label for="user_id">User ID:</label>
    <input type="text" id="user_id" name="user_id" required>

    <label for="post_id">Post ID:</label>
    <input type="text" id="post_id" name="post_id" required>

    <label for="title">Post Title:</label>
    <input type="text" id="title" name="title" required>

    <label for="reason">Reason:</label>
    <textarea id="reason" name="reason" rows="4" required placeholder="Write the reason for deleting the post..."></textarea>

    <input type="submit" value="Add Notification">
  </form>
</section>



<div class="search-box">
  <input type="text" id="searchInput" placeholder="Search by Reason, Post ID, User ID...">
</div>
<table id="notificationsTable">
  <thead>
    <tr>
      <th>Notification ID</th><th>User ID</th><th>Post ID</th><th>Reason</th><th>Deleted At</th>
    </tr>
  </thead>
  <tbody>
  <?php
  if ($result) {
    foreach ($result as $row) {
      echo "<tr>
              <td>{$row['notification_id']}</td>
              <td>{$row['user_id']}</td>
              <td>{$row['post_id']}</td>
              <td>{$row['reason']}</td>
              <td>{$row['created_at']}</td>
            </tr>";
    }
  } else {
    echo "<tr><td colspan='5' class='no-data'>No notifications found.</td></tr>";
  }
?>
  </tbody>
</table>

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
