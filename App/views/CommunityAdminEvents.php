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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Notifications</title>
  <link rel="stylesheet" href="../../assets/css/CommunityAdmin_notifications.css" type="text/css"/>
  <script src="../../assets/js/CommunityAdmin_events.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css"/>
</head>

<body>
  <!-- Header Section -->
  <header class="header">
    <div class="logo">
      <img src="../../assets/images/logo.jpg" alt="RelaxU Logo"/>
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

  <main>
    <br>
    <h2>Community Events Management</h2>
    <section class="notification-form-section">
      <h3>Add Community Event Details</h3>
      <form action="../controller/CommunityEventsController.php?action=addEvent" method="POST" class="notification-form">

        <label for="title">Event Title:</label>
        <input type="text" id="title" name="title" required>


        <label for="date">Event Date & Time:</label>
        <input type="datetime-local" id="date" name="date" required min="<?= date('Y-m-d\TH:i') ?>">

        <label for="link">Meeting Link:</label>
        <input type="url" id="link" name="link" placeholder="https://example.com" required>

        <label for="description">Event Description:</label>
        <textarea id="description" name="description" rows="4" required placeholder="Describe the event..."></textarea>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required placeholder="e.g: Yoga, Webinar, Meditation, etc.">

        <input type="submit" value="Add Event" class="add-post-btn">
      </form>
    </section>

    <!-- Search Bar -->
    <div class="search-box">
      <input type="text" id="searchInput" placeholder="Search by Title, Category, or Date...">
    </div>

    <!-- Events Table -->
    <table id="eventsTable">
      <thead>
        <tr>
          <th>Event ID</th>
          <th>Title</th>
          <th>Date & Time</th>
          <th>Link</th>
          <th>Description</th>
          <th>Category</th>
          <th>Created At</th>
          <th>Delete</th> 
          <th>Update</th> 
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result && count($result) > 0) {
          foreach ($result as $row) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['event_id']) . "</td>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['date']) . "</td>
                    <td><a href=\"" . htmlspecialchars($row['link']) . "\" target=\"_blank\">Join</a></td>
                    <td>" . htmlspecialchars($row['description']) . "</td>
                    <td>" . htmlspecialchars($row['category']) . "</td>
                    <td>" . htmlspecialchars($row['created_at']) . "</td>
                    <td>
                    <form method='POST' action='../controller/CommunityEventsController.php' onsubmit=\"return confirm('Are you sure you want to delete this event?');\">
                    <input type='hidden' name='action' value='delete'>
                      <input type='hidden' name='id' value='" . htmlspecialchars($row['event_id']) . "'>                      
                        <button type='submit' class='delete-btn'>Delete</button>
                      </form>
                  </td>
                  <td><a class='edit-btn' href='editEventForm.php?id=" . htmlspecialchars($row['event_id']) . "'>Update</a></td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='7' class='no-data'>No events found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-logo">
        <h1>RelaxU</h1>
        <p>Relax and Refresh while Excelling in your Studies</p>
        <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU Logo"/>
      </div>
      <div class="footer-section">
        <h3>Services</h3>
        <ul>
          <li><a href="#">Stress Monitoring</a></li>
          <li><a href="./relaxation_activities.php">Relaxation Activities</a></li>
          <li><a href="#">Academic Help</a></li>
          <li><a href="#">Counseling</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">Workload Management Tools</a></li>
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
        <li><a href="#"><img src="../../assets/images/facebook.png" alt="Facebook"/></a></li>
        <li><a href="#"><img src="../../assets/images/twitter.png" alt="Twitter"/></a></li>
        <li><a href="#"><img src="../../assets/images/instagram.png" alt="Instagram"/></a></li>
        <li><a href="#"><img src="../../assets/images/youtube.png" alt="YouTube"/></a></li>
      </ul>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2024 RelaxU. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
