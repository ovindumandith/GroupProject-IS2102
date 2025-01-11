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
    <title><?= htmlspecialchars($counselor['name']) ?>'s Profile</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../assets/css/counsellor_profile.css" type="text/css" />


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



<main class="profile-container">
    <!-- Profile Content -->
    <div class="profile-content">
        <div class="profile-header">
            <img class="profile-image" src="<?= htmlspecialchars($counselor['profile_image']) ?>" alt="<?= htmlspecialchars($counselor['name']) ?>'s Image">
            <h1 class="profile-name"><?= htmlspecialchars($counselor['name']) ?></h1>
            <p class="counselor-type"><strong>Counselor Type:</strong> <?= htmlspecialchars($counselor['type']) ?></p>
        </div>
        <div class="profile-details">
            <h2>About the Counselor</h2>
            <p><strong>Specialization:</strong> <?= htmlspecialchars($counselor['specialization']) ?: 'N/A' ?></p>
            <p><?= htmlspecialchars($counselor['description']) ?></p>
            <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($counselor['email']) ?>"><?= htmlspecialchars($counselor['email']) ?></a></p>
        </div>
        <div class="profile-actions">
          <a href="schedule_appointments.php?counselor_id=<?= $counselor['id'] ?>" class="action-button">📅 Schedule Appointment</a>


            
        </div>
    </div>

<!-- Reviews Section -->
<div class="reviews-section">
    <h2 class="reviews-heading">Reviews</h2>
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <h3 class="reviewer-name"><?= htmlspecialchars($review['reviewer_name']) ?></h3>
                <div class="rating"><?= str_repeat('⭐', $review['rating']) ?></div>

                <!-- Review Text (Initially Displayed) -->
                <p class="review-text" id="review-text-<?= $review['id'] ?>"><?= htmlspecialchars($review['review_text']) ?></p>
                <p class="review-date"><?= date("F j, Y", strtotime($review['created_at'])) ?></p>

                <!-- Edit Form (Initially Hidden) -->
                <div class="edit-review-form" id="edit-form-<?= $review['id'] ?>" style="display:none;">
                    <form action="ReviewController.php?action=updateReview" method="POST">
                        <input type="hidden" name="review_id" value="<?= htmlspecialchars($review['id']) ?>">
                        <input type="hidden" name="counselor_id" value="<?= $counselor['id'] ?>">
                        <label for="rating-<?= $review['id'] ?>">Rating:</label>
                        <select name="rating" id="rating-<?= $review['id'] ?>" required>
                            <option value="5" <?= $review['rating'] == 5 ? 'selected' : '' ?>>⭐️⭐️⭐️⭐️⭐️</option>
                            <option value="4" <?= $review['rating'] == 4 ? 'selected' : '' ?>>⭐️⭐️⭐️⭐️</option>
                            <option value="3" <?= $review['rating'] == 3 ? 'selected' : '' ?>>⭐️⭐️⭐️</option>
                            <option value="2" <?= $review['rating'] == 2 ? 'selected' : '' ?>>⭐️⭐️</option>
                            <option value="1" <?= $review['rating'] == 1 ? 'selected' : '' ?>>⭐️</option>
                        </select>
                        
                        <textarea name="review_text" id="review-text-<?= $review['id'] ?>" rows="4" required><?= htmlspecialchars($review['review_text']) ?></textarea>
                        
                        <button type="submit" class="submit-review-button">Update Review</button>
                    </form>
                </div>

                <!-- Edit/Delete Actions -->
                <?php if (isset($review['user_id'], $_SESSION['user_id']) && $review['user_id'] === $_SESSION['user_id']): ?>
                    <div class="review-actions">
                        <!-- Edit Button to Toggle Form Visibility -->
                        <button type="button" class="edit-button" onclick="toggleEditForm(<?= $review['id'] ?>)">Edit</button>

                        <!-- Delete Button -->
                        <form action="ReviewController.php?action=deleteReview" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                            <input type="hidden" name="review_id" value="<?= htmlspecialchars($review['id']) ?>">
                            <input type="hidden" name="counselor_id" value="<?= $counselor['id'] ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-reviews">No reviews yet. Be the first to share your thoughts!</p>
    <?php endif; ?>

    <!-- Add Review Section -->
    <div class="add-review">
        <h3>Add Your Review</h3>
        <form action="ReviewController.php?action=addReview" method="POST" class="add-review-form">
            <input type="hidden" name="counselor_id" value="<?= $counselor['id'] ?>">
            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                <option value="4">⭐️⭐️⭐️⭐️</option>
                <option value="3">⭐️⭐️⭐️</option>
                <option value="2">⭐️⭐️</option>
                <option value="1">⭐️</option>
            </select>
            <label for="review_text">Your Review:</label>
            <textarea name="review_text" id="review_text" rows="4" placeholder="Write your review here..." required></textarea>
            <button type="submit" class="submit-review-button">Submit Review</button>
        </form>
    </div>
</div>


<script>
    // Function to toggle the review edit form visibility
    function toggleEditForm(reviewId) {
        var reviewText = document.getElementById('review-text-' + reviewId);
        var editForm = document.getElementById('edit-form-' + reviewId);

        if (editForm.style.display === 'none') {
            // Show the edit form and hide the review text
            editForm.style.display = 'block';
            reviewText.style.display = 'none';
        } else {
            // Hide the edit form and show the review text
            editForm.style.display = 'none';
            reviewText.style.display = 'block';
        }
    }
</script>
</main>
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
