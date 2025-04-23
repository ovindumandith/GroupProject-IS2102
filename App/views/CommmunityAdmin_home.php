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
    <link
      rel="stylesheet"
      href="../../assets/css/admin_home.css"
      type="text/css">
      


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
          <li><a href="CommunityAdminEvents.php">Community Events</a></li>
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
  
    <div class="main-content">
    <div class="welcome-message">
        <h1>Welcome to Community Admin Dashboard</h1>
        <p>Here you can manage and monitor all activities related to posts, comments, users, and engagement.</p>
    </div>

    <div class="overview">
        <div class="card">
            <h3>Total Posts</h3>
            <p id="total-posts">1200</p>
        </div>
        <div class="card">
            <h3>Total Comments</h3>
            <p id="total-comments">3500</p>
        </div>
        <div class="card">
            <h3>Total Users</h3>
            <p id="total-users">780</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-container">
            <h3>Top Contributors</h3>
            <div id="contributors-chart" class="chart"></div>
        </div>
        <div class="chart-container">
            <h3>Posts Over Time</h3>
            <div id="posts-chart" class="chart"></div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-container">
        <h3>Recent Activities</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Activity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Amanda Lee</td>
                    <td>Added a new post</td>
                    <td>Published</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>David Kim</td>
                    <td>Commented on a post</td>
                    <td>Approved</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Emma Brown</td>
                    <td>Deleted a comment</td>
                    <td>Removed</td>
                </tr>
            </tbody>
        </table>
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
