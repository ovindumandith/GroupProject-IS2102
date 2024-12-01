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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task Popup</title>
    <link rel="stylesheet" href="../../assets/css/add_event.css">
</head>
<body>
    
 
 <!-- Popup Form -->
  <div id="popupForm" class="popup">
    <div class="popup-content">
      <span id="closeFormButton" class="close-button">&times;</span>
      <h2>Add Event</h2>
      <form id="eventForm">
        <!-- Event Title -->
        <label for="eventTitle">Event Title:</label>
        <input type="text" id="eventTitle" name="eventTitle" required>

        <!-- Event Description -->
        <label for="eventDescription">Description:</label>
        <textarea id="eventDescription" name="eventDescription" rows="4" required></textarea>

        <!-- Event Date -->
        <label for="eventDate">Date:</label>
        <input type="date" id="eventDate" name="eventDate" required>

        <!-- Start Time -->
        <label for="startTime">Start Time:</label>
        <input type="time" id="startTime" name="startTime" required>

        <!-- End Time -->
        <label for="endTime">End Time:</label>
        <input type="time" id="endTime" name="endTime" required>

        <!-- Submit Button -->
        <button type="submit">Add Event</button>
      </form>
    </div>
  </div>

  <script src="../../assets/js/add_event.js"></script>
</body>
</html>