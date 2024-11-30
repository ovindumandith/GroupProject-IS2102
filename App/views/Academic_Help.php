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
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../../assets/css/AcademicH.css" />
    <script src="../../assets/js/AcademicH.js"></script>
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

    <!-- Content Section (for demonstration) -->
    <div class="container">
    <!-- Header Section -->
    <header class="aheader">
      <h1>ACADEMIC HELP</h1>
      <p>Unlock Your Academic Potential with Confidence! ✨</p>
      <p>📚 From Stress to Success – We've Got Your Back! 📖</p>
    </header>

    <!-- Image Section -->
    <section class="banner">
      <img src="../../assets/images/AH1.png" alt="Student in Library">
    </section>

    <!-- Form Section -->
    <section class="form-section">
  <div class="form-container">
    <!-- Left Side: Image -->
    <div class="form-image">
      <img src="../../assets/images/AH2.png" alt="Academic Help" />
    </div>

    <!-- Right Side: Form -->
    <div class="form-content">
      <h2>ARE THERE ANY QUESTIONS?</h2>
      <form id="academic-help-form">
        <div class="form-row">
          <input type="text" placeholder="Index No" name="indexNo" required>
          <input type="text" placeholder="Registration No" name="registrationNo" required>
        </div>
        <div class="form-row">
          <input type="text" placeholder="Name" name="name" required>
          <input type="text" placeholder="Faculty" name="faculty" required>
        </div>
        <div class="form-row">
          <input type="email" placeholder="Email" name="email" required>
          <input type="tel" placeholder="Phone" name="phone" required>
        </div>
        <textarea placeholder="Define Your Question" name="problem" required></textarea>
        <button type="submit">Send Your Question</button>
      </form>
    </div>
  </div>
</section>

<div class="faq-container">
    <div class="faq-header">FREQUENTLY ASKED QUESTIONS</div>
    <div class="faq-item">
      <div class="faq-question">
      Where can I find credible resources for my assignments?
        <span class="faq-toggle">▼</span>
      </div>
      <div class="faq-answer">
        Credible resources for academic assignments can be found in your university library’s online databases, such as JSTOR or PubMed. Peer-reviewed journals, research papers, and textbooks recommended by your instructor are excellent sources. Reputable websites with domains like .gov, .edu, or .org can also provide reliable information. Avoid using unverified blogs or Wikipedia as primary references for your work.
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
      How do I manage my time effectively for academic success?
        <span class="faq-toggle">▼</span>
      </div>
      <div class="faq-answer">
      Effective time management involves organizing tasks and maintaining a balance. Create a daily or weekly schedule prioritizing important tasks and break larger projects into smaller steps. Use productivity tools like Google Calendar or Trello to track deadlines. Set realistic goals, avoid procrastination, and ensure you allocate time for breaks and self-care to maintain focus and avoid burnout.
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
      How can I improve my academic writing skills?
        <span class="faq-toggle">▼</span>
      </div>
      <div class="faq-answer">
      Improving academic writing requires consistent practice and attention to detail. Start by reading academic papers to understand their structure and style. Practice writing essays or reports regularly and use tools like Grammarly to polish grammar and style. Attend writing workshops or seek support from your university’s writing center. Receiving feedback from peers or instructors can also help you refine your skills.</div>
    </div>
  </div>
  <script>
    document.querySelectorAll('.faq-question').forEach(question => {
      question.addEventListener('click', () => {
        const faqItem = question.parentElement;
        faqItem.classList.toggle('active');
      });
    });
  </script>
  
<div class="container">
    <h1>View Your Previous Questions with Answers</h1>
    <div class="questions-section">
      <div class="question-card">
      <h3>What are the best tools for managing time as a student?</h3><br>
        <div class="auth-buttonss">
        <button>View Answer</button>
      </div>
        <p>20/09/2024</p>
      </div>
      <div class="question-card">
      <h3>How do I prepare effectively for final exams?</h3><br>
        <div class="auth-buttonss">
        <button>View Answer</button>
      </div>
        <p>20/09/2024</p>
      </div>
      <div class="question-card">
      <h3>What resources are available for finding scholarships?</h3><br>
        <div class="auth-buttonss">
        <button>View Answer</button>
      </div>
        <p>20/09/2024</p>
      </div>
      <div class="question-card">
      <h3> What are effective ways to deal with academic stress?</h3><br>
        <div class="auth-buttonss">
        <button>View Answer</button>
      </div>
        <p>20/09/2024</p>
      </div>
    </div>
    <h2>Explore Student Resources</h2>
    <div class="resources-container">
    <!-- Resource Card 1 -->
    <div class="resource-card">
      <img src="../../assets/images/mental3.png" alt="STUDY TECHNIQUES" class="resource-image">
      <div class="resource-content">
        <p class="resource-category">STUDY TECHNIQUES</p>
        <h3 class="resource-title">Effective Study Strategies for College Students</h3>
        <p class="resource-description">Learn proven study techniques like active recall, spaced repetition, and time-blocking to enhance your learning process and achieve better academic results.
          </p>
          <a href=#>Explore Study Techniques</a>
      </div>
    </div>

    <!-- Resource Card 2 -->
    <div class="resource-card">
      <img src="../../assets/images/mental2.png" alt="CAREER GUIDANCE" class="resource-image">
      <div class="resource-content">
        <p class="resource-category">CAREER GUIDANCE</p>
        <h3 class="resource-title">How to Build a Strong Academic Resume</h3>
        <p class="resource-description">Discover tips and templates to create a professional academic resume that highlights your achievements and skills to stand out in applications for internships, scholarships, and jobs.</p>
        <a href=#>Build Your Resume</a></div>
    </div>

    <!-- Resource Card 3 -->
    <div class="resource-card">
      <img src="../../assets/images/mental1.png" alt="MENTAL HEALTH" class="resource-image">
      <div class="resource-content">
        <p class="resource-category">MENTAL HEALTH SUPPORT</p>
        <h3 class="resource-title">Managing Stress During Exams</h3>
        <p class="resource-description">Explore practical ways to cope with stress and maintain mental well-being during exam periods, including mindfulness exercises, time management tips, and relaxation techniques.</p>
        <a href=#>Manage Exam Stress</a></div>
    </div>
  </div>>
  </div>
    

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
            <a href="#"><img src="../../assets/images/facebook.png" alt="Facebook" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/twitter.png" alt="Twitter" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/instagram.png" alt="Instagram" /></a>
          </li>
          <li>
            <a href="#"><img src="../../assets/images/youtube.png" alt="YouTube" /></a>
          </li>
        </ul>
      </div>
      <div class="footer-bottom">
        <p>copyright 2024 @RelaxU all rights reserved</p>
      </div>
    </footer>
  </body>
</html>
