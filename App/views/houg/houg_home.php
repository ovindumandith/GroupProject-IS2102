<?php
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
    header('Location: ../login.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Head of Undergraduate Studies Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../../assets/css/houg_home.css" type="text/css" />
    <script src="../../../assets/js/search_academic_requests.js" defer></script>
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
          <li><a href="../../views/houg/houg_home.php">Dashboard</a></li>
          <li><a href="../../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous">Academic Requests</a></li>
           <li><a href="../../controller/LecturerController.php?action=list">List of Lecturers</a></li>

        </ul>
      </nav>
      <div class="auth-buttons">
        <!-- Profile button form -->
<form action="hous_profile.php" method="GET">
    <button type="submit" class="login"><b>Profile</b></button>
</form>

    
        <!-- Logout button form -->
        <form action="../../../util/logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
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
          <li><a href="../../views/houg/houg_home.php">Dashboard</a></li>
          <li><a href="#">Academic Requests</a></li>
          <li><a href="#">List of Lecturers</a></li>
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


