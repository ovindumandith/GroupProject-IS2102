<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Responsive Meta Essentials -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin_activites_portal.css" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
  </head>

  <body>
    <!-- Main Header Structure -->
    <header class="header">
      <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
        <h1>RelaxU</h1>
      </div>

      <!-- Navigation System -->
      <nav class="navbar">
        <ul>
          <li><a href="../controller/AdminDashboardController.php?action=loadDashboard">Home</a></li>
          <li class="services">
            <a href="#">Services</a>
            <ul class="dropdown">
              <li><a href="../controller/AdminStressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
              <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
              <li><a href="../views/workload.php">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="#">Academic Help</a></li>
          <li><a href="#">Counseling</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </nav>

      <!-- User Controls -->
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='../../App/controller/AdminProfileController.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <!-- Dashboard Content -->
    <div id="content">
      <div class="card-container">
        <!-- Activity Viewer Card -->
        <div class="card">
          <h3>View Current Relaxation Activities</h3>
          <div class="stress-links">
            <a href="../views/low_level_relaxation_activities.php">Low</a>
            <a href="../views/moderate_level_relaxation_activities.php">Moderate</a>
            <a href="../views/high_level_relaxation_activities.php">High</a>
          </div>
          <img src="../../assets/images/relaxed-woman-enjoying-sea_1098-1441.avif" alt="Woman practicing mindfulness at beach">
        </div>
        <!-- Activity Creation Card -->
        <div class="card">
          <h3>Add New Relaxation Activities</h3>
          <div class="stress-links">
            <a href="../views/add_relaxation_activites.php">Add</a>
          </div>
          <img src="../../assets/images/young-adult-enjoying-yoga-nature_23-2149573166.jpg" alt="Person meditating in natural setting">
        </div>
      </div>
    </div>

    <!-- Footer Structure -->
    <footer class="footer">
      <div class="footer-container">
        <!-- Brand Column -->
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Relax and Refresh while Excelling in your Studies</p>
          <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU brand logo">
        </div>

        <!-- Service Links -->
        <div class="footer-section">
          <h3>Services</h3>
          <ul>
            <li><a href="#">Stress Monitoring</a></li>
            <li><a href="#">Relaxation Activities</a></li>
            <li><a href="#">Academic Help</a></li>
            <li><a href="#">Counseling</a></li>
            <li><a href="#">Community</a></li>
            <li><a href="#">Workload Management Tools</a></li>
          </ul>
        </div>

        <!-- Contact Information -->
        <div class="footer-section">
          <h3>Contact</h3>
          <p><i class="fa fa-phone"></i> +14 5464 8272</p>
          <p><i class="fa fa-envelope"></i> rona@domain.com</p>
          <p><i class="fa fa-map-marker"></i> Lazy Tower 192, Burn Swiss</p>
        </div>

        <!-- Legal Links -->
        <div class="footer-section">
          <h3>Links</h3>
          <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms Of Use</a></li>
          </ul>
        </div>
      </div>

      <!-- Social Media Integration -->
      <div class="social-media">
        <ul>
          <li><a href="#"><img src="../../assets/images/facebook.png" alt="Facebook profile"></a></li>
          <li><a href="#"><img src="../../assets/images/twitter.png" alt="Twitter account"></a></li>
          <li><a href="#"><img src="../../assets/images/instagram.png" alt="Instagram page"></a></li>
          <li><a href="#"><img src="../../assets/images/youtube.png" alt="YouTube channel"></a></li>
        </ul>
      </div>

      <!-- Copyright Notice -->
      <div class="footer-bottom">
        <p>copyright 2024 @RelaxU all rights reserved</p>
      </div>
    </footer>
  </body>
</html>