<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}
if (isset($_GET['counselor_id'])) {
    $counselorId = $_GET['counselor_id'];
} else {
    // Redirect or show an error if no counselor_id is provided
    die("Counselor ID is missing.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment</title>
        <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
        <link
      rel="stylesheet"
      href="../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../assets/css/schedule_appointments.css" type="text/css" />
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
          <li><a href="#">Academic Help</a></li>
          <li><a href="../controller/CounselorController.php?action=list">Counseling</a></li>
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


<div class="schedule-appointment">
  <h2>Schedule an Appointment</h2>
  <p>Fill in the details below to book your appointment with the counselor.</p>
  
  <!-- Add info message about appointment scheduling restrictions -->
  <div class="info-message">
    <p><strong>Note:</strong> Appointments must be scheduled at least 2 days in advance to give our counselors enough time to prepare.</p>
  </div>
  
  <!-- Error message display - only shown when there's an error -->
  <?php if(isset($_GET['error']) && $_GET['error'] == 'date'): ?>
  <div class="error-message">
    <p><strong>Error:</strong> Please select a date at least 2 days from today. Past dates and too close dates cannot be scheduled.</p>
  </div>
  <?php endif; ?>

  <form class="schedule-form" action="AppointmentController.php?action=scheduleAppointment" method="POST" id="appointmentForm">
    <!-- Hidden fields for user ID and counselor ID -->
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
    <input type="hidden" name="counselor_id" value="<?= $_GET['counselor_id'] ?>">

    <!-- Date and Time -->
    <div class="form-group">
      <label for="appointment_date">Choose Date and Time:</label>
      <input type="datetime-local" id="appointment_date" name="appointment_date" required>
      <small id="dateHelp" class="form-text">Earliest available appointment is <span id="earliestDate">calculating...</span></small>
    </div>

    <!-- Topic of Interest -->
    <div class="form-group">
      <label for="topic">Topic of Discussion:</label>
      <input type="text" id="topic" name="topic" placeholder="E.g., Stress Management, Career Guidance" required>
    </div>

    <!-- Email -->
    <div class="form-group">
      <label for="email">Your Email:</label>
      <input type="email" id="email" name="email" placeholder="Enter your email address" required>
    </div>

    <!-- Phone -->
    <div class="form-group">
      <label for="phone">Your Phone:</label>
      <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="submit-btn" id="scheduleBtn">📅 Schedule Appointment</button>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the date input element
    const dateInput = document.getElementById('appointment_date');
    const dateHelp = document.getElementById('earliestDate');
    const form = document.getElementById('appointmentForm');
    
    // Calculate the minimum date (2 days from now)
    const now = new Date();
    const minDate = new Date(now);
    minDate.setDate(now.getDate() + 2); // Add 2 days
    
    // Format the date for the datetime-local input
    // Format: YYYY-MM-DDThh:mm
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}T00:00`;
    }
    
    // Set the minimum date attribute
    dateInput.min = formatDateForInput(minDate);
    
    // Display the earliest available date in a user-friendly format
    dateHelp.textContent = minDate.toDateString();
    
    // Form validation
    form.addEventListener('submit', function(event) {
        const selectedDate = new Date(dateInput.value);
        
        // Check if the selected date is at least 2 days in the future
        if (selectedDate < minDate) {
            event.preventDefault();
            alert('Please select a date that is at least 2 days from today.');
            dateInput.focus();
        }
    });
});
</script>

<!-- Add some CSS for the info message -->
<style>
.info-message {
    background-color: #e7f3ff;
    border-left: 4px solid #2196F3;
    margin-bottom: 20px;
    padding: 10px 15px;
    border-radius: 3px;
}

.info-message p {
    margin: 0;
    color: #0c5460;
}

#dateHelp {
    color: #6c757d;
    font-size: 0.85rem;
    margin-top: 5px;
    display: block;
}
</style>

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
            <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
            <li><a href="../views/relaxation_activities.php">Relaxation Activities</a></li>
            <li><a href="#">Academic Help</a></li>
            <li><a href="../views/counselling/counsellor_index.php">Counseling</a></li>
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