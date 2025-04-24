<?php
// File: views/admin/admin_profile.php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php?error=unauthorized');
    exit();
}

// Get profile data from session
$profileData = $_SESSION['admin_profile'] ?? [];
$stats = $_SESSION['system_stats'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Main content styles */
        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif;
        }
        
        h2 {
            color: #009f77;
            margin-bottom: 25px;
            text-align: center;
            font-size: 28px;
        }
        
        /* Alert styles */
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Profile container */
        .profile-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        /* Sidebar */
        .profile-sidebar {
            flex: 1;
            min-width: 300px;
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #e0f5f0;
            color: #009f77;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 60px;
        }
        
        .profile-name {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        
        .profile-role {
            text-align: center;
            color: #009f77;
            font-weight: 500;
            margin-bottom: 25px;
        }
        
        .profile-stats {
            margin: 25px 0;
        }
        
        .stats-title {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .stats-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .stats-item:last-child {
            border-bottom: none;
        }
        
        .stats-label {
            color: #555;
        }
        
        .stats-value {
            font-weight: 600;
            color: #333;
        }
        
        .stat-highlight {
            color: #009f77;
        }
        
        /* Forms */
        .profile-content {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .profile-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-title {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: #009f77;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 159, 119, 0.1);
        }
        
        .form-actions {
            margin-top: 25px;
            text-align: right;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #009f77;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #00815f;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        /* Info items */
        .info-item {
            display: flex;
            margin-bottom: 15px;
        }
        
        .info-label {
            width: 120px;
            font-weight: 500;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .required-field::after {
            content: "*";
            color: #e74c3c;
            margin-left: 4px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .profile-container {
                flex-direction: column;
            }
            
            .profile-sidebar {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="logo">
            <img src="../../../assets/images/logo.jpg" alt="RelaxU Logo" />
            <h1>RelaxU</h1>
        </div>
      <nav class="navbar">
        <ul>
          <li><a href="../admin_home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="../../controller/AdminStressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
              <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
              <li><a href="./workload.php">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="../../controller/Academic_QuestionsController.php?action=viewAllQuestions">Academic Help</a></li>
          <li><a href="../../controller/AppointmentController.php?action=viewAppointments">Counseling</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </nav>
        <div class="auth-buttons">
            <form action="../../controller/AdminProfileController.php" method="GET">
  <input type="hidden" name="action" value="viewProfile">
  <button type="submit" class="login"><b>Profile</b></button>
</form>
            <form action="../../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="logout-btn"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
        <h2>Admin Profile</h2>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error_message']; 
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-container">
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3 class="profile-name"><?= htmlspecialchars($profileData['username'] ?? 'Admin User') ?></h3>
                <div class="profile-role">System Administrator</div>
                
                <div class="info-item">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?= htmlspecialchars($profileData['email'] ?? 'Email not available') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone:</div>
                    <div class="info-value"><?= htmlspecialchars($profileData['phone'] ?? 'Phone not available') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Member Since:</div>
                    <div class="info-value"><?= isset($profileData['created_at']) ? date('M d, Y', strtotime($profileData['created_at'])) : 'Unknown' ?></div>
                </div>
                
                <div class="profile-stats">
                    <h4 class="stats-title">System Overview</h4>
                    
                    <div class="stats-item">
                        <div class="stats-label">Total Users</div>
                        <div class="stats-value"><?= array_sum($stats['user_stats'] ?? []) ?></div>
                    </div>
                    
                    <div class="stats-item">
                        <div class="stats-label">Students</div>
                        <div class="stats-value"><?= $stats['user_stats']['student'] ?? 0 ?></div>
                    </div>
                    
                    <div class="stats-item">
                        <div class="stats-label">Lecturers</div>
                        <div class="stats-value"><?= $stats['user_stats']['lecturer'] ?? 0 ?></div>
                    </div>
                    
                    <div class="stats-item">
                        <div class="stats-label">Academic Questions</div>
                        <div class="stats-value"><?= $stats['academic_questions_count'] ?? 0 ?></div>
                    </div>
                    
                    <div class="stats-item">
                        <div class="stats-label">Counseling Appointments</div>
                        <div class="stats-value"><?= array_sum($stats['appointment_stats'] ?? []) ?></div>
                    </div>
                    
                    <div class="stats-item">
                        <div class="stats-label">Pending Appointments</div>
                        <div class="stats-value stat-highlight"><?= $stats['appointment_stats']['Pending'] ?? 0 ?></div>
                    </div>
                </div>
            </div>
            
            <div class="profile-content">
                <div class="profile-form">
                    <h3 class="form-title">Edit Profile Information</h3>
                    <form action="../../controller/AdminProfileController.php?action=updateProfile" method="POST">
                        <div class="form-group">
                            <label for="username" class="required-field">Username</label>
                            <input type="text" id="username" name="username" value="<?= htmlspecialchars($profileData['username'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="required-field">Email Address</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($profileData['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($profileData['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="profile-form">
                    <h3 class="form-title">Change Password</h3>
                    <form action="../../controller/AdminProfileController.php?action=updatePassword" method="POST">
                        <div class="form-group">
                            <label for="current_password" class="required-field">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password" class="required-field">New Password</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="required-field">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1>RelaxU</h1>
                <p>Your mental health, your priority.</p>
                <img id="footer-logo" src="../../../assets/images/logo.jpg" alt="RelaxU Logo" />
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="admin_home.php">Dashboard</a></li>
                    <li><a href="../../controller/AppointmentController.php?action=viewAppointments">Counseling</a></li>
                    <li><a href="#">Users</a></li>
                    <li><a href="#">Reports</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Support</h3>
                <p>Email: support@relaxu.com</p>
                <p>Phone: +1 800-RELAXU</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 RelaxU. All Rights Reserved.</p>
        </div>
    </footer>
    
    <script>
        // Password validation
        document.addEventListener('DOMContentLoaded', function() {
            const passwordForm = document.querySelector('.profile-form:nth-child(2) form');
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            passwordForm.addEventListener('submit', function(e) {
                if (newPassword.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('New passwords do not match. Please try again.');
                }
            });
        });
    </script>
</body>
</html>