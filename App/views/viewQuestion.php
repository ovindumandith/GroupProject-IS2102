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
    <link rel="stylesheet" href="../../assets/css/viewQuestion.css" type="text/css" />
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

    <div class="container">
        <h1>Academic Question Details</h1>

        <?php if (isset($data) && !empty($data)): ?>
            <!-- Display Question Details -->
            <div class="question-details">
                <h2>Question Information</h2>
                <p><strong>Question ID:</strong> <?php echo htmlspecialchars($data[0]['question_id']); ?></p>
                <p><strong>Question:</strong> <?php echo htmlspecialchars($data[0]['question']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($data[0]['question_status']); ?></p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($data[0]['question_created_at']); ?></p>
                <p><strong>Last Updated:</strong> <?php echo htmlspecialchars($data[0]['question_updated_at']); ?></p>
            </div>

            <!-- Display Responses -->
            <div class="responses-section">
                <h2>Responses</h2>
                <?php 
                $responses = array_filter($data, function($entry) {
                    return !empty($entry['response_id']);
                });

                if (!empty($responses)): ?>
                    <ul class="responses-list">
                        <?php foreach ($responses as $response): ?>
                            <li>
                                <p><strong>Response ID:</strong> <?php echo htmlspecialchars($response['response_id']); ?></p>
                                <p><strong>Response:</strong> <?php echo htmlspecialchars($response['response']); ?></p>
                                <p><strong>Admin ID:</strong> <?php echo htmlspecialchars($response['admin_id']); ?></p>
                                <p><strong>Created At:</strong> <?php echo htmlspecialchars($response['response_created_at']); ?></p>
                                <p><strong>Last Updated:</strong> <?php echo htmlspecialchars($response['response_updated_at']); ?></p>
                            </li>
                            <hr>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No responses available for this question.</p>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p class="error-message">No question details found or invalid request.</p>
        <?php endif; ?>

        <!-- Back Button -->
<div class="back-btn">
    <a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions" class="btn">Back to All Questions</a>

    <!-- Form for Mark as Resolved -->
    <form action="../controller/Academic_QuestionsController.php?action=markAsResolved" method="POST" style="display:inline;">
        <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($data[0]['question_id']); ?>">
        <button type="submit" class="btn mark-resolved-btn">Mark as Resolved</button>
    </form>
</div>


    </div>s
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

