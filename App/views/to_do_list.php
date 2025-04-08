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
  <title>to-do-list</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/home.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/to_do_list.css" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Font Awesome CDN link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>

  <!-- Content Section (for demonstration) -->
  <div class="dashboard-container">

    <!-- Main Content -->
    <main class="main-content">
      <header class="header-task-planner">
        <div class="task-planner">

          <h2>Task Planner</h2>
        </div>
        <div class="search-and-add">
          <div class="search-bar">
            <input type="text" class="search-task" placeholder="Search your task here !">

            <button class="search-button">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div>
          <button class="add-event-button" onclick="showPopup()">
            <span class="add-icon"><i class="fa-solid fa-plus"></i></span>
            Add Task
          </button>
          <!-- Popup Form (Initially Hidden) -->
          <div class="popup" id="eventPopup">
            <div class="popup-content">
              <span class="close-btn" onclick="closePopup()">&times;</span>
              <h2 id="popupTitle">Add Task</h2>
              <form id="eventForm" method="POST">
                <input type="hidden" id="taskId" name="id"> <!-- Hidden field for event ID -->
                <!-- Error container (for displaying error messages) -->
                <div id="errorContainer" style="color: red; margin-bottom: 10px;"></div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
                <label for="time">Time:</label>
                <input type="time" id="time" name="time" required>

                <button type="submit" id="formSubmitButton">Save Task</button>
              </form>
            </div>
          </div>
        </div>

      </header>

      <!-- Events Section -->

      <section class="my-tasks">
    <div class="my-tasks-container">
        <div class="my-tasks-container-header">
            <div class="task-filter">
                <button class="filter-button active" data-filter="today">Today <span class="count">0</span></button>
                <button class="filter-button" data-filter="upcoming">Upcoming <span class="count">0</span></button>
                <button class="filter-button" data-filter="overdue">Overdue <span class="count">0</span></button>
            </div>
        </div>

        <div class="my-tasks-list"></div> <!-- Task list will be inserted here -->
    </div>

    <button class="back-button" onclick="location.href='workload.php'">
        <i class="fa-solid fa-arrow-left"></i>
    </button>
</section>
      <div id="popupMessage" style="display: none; position: fixed; top: 20px; right: 20px; background: #4caf50; color: white; padding: 15px; border-radius: 5px; z-index: 1000;">
        <span id="popupText"></span>
      </div>

      <script src="../../assets/js/to_do_list.js" defer></script>
</body>

</html>