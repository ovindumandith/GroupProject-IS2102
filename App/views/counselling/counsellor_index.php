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
  <title>RelaxU - Counselors</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/GroupProject-IS2102/assets/css/header_footer.css">
  <link rel="stylesheet" href="/GroupProject-IS2102/assets/css/counselor_card.css" type="text/css">
  <script src="/GroupProject-IS2102/assets/js/counselor_index.js"></script>

  <style>
    /* Main Styles for Counselor Listing Page */

:root {
  --primary-color: #009f77;
  --primary-dark: #007a5c;
  --primary-light: #e8f1ec;
  --secondary-color: #fa8128;
  --secondary-dark: #e87217;
  --text-dark: #333;
  --text-light: #777;
  --white: #fff;
  --light-bg: #f9f9f9;
  --border-radius: 15px;
  --box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  --transition: all 0.3s ease;
}

.close-modal {
  position: absolute;
  top: 15px;
  right: 20px;
  font-size: 28px;
  color: var(--text-light);
  cursor: pointer;
  transition: var(--transition);
}

.close-modal:hover {
  color: var(--text-dark);
}

.modal h2 {
  font-size: 1.8rem;
  margin-bottom: 20px;
  color: var(--text-dark);
  text-align: center;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: var(--text-dark);
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  font-size: 1rem;
  color: var(--text-dark);
  font-family: "Poppins", sans-serif;
  transition: var(--transition);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(0, 159, 119, 0.1);
}

.schedule-submit-btn {
  width: 100%;
  background: linear-gradient(
    135deg,
    var(--primary-color) 0%,
    var(--primary-dark) 100%
  );
  color: var(--white);
  border: none;
  padding: 14px;
  border-radius: 30px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  margin-top: 10px;
}

.schedule-submit-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0, 159, 119, 0.2);
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Active Menu Item */
.navbar ul li a.active {
  color: #ffeb3b;
}

.navbar ul li a.active::after {
  width: 100%;
}

/* Responsive Styles */
@media (max-width: 992px) {
  .help-content {
    flex-direction: column;
    text-align: center;
  }

  .help-text h2,
  .help-text p {
    text-align: center;
  }

  .emergency-btn {
    margin: 0 auto;
  }
}

@media (max-width: 768px) {
  .page-header h1 {
    font-size: 2.2rem;
  }

  .page-header p {
    font-size: 1rem;
  }

  .search-bar {
    flex-direction: column;
  }

  .filter-options {
    width: 100%;
    justify-content: space-between;
  }

  .filter-dropdown select {
    width: 100%;
  }

  .counselor-list {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  }
}

@media (max-width: 576px) {
  .counselor-list {
    grid-template-columns: 1fr;
  }

  .filter-options {
    flex-direction: column;
    gap: 10px;
  }

  .modal-content {
    padding: 20px;
  }

  .card-actions {
    flex-direction: column;
  }
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Poppins", sans-serif;
  background-color: var(--light-bg);
  color: var(--text-dark);
  line-height: 1.6;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Page Header Styles */
.page-header {
  background: linear-gradient(
    135deg,
    var(--primary-color) 0%,
    var(--primary-dark) 100%
  );
  color: var(--white);
  padding: 60px 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.page-header::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("/GroupProject-IS2102/assets/images/pattern.svg") repeat;
  opacity: 0.1;
}

.page-header h1 {
  font-size: 2.8rem;
  font-weight: 700;
  margin-bottom: 15px;
  position: relative;
}

.page-header p {
  font-size: 1.2rem;
  max-width: 700px;
  margin: 0 auto;
  opacity: 0.9;
  font-weight: 300;
}

/* Filter Section Styles */
.filter-section {
  padding: 30px 0;
  position: relative;
  margin-top: -25px;
  z-index: 10;
}

.search-bar {
  background-color: var(--white);
  border-radius: var(--border-radius);
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: var(--box-shadow);
  flex-wrap: wrap;
  gap: 15px;
}

.search-box {
  position: relative;
  flex: 1;
  min-width: 250px;
}

.search-box input {
  width: 100%;
  padding: 12px 20px 12px 45px;
  border: 1px solid #e0e0e0;
  border-radius: 30px;
  font-size: 1rem;
  color: var(--text-dark);
  transition: var(--transition);
}

.search-box input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(0, 159, 119, 0.1);
}

.search-box i {
  position: absolute;
  left: 18px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-light);
  font-size: 18px;
}

.filter-options {
  display: flex;
  gap: 15px;
  align-items: center;
}

.filter-dropdown select {
  padding: 12px 40px 12px 20px;
  border: 1px solid #e0e0e0;
  border-radius: 30px;
  font-size: 1rem;
  color: var(--text-dark);
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23777' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: calc(100% - 15px) center;
  background-color: var(--white);
  cursor: pointer;
  transition: var(--transition);
}

.filter-dropdown select:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(0, 159, 119, 0.1);
}

.filter-btn {
  background-color: var(--primary-color);
  color: var(--white);
  border: none;
  padding: 12px 20px;
  border-radius: 30px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition);
}

.filter-btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
}

.filter-btn i {
  font-size: 14px;
}

/* Advanced Filters */
.advanced-filters {
  background-color: var(--white);
  border-radius: var(--border-radius);
  padding: 20px;
  margin-top: 15px;
  box-shadow: var(--box-shadow);
  display: none;
  animation: fadeIn 0.3s ease;
}

.advanced-filters.show {
  display: block;
}

.filter-group {
  margin-bottom: 15px;
}

.filter-group:last-child {
  margin-bottom: 0;
}

.filter-group h4 {
  margin-bottom: 10px;
  color: var(--text-dark);
  font-size: 1rem;
  font-weight: 600;
}

.checkbox-group,
.rating-filter {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.checkbox-group label,
.rating-filter label {
  display: flex;
  align-items: center;
  gap: 5px;
  cursor: pointer;
  font-size: 0.95rem;
  color: var(--text-light);
  transition: var(--transition);
}

.checkbox-group label:hover,
.rating-filter label:hover {
  color: var(--primary-color);
}

.checkbox-group input[type="checkbox"],
.rating-filter input[type="radio"] {
  width: 18px;
  height: 18px;
  accent-color: var(--primary-color);
  cursor: pointer;
}

/* Counselor List Styles */
.counselor-section {
  padding: 30px 0 60px;
}

.counselor-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
}

.counselor-card {
  background-color: var(--white);
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: var(--box-shadow);
  transition: var(--transition);
  position: relative;
  height: 100%;
  display: flex;
  flex-direction: column;
  border: none;
}

.counselor-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.card-banner {
  height: 80px;
  background: linear-gradient(
    135deg,
    var(--primary-color) 0%,
    var(--primary-dark) 100%
  );
}

.card-image {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background-color: var(--white);
  border: 5px solid var(--white);
  position: absolute;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

.card-image .initials {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: var(--secondary-color);
  color: var(--white);
  font-size: 3rem;
  font-weight: 700;
}

.card-content {
  padding: 70px 25px 25px;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.counselor-name {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--text-dark);
  text-align: center;
  margin-bottom: 5px;
}

.counselor-type {
  display: inline-block;
  background: rgba(0, 159, 119, 0.1);
  color: var(--primary-color);
  padding: 5px 15px;
  border-radius: 20px;
  font-size: 0.85rem;
  margin: 0 auto 15px;
  font-weight: 500;
}

.rating-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 15px;
  gap: 5px;
}

.stars {
  color: #ffc107;
  font-size: 1.1rem;
}

.review-count {
  font-size: 0.85rem;
  color: var(--text-light);
}

.specialization {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 15px;
  text-align: center;
}

.specialization .label {
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 5px;
}

.specialization .value {
  color: var(--text-light);
  font-size: 0.95rem;
}

.description {
  font-size: 0.9rem;
  color: var(--text-light);
  text-align: center;
  margin-bottom: 20px;
  flex-grow: 1;
}

.card-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: auto;
}

.view-profile-btn,
.quick-contact-btn {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  padding: 12px;
  border-radius: 30px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: var(--transition);
  width: 100%;
  border: none;
}

.view-profile-btn {
  background-color: var(--secondary-color);
  color: var(--white);
}

.view-profile-btn:hover {
  background-color: var(--secondary-dark);
  transform: translateY(-2px);
}

.quick-contact-btn {
  background-color: transparent;
  color: var(--primary-color);
  border: 2px solid var(--primary-color);
}

.quick-contact-btn:hover {
  background-color: var(--primary-light);
  transform: translateY(-2px);
}

/* No Results */
.no-results {
  text-align: center;
  padding: 60px 0;
  grid-column: 1 / -1;
}

.no-results img {
  max-width: 200px;
  margin-bottom: 20px;
  opacity: 0.7;
}

.no-results h3 {
  font-size: 1.5rem;
  margin-bottom: 10px;
  color: var(--text-dark);
}

.no-results p {
  font-size: 1rem;
  color: var(--text-light);
  max-width: 500px;
  margin: 0 auto;
}

/* Help Section Styles */
.help-section {
  background-color: var(--primary-light);
  padding: 60px 0;
}

.help-content {
  display: flex;
  align-items: center;
  gap: 40px;
}

.help-text {
  flex: 1;
}

.help-text h2 {
  font-size: 2rem;
  margin-bottom: 15px;
  color: var(--text-dark);
}

.help-text p {
  margin-bottom: 25px;
  color: var(--text-light);
  font-size: 1.1rem;
}

.emergency-btn {
  background-color: #e74c3c;
  color: var(--white);
  border: none;
  padding: 12px 25px;
  border-radius: 30px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: var(--transition);
  box-shadow: 0 5px 15px rgba(231, 76, 60, 0.2);
}

.emergency-btn:hover {
  background-color: #c0392b;
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
}

.help-image {
  flex: 1;
  text-align: center;
}

.help-image img {
  max-width: 100%;
  height: auto;
  max-height: 300px;
}

/* Modal Styles */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  z-index: 1000;
  justify-content: center;
  align-items: center;
  animation: fadeIn 0.3s ease;
}

.modal.show {
  display: flex;
}

.modal-content {
  background-color: var(--white);
  border-radius: var(--border-radius);
  width: 90%;
  max-width: 500px;
  padding: 30px;
  position: relative;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  animation: slideIn 0.3s ease;
}

  </style>
</head>
<body>
  <!-- Header Section -->
  <header class="header">
    <div class="logo">
      <img src="/GroupProject-IS2102/assets/images/logo.jpg" alt="RelaxU Logo">
      <h1>RelaxU</h1>
    </div>
    <nav class="navbar">
      <ul>
        <li><a href="../views/home.php">Home</a></li>
        <li class="services">
          <a href="#">Services</a>
          <ul class="dropdown">
            <li><a href="../../views/stress_managment_form.php">Stress Monitoring</a></li>
            <li><a href="../../views/relaxation_activities_suggester.php">Relaxation Activities</a></li>
            <li><a href="#">Workload Management Tools</a></li>
          </ul>
        </li>
        <li><a href="../../views/Academic_Help.php">Academic Help</a></li>
        <li><a href="../../controller/CounselorController.php?action=list" class="active">Counseling</a></li>
        <li><a href="../../controller/CommunityController.php?action=list">Community</a></li>
        <li><a href="../../views/About_Us.php">About Us</a></li>
      </ul>
    </nav>
    <div class="auth-buttons">
      <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
      <form action="/GroupProject-IS2102/util/logout.php" method="post" style="display: inline">
        <button type="submit" class="login"><b>Log Out</b></button>
      </form>
    </div>
  </header>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <h1>Our Counseling Team</h1>
      <p>Connect with experienced counselors who can help you navigate academic stress and personal challenges</p>
    </div>
  </section>

  <!-- Filter and Search Section -->
  <section class="filter-section">
    <div class="container">
      <div class="search-bar">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" placeholder="Search by name or specialization...">
        </div>
        <div class="filter-options">
          <div class="filter-dropdown">
            <select id="typeFilter">
              <option value="all">All Types</option>
              <option value="Student">Student Counselor</option>
              <option value="Professional">Professional</option>
              <option value="Psychiatrist">Psychiatrist</option>
            </select>
          </div>
          <button id="showFiltersBtn" class="filter-btn">
            <i class="fas fa-sliders"></i> More Filters
          </button>
        </div>
      </div>
      
      <div id="advancedFilters" class="advanced-filters">
        <div class="filter-group">
          <h4>Availability</h4>
          <div class="checkbox-group">
            <label>
              <input type="checkbox" value="weekday"> Weekdays
            </label>
            <label>
              <input type="checkbox" value="weekend"> Weekends
            </label>
            <label>
              <input type="checkbox" value="evening"> Evenings
            </label>
          </div>
        </div>
        <div class="filter-group">
          <h4>Rating</h4>
          <div class="rating-filter">
            <label>
              <input type="radio" name="rating" value="all" checked> All
            </label>
            <label>
              <input type="radio" name="rating" value="4"> 4+ Stars
            </label>
            <label>
              <input type="radio" name="rating" value="3"> 3+ Stars
            </label>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Counselor List Section -->
  <section class="counselor-section">
    <div class="container">
      <div class="counselor-list">
        <?php if (!empty($counselors)): ?>
          <?php foreach ($counselors as $counselor): ?>
<!-- Replace this in your counsellor_index.php file -->
<div class="counselor-card" data-type="<?= htmlspecialchars($counselor['type']) ?>">
    <div class="card-banner"></div>
    <div class="card-image">
        <?php if ($counselor['profile_image']): ?>
            <img src="<?= htmlspecialchars($counselor['profile_image']) ?>" alt="<?= htmlspecialchars($counselor['name']) ?>">
        <?php else: ?>
            <div class="initials"><?= strtoupper(substr($counselor['name'], 0, 1)) ?></div>
        <?php endif; ?>
    </div>
    <div class="card-content">
        <h3 class="counselor-name"><?= htmlspecialchars($counselor['name']) ?></h3>
        <span class="counselor-type"><?= htmlspecialchars($counselor['type']) ?></span>
        
        <?php
        // Fetch actual reviews and calculate average rating
        $counselorId = $counselor['id'];
        $reviews = $this->counselorModel->getReviewsByCounselorId($counselorId);
        
        // Calculate average rating
        $totalRating = 0;
        $reviewCount = count($reviews);
        
        if ($reviewCount > 0) {
            foreach ($reviews as $review) {
                $totalRating += $review['rating'];
            }
            $averageRating = $totalRating / $reviewCount;
        } else {
            $averageRating = 0;
            $reviewCount = 0;
        }
        
        // Round to nearest half for star display
        $roundedRating = round($averageRating * 2) / 2;
        $fullStars = floor($roundedRating);
        $hasHalfStar = ($roundedRating - $fullStars) >= 0.5;
        ?>
        
        <div class="rating-container">
            <div class="stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= $fullStars): ?>
                        <i class="fas fa-star"></i>
                    <?php elseif ($i == $fullStars + 1 && $hasHalfStar): ?>
                        <i class="fas fa-star-half-alt"></i>
                    <?php else: ?>
                        <i class="far fa-star"></i>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <span class="review-count"><?= $reviewCount ?> review<?= $reviewCount != 1 ? 's' : '' ?></span>
        </div>
        
        <div class="specialization">
            <span class="label">Specialization:</span>
            <span class="value">
                <?= $counselor['specialization'] ? htmlspecialchars($counselor['specialization']) : 'General Counseling' ?>
            </span>
        </div>
        
        <?php if (!empty($counselor['description'])): ?>
            <p class="description">
                <?= substr(htmlspecialchars($counselor['description']), 0, 100) ?>...
            </p>
        <?php endif; ?>
        
        <div class="card-actions">
            <form action="CounselorController.php" method="GET">
                <input type="hidden" name="action" value="viewCounselor">
                <input type="hidden" name="id" value="<?= $counselor['id'] ?>">
                <button type="submit" class="view-profile-btn">
                    <i class="fas fa-user-circle"></i> View Profile
                </button>
            </form>
        </div>
    </div>
</div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-results">
            <img src="/GroupProject-IS2102/assets/images/no-results.svg" alt="No counselors found">
            <h3>No Counselors Available</h3>
            <p>We couldn't find any counselors matching your criteria. Please try adjusting your filters or check back later.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Need Help Section -->
  <section class="help-section">
    <div class="container">
      <div class="help-content">
        <div class="help-text">
          <h2>Need Immediate Support?</h2>
          <p>If you're experiencing a crisis or need immediate assistance, our support team is available 24/7.</p>
          <button class="emergency-btn">
            <i class="fas fa-phone"></i> Contact Emergency Support
          </button>
        </div>
        <div class="help-image">
          <img src="/GroupProject-IS2102/assets/images/emergency_support.png" alt="Emergency Support">
        </div>
      </div>
    </div>
  </section>

  <!-- Footer Section -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-logo">
        <h1>RelaxU</h1>
        <p>Relax and Refresh while Excelling in your Studies</p>
        <img id="footer-logo" src="/GroupProject-IS2102/assets/images/logo.jpg" alt="RelaxU Logo">
      </div>
      <div class="footer-section">
        <h3>Services</h3>
        <ul>
          <li><a href="../../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
          <li><a href="../../views/relaxation_activities.php">Relaxation Activities</a></li>
          <li><a href="../../views/Academic_Help.php">Academic Help</a></li>
          <li><a href="../../controller/CounselorController.php?action=list">Counseling</a></li>
          <li><a href="../../controller/CommunityController.php?action=list">Community</a></li>
          <li><a href="#">Workload Management Tools</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Contact</h3>
        <p><i class="fas fa-phone"></i> +14 5464 8272</p>
        <p><i class="fas fa-envelope"></i> contact@relaxu.com</p>
        <p><i class="fas fa-map-marker-alt"></i> University Campus, Building 192</p>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="../../views/About_Us.php">About Us</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms Of Use</a></li>
        </ul>
      </div>
    </div>
    <div class="social-media">
      <ul>
        <li><a href="#"><img src="/GroupProject-IS2102/assets/images/facebook.png" alt="Facebook"></a></li>
        <li><a href="#"><img src="/GroupProject-IS2102/assets/images/twitter.png" alt="Twitter"></a></li>
        <li><a href="#"><img src="/GroupProject-IS2102/assets/images/instagram.png" alt="Instagram"></a></li>
        <li><a href="#"><img src="/GroupProject-IS2102/assets/images/youtube.png" alt="YouTube"></a></li>
      </ul>
    </div>
    <div class="footer-bottom">
      <p>Copyright © 2024 RelaxU - All Rights Reserved</p>
    </div>
  </footer>

  <!-- Schedule Modal (Hidden by default) -->
  <div id="scheduleModal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Schedule a Session</h2>
      <form id="scheduleForm">
        <div class="form-group">
          <label for="session-date">Date</label>
          <input type="date" id="session-date" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
          <label for="session-time">Time</label>
          <select id="session-time" required>
            <option value="">Select a time</option>
            <option value="09:00">9:00 AM</option>
            <option value="10:00">10:00 AM</option>
            <option value="11:00">11:00 AM</option>
            <option value="13:00">1:00 PM</option>
            <option value="14:00">2:00 PM</option>
            <option value="15:00">3:00 PM</option>
            <option value="16:00">4:00 PM</option>
          </select>
        </div>
        <div class="form-group">
          <label for="session-type">Session Type</label>
          <select id="session-type" required>
            <option value="">Select session type</option>
            <option value="video">Video Call</option>
            <option value="audio">Audio Call</option>
            <option value="in-person">In Person</option>
          </select>
        </div>
        <div class="form-group">
          <label for="session-notes">Notes (Optional)</label>
          <textarea id="session-notes" rows="3" placeholder="Anything specific you'd like to discuss?"></textarea>
        </div>
        <button type="submit" class="schedule-submit-btn">Confirm Appointment</button>
      </form>
    </div>
  </div>
</body>
</html>
