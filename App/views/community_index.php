<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php?error=unauthorized');
    exit();

}

$_SESSION['current_post_id'] = $postId;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Community Blog</title>
  <link rel="stylesheet" href="../../assets/css/header_footer.css" />
  <link rel="stylesheet" href="../../assets/css/Community.css">
  <script defer src="../../assets/js/Community1.js"></script>
  <script defer src="../../assets/js/searchpost.js"></script>
  <script defer src="../../assets/js/likepost.js"></script>
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
        <button class="signup" onclick="location.href='../views/profile.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <section class="hero">
    <h1>JOIN OUR COMMUNITY</h1>
        <div class="hero-content">
            <p>Connect with others on a deeper level. <br>Share, Listen & Grow Together...</p>
            <button class="btn" onclick="document.getElementById('target-section').scrollIntoView({ behavior: 'smooth' });">
            Get Started</button>
            <br>
        </div>

          <div class="image-slider"> <br/>
          <div class="image-slide active">
            <img src="../../assets/images/comm1.png" alt="Image 1" />
            <h3>“Success is not final; failure is not fatal: It is the courage to continue that counts.”<br>
                –  Discover stories  –</h3>
          </div>
          <div class="image-slide">
            <img src="../../assets/images/comm2.png" alt="Image 2" />
            <h3>“You are never too old to set another goal or to dream a new dream.” <br>
             –  Share ideas  – </h3>
          </div>
          <div class="image-slide">
            <img src="../../assets/images/comm3.png" alt="Image 3" />
            <h3>“The greatest weapon against stress is our ability to choose one thought over another.”<br>
             –  Build a supportive space  –</h3>
          </div>
          <div class="slider-buttons">
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
          </div>
          </div>
    </section>
    
    <section class="blog-section" id="target-section">
        <section class="blog-section">
        <h2 id="target-section">Get Started</h2>
          <div class="search-bar">
            <a href="/GroupProject-IS2102/App/views/create_post.php" class="styled-button">Create your post</a>    
            <a href="/GroupProject-IS2102/App/views/User_notifications.php" class="styled-button">View Admin Notifications</a> 
            <a href="/GroupProject-IS2102/App/views/manage_post.php" class="styled-button">Your Personal Posts</a>
          </div><br>
          <div class="blog-section">
              <h2>Our Blog For You</h2>
          </div>
      <div class="search-bar">
      <input type="text" id="searchBox" placeholder="Search by post title..." style="margin-bottom: 20px; padding: 8px; width: 300px;">
      </div><br>

      <div class="posts-container">

      <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
            <div class="post-header">
                <h4><strong><img src="../../assets/images/Account.png" alt="Profile" class="profile-picc"></strong> <?= htmlspecialchars($post['username']) ?></h4>
            </div>
                <br><h3><?= htmlspecialchars($post['title']) ?></h3><br>
                <?php if (!empty($post['image_path'])): ?>
                  <img src="<?= $post['image_path'] ?>" alt="Post image">
                <?php endif; ?>
                <p><?= nl2br(htmlspecialchars($post['description'])) ?></p>
                <br><hr>
                <div class="post-footer">
                  <button class="like-btn" id="likeButton1"> ❤️ 30 Likes</button>
                  <a href="/GroupProject-IS2102/App/views/comment_post.php?post_id=<?= $post['post_id'] ?>">
                    <button id="add-comment-button"><img src="../../assets/images/Comment.png" alt="Share" class="cmt-img"></button>
                  </a>
                </div>
                <small>Posted on: <?= htmlspecialchars($post['created_at']) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts available.</p>
    <?php endif; ?>
    
        <!-- Post 1 -->
        <article class="post">
          <div class="post-header">
            <img src="../../assets/images/Account.png" alt="Profile" class="profile-pic">
            <h4>Camy</h4>
          </div>
          <h3>My Journey to Better Mental Health</h3>
          <br>
          <img src="../../assets/images/Post1.png" alt="Post Image" class="post-img"><br>
          <p>
          We all face challenges, and sometimes, those challenges feel insurmountable. In this heartfelt post, I share my personal experience with mental health struggles, how they impacted my life, and the steps I took to regain control. From practicing mindfulness to embracing self-care routines, this journey was filled with ups and downs, but it ultimately led to a stronger, healthier version of myself. I hope my story inspires you to take that first step toward prioritizing your mental well-being. Join the conversation, share your story, or simply know that you’re not alone in this.
          </p><br><hr>
          <div class="post-footer">
          <button class="like-btn" id="likeButton2"> ❤️ 30 Likes</button>
              <a href="/GroupProject-IS2102/App/views/comment_post.php?post_id=<?= $post['post_id'] ?>">
                <button id="add-comment-button"><img src="../../assets/images/Comment.png" alt="Share" class="cmt-img"></button>
              </a>
            </div>
          <small>Posted on: 2024-12-02 17:57:46 </small>
        </article>
  
        <!-- Post 2 -->
        <article class="post">
          <div class="post-header">
            <img src="../../assets/images/Account.png" alt="Profile" class="profile-pic">
            <h4>Julian</h4>
          </div>
          <h3>5-Minute Relaxation Techniques for Busy Days</h3><br>
          <img src="../../assets/images/Post2.png" alt="Post Image" class="post-img"><br>
          <p>
          Life can be overwhelming, and sometimes finding time to relax feels impossible. But even a short break can make a difference! This post introduces quick and practical relaxation methods, like focused deep breathing, mindful stretching, or even a five-minute guided meditation. These techniques are designed to fit into your busiest days, helping you recharge your mind and body. Whether you’re at work, studying, or managing multiple responsibilities, take a moment to explore these simple ways to restore balance to your life.
          </p><br><hr>
          <div class="post-footer">
              <button class="like-btn" id="likeButton3"> ❤️ 30 Likes</button>
              <a href="/GroupProject-IS2102/App/views/comment_post.php?post_id=<?= $post['post_id'] ?>">
                    <button id="add-comment-button"><img src="../../assets/images/Comment.png" alt="Share" class="cmt-img"></button>
                  </a>
          </div>
          <small>Posted on: 2024-12-01 01:57:30 </small>
        </article>
  
        <!-- Post 3 -->
        <article class="post">
          <div class="post-header">
            <img src="../../assets/images/Account.png" alt="Profile" class="profile-pic">
            <h4>Stefan</h4>
          </div>
          <h3>How Exercise Can Help You Beat Stress</h3><br>
          <img src="../../assets/images/Post3.png" alt="Post Image" class="post-img"><br>
          <p>
          Feeling tense or overwhelmed? Moving your body might be the solution! This post delves into how physical activity not only strengthens your body but also calms your mind. Whether it’s a quick walk, a yoga session, or an intense workout, exercise releases endorphins—your body’s natural stress relievers. We’ll explore the best types of exercises for stress reduction and how you can fit them into your daily routine, no matter how busy life gets. Start small, and let movement transform your mood and energy levels!
          </p><br><hr>
          <div class="post-footer">
              <button class="like-btn" id="likeButton4"> ❤️ 30 Likes</button>
              <a href="/GroupProject-IS2102/App/views/comment_post.php?post_id=<?= $post['post_id'] ?>">
                    <button id="add-comment-button"><img src="../../assets/images/Comment.png" alt="Share" class="cmt-img"></button>
                  </a>
          </div>
            <small>Posted on: 2024-11-30 15:57:49 </small>
        </article>       
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
