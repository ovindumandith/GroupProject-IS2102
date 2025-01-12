
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

<?php if (!empty($appointments)): ?>
    <table>
        <thead>
            <tr>
                <th>Appointment Date</th>
                <th>Counselor</th>
                <th>Topic</th>
                <th>Status</th>
                <th>Actions</th> <!-- New column for actions -->
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
    <!-- Update button -->
    <a href="../controllers/AppointmentController.php?action=updateAppointmentForm&appointment_id=<?php echo $appointment['id']; ?>" 
       class="btn update-btn">Update</a>

    <!-- Delete button -->
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

