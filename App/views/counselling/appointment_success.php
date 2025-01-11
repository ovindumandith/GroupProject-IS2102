<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Success</title>
    <style>

           * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background-color: #f9f9f9; /* Subtle background color */
    }

    .success-container {
        flex: 1; /* This ensures it grows and takes available space */
        display: flex;
        flex-direction: column;
        justify-content: center; /* Centers vertically */
        align-items: center; /* Centers horizontally */
        text-align: center; /* Centers text */
        background: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 90%;
        margin: auto; /* Centers in the available body flexbox */
    }

    .sucess-h1 {
        font-size: 2rem;
        color:  #2c3e50;
        
        margin-bottom: 15px;
        font-weight: bold;
    }

    p {
        font-size: 1.1rem;
        
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .icon {
        font-size: 3rem;
        color: #27ae60;
        margin-bottom: 15px;
    }

    /* Toast Message Styles */
#toast {
    visibility: hidden;
    max-width: 350px;
    margin: auto;
    background-color: #ffffff; /* White background */
    color: #009f77; /* Matching green text */
    text-align: center;
    border: 2px solid #009f77; /* Green border */
    border-radius: 8px;
    padding: 16px;
    position: fixed;
    z-index: 1000;
    left: 50%;
    top: 30px;
    transform: translateX(-50%);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    font-family: Arial, sans-serif;
    font-size: 1rem;
    font-weight: bold;
    letter-spacing: 0.5px;
}
    #toast.show {
        visibility: visible;
        animation: fadeInOut 4s;
    }

    @keyframes fadeInOut {
        0%, 100% { opacity: 0; }
        10%, 90% { opacity: 1; }
    }
    </style>
    
<link rel="stylesheet" href="/GroupProject-IS2102/assets/css/header_footer.css">
</head>
<body>
        <header class="header">
      <div class="logo">
        <img src="/GroupProject-IS2102/assets/images/logo.jpg" alt="RelaxU Logo" />
        <h1>RelaxU</h1>
      </div>
      <nav class="navbar">
        <ul>
          <li><a href="../../views/home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="../../views/stress_managment_form.php">Stress Monitoring</a></li>
              <li><a href="../../views/relaxation_activities.php">Relaxation Activities</a></li>
              <li><a href="#">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="../../views/Academic_Help.php">Academic Help</a></li>
          <li><a href="../../controller/CounselorController.php?action=list">Counseling</a></li>
          <li><a href="../../controller/CommunityController.php?action=list">Community</a></li>
          <li><a href="../../views/About_Us.php">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
        <form action="../../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>


      </div>
    </header><br><br>
    <div class="success-container">
        <div class="icon">✅</div>
        <h1 class="sucess-h1">Appointment Slot Requested!</h1>
        <p>Your appointment has been successfully requested. We will notify you via email soon. Thank you for reaching out!</p>
    </div>

    <!-- Toast -->
    <div id="toast">✅ Appointment requested! We will inform you by email.</div>

    <script>
        // Check if 'success' flag exists in the URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            showToast();
        }

        // Function to show the toast message
        function showToast() {
            const toast = document.getElementById("toast");
            toast.classList.add("show");
            setTimeout(() => {
                toast.classList.remove("show");
            }, 4000); // Toast disappears after 4 seconds
        }
    </script>
    <br><br>
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Relax and Refresh while Excelling in your Studies</p>
          <img
            id="footer-logo"
            src="/GroupProject-IS2102/assets/images/logo.jpg"
            alt="RelaxU Logo"
          />
        </div>
        <div class="footer-section">
          <h3>Services</h3>
          <ul>
            <li><a href="../../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
            <li><a href="../../views/relaxation_activities.php">Relaxation Activities</a></li>
            <li><a href="../../views/Academic_Help.php">Academic Help</a></li>
            <li><a href="../../controller/CounselorController.php?action=list">Counseling</a></li>
            <li><a href="../../controller/CommunityController.php?action=list">Community</a></li>
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
              ><img src="/GroupProject-IS2102/assets/images/facebook.png" alt="Facebook"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="/GroupProject-IS2102/assets/images/twitter.png" alt="Twitter"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="/GroupProject-IS2102/assets/images/instagram.png" alt="Instagram"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="/GroupProject-IS2102/assets/images/youtube.png" alt="YouTube"
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

