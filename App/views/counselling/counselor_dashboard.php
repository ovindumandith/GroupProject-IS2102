<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['counselor'])) {
    header('Location: counselor_login.php');
    exit();
}

// Get counselor details from the session
$counselor = $_SESSION['counselor'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Counselor Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../../assets/css/counselor_dashboard.css" type="text/css" />
    
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
          <li><a href="../views/counselor_dashboard.php">Dashboard</a></li>
          <li><a href="../views/manage_appointments.php">Appointments</a></li>
          <li><a href="../views/messages.php">Messages</a></li>
          <li><a href="../views/reviews.php">Reviews</a></li>
          <li><a href="../views/profile_edit.php">Profile</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <form action="../../../util/counselor_logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <!-- Content Section -->
    <section class="hero">
      <div class="hero-content">
        <div class="hero-text">
          <h1>
            Welcome, Counselor!
            <span>Your Dashboard Awaits</span>
          </h1>
          <p>
            Manage appointments, assist students, and monitor your progress in
            supporting mental wellness.
          </p>
          <button class="get-started-btn">Get Started</button>
        </div>

      </div>
    </section>

    <!-- Features Section -->
    <section class="dashboard-features">
      <h2>What You Can Do</h2>
      <div class="features">
        <div class="feature-item">
          <img src="../../../assets/images/manage_appointments.png" alt="Manage Appointments" />
          <h3>Manage Appointments</h3>
          <p>View, approve, or reschedule appointments with students.</p>
        </div>
        <div class="feature-item">
          <img src="../../../assets/images/messages.png" alt="Messages" />
          <h3>Messages</h3>
          <p>Communicate directly with students and provide guidance.</p>
        </div>
        <div class="feature-item">
          <img src="../../../assets/images/reviews.png" alt="Reviews" />
          <h3>Reviews & Feedback</h3>
          <p>View feedback from students to improve your sessions.</p>
        </div>
        <div class="feature-item">
          <img src="../../../assets/images/tools.png" alt="Tools" />
          <h3>Productivity Tools</h3>
          <p>Access tools to stay organized and manage your sessions effectively.</p>
        </div>
      </div>
    </section>

    <!-- Summary Section -->
    <section class="dashboard-summary">
      <h2>Your Dashboard Summary</h2>
      <div class="summary-cards">
        <div class="card">
          <h3>Total Appointments</h3>
          <p>15</p>
        </div>
        <div class="card">
          <h3>Pending Approvals</h3>
          <p>3</p>
        </div>
        <div class="card">
          <h3>New Messages</h3>
          <p>7</p>
        </div>
        <div class="card">
          <h3>Average Rating</h3>
          <p>4.8/5</p>
        </div>
      </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Your mental health, your priority.</p>
          <img
            id="footer-logo"
            src="../../../assets/images/logo.jpg"
            alt="RelaxU Logo"
          />
        </div>
        <div class="footer-section">
          <h3>Quick Links</h3>
          <ul>
            <li><a href="../views/counselor_dashboard.php">Dashboard</a></li>
            <li><a href="../views/manage_appointments.php">Appointments</a></li>
            <li><a href="../views/messages.php">Messages</a></li>
            <li><a href="../views/reviews.php">Reviews</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h3>Contact Support</h3>
          <p>Email: support@relaxu.com</p>
          <p>Phone: +1 800-RELAXU</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2024 RelaxU. All Rights Reserved.</p>
      </div>
    </footer>
  </body>
</html>
