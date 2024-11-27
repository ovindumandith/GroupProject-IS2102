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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/home.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/to_do_list.css" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Font Awesome CDN link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


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
            <li><a href="#">Stress Monitoring</a></li>
            <li><a href="#">Relaxation Activities</a></li>
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
  <div class="main-content">
    <header class="to_do_list_header">
      <div class="task-planner">
        <img src="todo-icon.png" alt="To-Do Icon" class="todo-icon">
        <h3>Task Planner</h3>
      </div>
      <div class="search-and-add">
        <div class="search-bar">
          <input type="text" class="search-task" placeholder="Search your task here !">
          <button class="search-button">
            <i class="fas fa-search" aria-hidden="true"></i>
          </button>

        </div>
        <button class="add-event-button" onclick="window.location.href='add-task.html';">
          <span class="add-icon"><img src="add-icon.png" alt="Add Icon" class="add-icon"></span>
          Add task
        </button>
      </div>

    </header>

    <!-- Events Section -->

    <section class="my-tasks">
      <div class="my-tasks-container">
        <div class="my-tasks-container-header">
          <div class="task-filter">
            <button class="filter-button active" data-filter="today">Today <span class="count">35</span></button>
            <button class="filter-button" data-filter="upcoming">Upcoming <span class="count">14</span></button>
            <button class="filter-button" data-filter="overdue">Overdue<span class="count">19</span></button>
          </div>
        </div>

        <div class="my-tasks-list">
          <!-- Today Event Cards -->
          <div class="my-tasks-list-card" data-category="today">
            <div class="checkbox-container">
              <input type="checkbox" id="task-done-1" class="custom-checkbox" />
              <label for="task-done-1" class="custom-label"></label>
            </div>

            <div class="my-task-list-card-content">
              <h4>Learn Javascript</h4>
              <div class="time-icon">
                <i class="fas fa-bell bell-icon" aria-hidden="true"></i>
                <span>7:30 PM</span>
              </div>
            </div>

            <div class="button-container">
              <button class="edit-btn">
                <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
              </button>

              <button class="delete-btn">
                <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
              </button>

            </div>
          </div>

          <div class="my-tasks-list-card" data-category="today">
            <div class="checkbox-container">
              <input type="checkbox" id="task-done-1" class="custom-checkbox" />
              <label for="task-done-1" class="custom-label"></label>
            </div>

            <div class="my-task-list-card-content">
              <h4>Learn Javascript</h4>
              <div class="time-icon">
                <i class="fas fa-bell bell-icon" aria-hidden="true"></i>
                <span>7:30 PM</span>
              </div>

            </div>

            <div class="button-container">
              <button class="edit-btn">
                <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
              </button>
              <button class="delete-btn">
                <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <!-- Upcoming Event Card -->
          <div class="my-tasks-list-card" data-category="upcoming">
            <div class="checkbox-container">
              <input type="checkbox" id="task-done-2" class="custom-checkbox" />
              <label for="task-done-2" class="custom-label"></label>
            </div>

            <div class="my-task-list-card-content">
              <h4>UI/UX Workshop</h4>
              <div class="calendar-icon">
                <i class="fas fa-calendar-alt"></i>
                <span>Nov 23, 2024</span>
              </div>
            </div>

            <div class="button-container">
              <button class="edit-btn">
                <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
              </button>
              <button class="delete-btn">
                <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <!-- Overdue Event Card -->
          <div class="my-tasks-list-card" data-category="overdue">
            <div class="checkbox-container">
              <input type="checkbox" id="task-done-3" class="custom-checkbox" />
              <label for="task-done-3" class="custom-label"></label>
            </div>

            <div class="my-task-list-card-content">
              <h4>Finalize Project Report</h4>
              <div class="exclamation-mark-icon">
                <i class="fas fa-exclamation-circle"></i>
                <span>Nov 23, 2024</span>
              </div>

            </div>

            <div class="button-container">
              <button class="edit-btn">
                <i class="fas fa-edit edit-icon" aria-hidden="true"></i>
              </button>
              <button class="delete-btn">
                <i class="fas fa-trash delete-icon" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div><!-- End of .my-tasks-list -->
        <button class="see-all-button">see all</button>

      </div><!-- End of .my-tasks-container -->
      <button class="back-button">
        <i class="fas fa-arrow-left"></i> Back
      </button>


    </section>


  </div>

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
  <script src="../../assets/js/to_do_list.js" defer></script>
</body>

</html>