<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Responsive Setup -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- External Resources -->
    <title>RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    
    <!-- Local Styles -->
    <link rel="stylesheet" href="../../assets/css/relaxation_activities.css" />
    <link rel="stylesheet" href="../../assets/css/add_relaxation_activities.css" type="text/css" />
  </head>

  <body>
  <?php
    require_once "../controller/RelaxationActivityController.php";
    $controller = new RelaxationActivityController();
    $message    = $controller->handleRequest();
  ?>

    <!-- Header Reuse -->
    <header class="header">
      <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
        <h1>RelaxU</h1>
      </div>

      <!-- Navigation System -->
      <nav class="navbar">
        <ul>
          <li><a href="./admin_home.php">Home</a></li>
          <li class="services">
            <a href="#">Services</a>
            <ul class="dropdown">
              <li><a href="#">Stress Monitoring</a></li>
              <li><a href="relaxation_activities.php">Relaxation Activities</a></li>
              <li><a href="#">Workload Management Tools</a></li>
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
        <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <!-- Error Display System -->
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert error creative-error">  <!-- Session-Based Feedback -->
        <div class="error-header">
            <span class="error-emoji">🚨</span>  <!-- Visual Indicators -->
        </div>
        <div class="error-content">
            <?= str_replace('<br>', "\n", htmlspecialchars($_SESSION['error'])) ?>  <!-- XSS Prevention -->
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Main Form Section -->
    <div class="content">
      <h1>Add Relaxation Activities</h1>
      
      <!-- Data Entry Form -->
      <form method="post" action="./add_relaxation_activites.php" id="updateform" enctype="multipart/form-data">  <!-- File Upload Capability -->
        <!-- Input Grouping -->
        <label for="activity_name">Activity Title:</label>
        <input type="text" id="activity_name" name="activity_name" required>  <!-- HTML5 Validation -->

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>  <!-- Content Input -->

        <label for="playlist">Source:</label>
        <input type="text" id="playlist" name="playlist_url" required>  <!-- URL Input -->

        <!-- File Upload Section -->
        <label for="image_url">Image:</label>
        <div class="image-preview-container">
          <label for="image" class="file-input-label">Choose Image</label>  <!-- Custom Styling -->
          <input type="file" id="image_url" name="image_url" class="file-input" required >  <!-- Required Field -->
          <img id="newImagePreview" class="new-image-preview" src="#" alt="Image Preview">  <!-- Live Preview -->
        </div>

        <!-- Stress Level Selection -->
        <label>Recommended Stress Level:</label>
        <div class="radio-group">  <!-- Accessible Grouping -->
          <label for="low"><input type="radio" value="low" id="low" name="stress_level" required>Low</label>
          <label for="moderate"><input type="radio" value="moderate" id="moderate" name="stress_level">Moderate</label>
          <label for="high"><input type="radio" value="high" id="high" name="stress_level">High</label>
        </div>

        <input type="submit" name="submit" value="Add Activity">  <!-- Form Submission -->
      </form>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">Profile updated successfully!</div>  <!-- Hidden Feedback Element -->

    <!-- Consistent Footer -->
    <footer class="footer">[...]</footer>  <!-- Reused from previous templates -->

    <!-- Script Dependencies -->
    <script src="../../assets/js/update_relaxation_activities.js"></script>  <!-- Image Preview Logic -->
  </body>
</html>