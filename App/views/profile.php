<?php
// This file should be located in /App/views/user/profile.php

// Additional security check
if (!isset($_SESSION['user_id']) || !isset($user)) {
    header('Location: ../views/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RelaxU - User Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/user_profile.css" type="text/css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="logo">
            <img src="../../assets/images/logo.jpg" alt="RelaxU Logo">
            <h1>RelaxU</h1>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="../views/home.php">Home</a></li>    
                <li class="services">
                    <a href="#">Services</a>
                    <ul class="dropdown">
                        <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
                        <li><a href="../views/relaxation_activities_suggester.php">Relaxation Activities</a></li>
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
            <button class="signup" onclick="location.href='../controller/UserProfileController.php?action=showProfile'"><b>Profile</b></button>
            <form action="../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="login"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <!-- Content Section -->
    <div class="content">
        <div class="page-header">
            <h1><i class="fas fa-user-circle"></i> User Profile</h1>
            <p>Manage your account information and access your appointments and academic requests</p>
        </div>
        
        <!-- Display errors if any -->
        <?php if (isset($_SESSION['update_errors']) && !empty($_SESSION['update_errors'])): ?>
            <div class="error-container">
                <?php foreach ($_SESSION['update_errors'] as $error): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['update_errors']); ?>
        <?php endif; ?>

        <div class="profile-container">
            <!-- Profile Form Card -->
            <div class="card profile-form-card">
                <div class="card-header">
                    <i class="fas fa-id-card"></i>
                    <h2>Profile Details</h2>
                </div>
                
                <form method="POST" action="../controller/UserProfileController.php?action=updateProfile" id="updateForm">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user"></i> Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password:</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
                            <button type="button" id="togglePassword" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone"><i class="fas fa-phone"></i> Phone:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="year"><i class="fas fa-calendar-alt"></i> Year:</label>
                        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($user['year']); ?>" required>
                    </div>
                    
                    <button type="submit" class="submit-button">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>

            <!-- Appointments Card -->
            <div class="card feature-card appointments-card">
                <div class="card-header">
                    <i class="fas fa-calendar-check"></i>
                    <h2>My Appointments</h2>
                </div>
                <div class="card-content">
                    <img src="../../assets/images/stu_appointment.jpg" alt="Appointments Icon" class="card-image">
                    <p>View and manage your scheduled counseling appointments</p>
                    <a href="../controller/AppointmentController.php?action=showStudentAppointments" class="action-button">
                        <i class="fas fa-external-link-alt"></i> View Appointments
                    </a>
                </div>
            </div>

            <!-- Academic Requests Card -->
            <div class="card feature-card academic-requests-card">
                <div class="card-header">
                    <i class="fas fa-graduation-cap"></i>
                    <h2>Academic Requests</h2>
                </div>
                <div class="card-content">
                    <img src="../../assets/images/stu_acareq.jpg" alt="Academic Requests Icon" class="card-image">
                    <p>Track and manage your academic help requests</p>
                    <a href="../controller/Academic_QuestionsController.php?action=viewUserQuestions" class="action-button">
                        <i class="fas fa-external-link-alt"></i> View Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="<?php echo $update_success ? 'toast show' : 'toast'; ?>">
        <i class="fas fa-check-circle"></i> Profile updated successfully!
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1>RelaxU</h1>
                <p>Relax and Refresh while Excelling in your Studies</p>
                <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU Logo">
            </div>
            <div class="footer-section">
                <h3>Services</h3>
                <ul>
                    <li><a href="../views/stress_management/stress_management_index.php">Stress Monitoring</a></li>
                    <li><a href="../views/relaxation_activities.php">Relaxation Activities</a></li>
                    <li><a href="../views/Academic_Help.php">Academic Help</a></li>
                    <li><a href="../controller/CounselorController.php?action=list">Counseling</a></li>
                    <li><a href="../controller/CommunityController.php?action=list">Community</a></li>
                    <li><a href="../views/workload.php">Workload Management Tools</a></li>
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
                    <li><a href="../views/About_Us.php">About Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms Of Use</a></li>
                </ul>
            </div>
        </div>
        <div class="social-media">
            <ul>
                <li><a href="#"><img src="../../assets/images/facebook.png" alt="Facebook"></a></li>
                <li><a href="#"><img src="../../assets/images/twitter.png" alt="Twitter"></a></li>
                <li><a href="#"><img src="../../assets/images/instagram.png" alt="Instagram"></a></li>
                <li><a href="#"><img src="../../assets/images/youtube.png" alt="YouTube"></a></li>
            </ul>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2024 RelaxU - All Rights Reserved</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Hide toast after 3 seconds
        <?php if ($update_success): ?>
            setTimeout(() => {
                document.getElementById('toast').classList.remove('show');
            }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>