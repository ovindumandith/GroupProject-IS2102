<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not logged in
  header('Location: login.php?error=unauthorized');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RelaxU</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/css/header_footer.css" />
  <link rel="stylesheet" href="../../assets/css/academic_help.css" />
  <script src="../../assets/js/academic_help.js"></script>
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
          <a href="#">Services</a>
          <ul class="dropdown">
            <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
            <li><a href="../views/relaxation_activities.php">Relaxation Activities</a></li>
            <li><a href="#">Workload Management Tools</a></li>
          </ul>
        </li>
        <li><a href="../views/Academic_Help.php">Academic Help</a></li>
        <li><a href="../controller/CounselorController.php?action=list">Counseling</a></li>
        <li><a href="../controller/CommunityController.php?action=list">Community</a></li>
        <li><a href="../views/About_Us.php">About Us</a></li>
      </ul>
    </nav>
    <div class="auth-buttons">
      <button class="signup"><b>Profile</b></button>
      <button class="login"><b>Log Out</b></button>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Academic Support Center</h1>
      <p>Get the help you need to succeed in your academic journey</p>
      <div class="hero-stats">
        <div class="stat-item">
          <span class="stat-number">24/7</span>
          <span class="stat-label">Support</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">1000+</span>
          <span class="stat-label">Questions Answered</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">98%</span>
          <span class="stat-label">Satisfaction Rate</span>
        </div>
      </div>
      <a href="#help-form" class="cta-button">Get Help Now</a>
    </div>
    <div class="wave-shape"></div>
  </section>

  <!-- Help Form Section -->
  <section id="help-form" class="help-form">
    <h2>Submit Your Academic Query</h2>
    <form id="academicForm" action="../controller/Academic_QuestionsController.php?action=submitQuestion" method="POST">
      <div class="form-row">
        <div class="form-group">
          <label for="indexNo">Index Number</label>
          <input type="text" id="indexNo" name="index_no" placeholder="Enter your index number" required>
        </div>
        <div class="form-group">
          <label for="regNo">Registration Number</label>
          <input type="text" id="regNo" name="reg_no" placeholder="Enter your registration number" required>
        </div>
      </div>
      <div class="form-group">
        <label for="fullName">Full Name</label>
        <input type="text" id="fullName" name="full_name" placeholder="Enter your full name" required>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="faculty">Faculty</label>
          <select id="faculty" name="faculty" required>
            <option value="">Select your faculty</option>
            <option value="Computing">UCSC</option>
            <option value="Engineering">Faculty of Engineering</option>
            <option value="Science">Faculty of Science</option>
            <option value="Arts">Faculty of Arts</option>
            <option value="Medicine">Faculty of Medicine</option>
            <option value="Business">Faculty of Business</option>
            <option value="Law">Faculty of Law</option>
          </select>
        </div>
        <div class="form-group">
          <label for="category">Question Category</label>
          <select id="category" name="category" required>
            <option value="">Select a category</option>
            <option value="Coursework">Coursework Issues</option>
            <option value="Assignment">Assignment Issues</option> 
            <option value="Examination">Examination Issues</option>
            <option value="Financial">Financial Aid</option>
            <option value="Mahapola">Mahapola Scholarship or Bursary</option>
            <option value="Repeat_Registration">Repeat Registration</option>
            <option value="Registration">Course Registration</option>
            <option value="Medical">Medical Issues</option>
            <option value="Accommodation">Accommodation</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="telephone">Telephone Number</label>
          <input type="tel" id="telephone" name="telephone" placeholder="Enter your phone number" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
      </div>
      <div class="form-group">
        <label for="question">Your Question</label>
        <textarea id="question" name="question" rows="4" placeholder="Type your question here..." required></textarea>
      </div>
      <button type="submit">Submit Question</button>
    </form>
  </section>

  <!-- FAQ Section -->
  <section class="faq">
    <h2>Frequently Asked Questions</h2>
    <div class="search-box">
      <input type="text" id="faqSearch" placeholder="Search FAQs...">
    </div>
    <div class="faq-container">
      <div class="faq-card">
        <div class="faq-question">
          How do I register for courses?
        </div>
        <div class="faq-answer">
          Visit the student portal, log in with your credentials, and navigate to course registration. Select your desired courses within the registration period.
        </div>
      </div>
      <div class="faq-card">
        <div class="faq-question">
          What is the deadline for assignment submissions?
        </div>
        <div class="faq-answer">
          Assignment deadlines vary by course. Check your course outline or contact your professor for specific submission dates.
        </div>
      </div>
      <div class="faq-card">
        <div class="faq-question">
          How can I access the library resources?
        </div>
        <div class="faq-answer">
          Use your student ID to access physical library resources. For digital resources, log in to the library portal using your university credentials.
        </div>
      </div>
      <div class="faq-card">
        <div class="faq-question">
          What support services are available for students?
        </div>
        <div class="faq-answer">
          We offer tutoring, counseling, career guidance, and academic advising. Visit the Student Services Center.
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
        <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
      </div>
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
        <li><a href="#"><img src="../../assets/images/facebook.png" alt="Facebook" /></a></li>
        <li><a href="#"><img src="../../assets/images/twitter.png" alt="Twitter" /></a></li>
        <li><a href="#"><img src="../../assets/images/instagram.png" alt="Instagram" /></a></li>
        <li><a href="#"><img src="../../assets/images/youtube.png" alt="YouTube" /></a></li>
      </ul>
    </div>
    <div class="footer-bottom">
      <p>copyright 2024 @RelaxU all rights reserved</p>
    </div>
  </footer>
</body>
</html>
