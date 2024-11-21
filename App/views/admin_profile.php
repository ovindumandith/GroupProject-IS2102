<?php
session_start();
require '../../config/config.php'; // Ensure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Database connection failed.");
}

// Fetch user information
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, phone, role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bindParam(1, $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

$update_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    

    $update_query = "UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(1, $username);
    $update_stmt->bindParam(2, $email);
    $update_stmt->bindParam(3, $phone);
    $update_stmt->bindParam(4, $user_id, PDO::PARAM_INT);
    
    if ($update_stmt->execute()) {
        $update_success = true;
    } else {
        echo "Error updating profile: " . $conn->errorInfo()[2];
    }
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
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/user_profile.css" type="text/css" />
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
          <li><a href="../views/admin_home.php">Home </a></li>    
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="#">Stress Monitoring</a></li>
              <li><a href="./admin_page_relaxation activites.php">Relaxation Activities</a></li>
              <li><a href="#">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="#">Academic Help</a></li>
          <li><a href="#">Counseling</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <button class="signup" onclick="location.href='admin_profile.php'"><b>Profile</b></button>
        <form action="../../util/logout.php" method="post" style="display: inline">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <!-- Content Section (for demonstration) -->
    <div class="content">
        <h1> User Profile</h1>
         
    
    <form method="POST" action="admin_profile.php" id="updateform">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>


        <input type="submit" value="Update Profile">
    </form>
      <p></p>
    </div>
    <div id="toast" class="toast">Profile updated successfully!</div>

  <script>
        <?php if ($update_success): ?>
            // Show the toast message
            const toast = document.getElementById("toast");
            toast.classList.add("show");

            // Hide the toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove("show");
                // Redirect after toast is hidden
                window.location.href = 'admin_profile.php';
            }, 3000);
        <?php endif; ?>
    </script>

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



