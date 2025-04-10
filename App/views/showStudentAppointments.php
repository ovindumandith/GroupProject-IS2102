
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
      href="../../assets/css/showstudentappointments.css"
      type="text/css" />

    <script src="../../assets/js/hero_slider.js" defer></script>
    <script src="../../assets/js/testimonial_slider.js" defer></script>
    <script src="../../assets/js/counter.js" defer></script>
    <script src="../../assets/js/appointment_search_student.js" defer></script>

    <style>
      /* Search Bar Container */
.search-container {
  display: flex;
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */
  margin: 20px 0;
}

/* Search Input Styling */
.search-input {
  width: 300px; /* Default width */
  height: 40px; /* Default height */
  padding: 10px 20px; /* Add padding */
  font-size: 1.1rem; /* Larger font size */
  border: 2px solid #006d58; /* Match the green theme */
  border-radius: 25px; /* Rounded edges */
  outline: none;
  transition: all 0.3s ease; /* Smooth transition for animations */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Interaction: Focus Effects */
.search-input:focus {
  width: 400px; /* Expand width */
  height: 50px; /* Expand height */
  border-color: #009f77; /* Highlight border */
  box-shadow: 0 6px 10px rgba(0, 159, 119, 0.4); /* Stronger shadow */
  background-color: #f9f9f9; /* Slight background color change */
}

/* Search Button Styling (Optional) */
.search-btn {
  display: none; /* Hide the button as search is dynamic */
}

    </style>
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
              <li><a href="../views/workload.php">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="../views/Academic_Help.php">Academic Help</a></li>
          <li><a href="../controller/CounselorController.php?action=list">Counseling</a></li>
          <li><a href="../controller/CommunityController.php?action=list">Community</a></li>
          <li><a href="../views/About_Us.php">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
    <h1 class="student-appointments">Your Pending Appointments</h1>

<div class="search-container">
    <input 
        type="text" 
        id="search-bar" 
        placeholder="🔍 Search... " 
        class="search-input"
    >
</div>
<div id="appointment-results">
    <?php if (!empty($appointments)): ?>
        <table>
            <thead>
                <tr>
                    <th>Appointment Date</th>
                    <th>Counselor</th>
                    <th>Topic</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['counselor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['topic']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                        <td>
                            <div class="button-group">
                                <a href="../controller/AppointmentController.php?action=updateAppointmentForm&appointment_id=<?php echo $appointment['id']; ?>" 
                                   class="btn update-btn">Update</a>
                                <form method="POST" action="../controller/AppointmentController.php?action=deleteAppointment" style="display: inline;">
                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                    <?php if ($appointment['status'] === 'Accepted'): ?>
                                        <button type="submit" class="btn delete-btn" 
                                                onclick="return confirm('This appointment is already scheduled. Are you sure you want to cancel it?');">
                                            Cancel
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn delete-btn" 
                                                onclick="return confirm('Are you sure you want to delete this appointment?');">
                                            Delete
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="student-appointments">You have no pending appointments.</p>
    <?php endif; ?>
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
            <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
            <li><a href="../views/relaxation_activities.php">Relaxation Activities</a></li>
            <li><a href="../views/academic_help.php">Academic Help</a></li>
            <li><a href="../views/counselling/counsellor_index.php">Counseling</a></li>
        
            <li><a href="#">Workload Managment Tools</a></li>
            <li><a href="../views/Community/create_post.php">Community</a></li>
        
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

