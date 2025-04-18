<!-- File: views/houg/houg_profile.php -->
<?php
session_start();

// Check if user is logged in as HOUS
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
    header('Location: ../../login.php?error=unauthorized');
    exit();
}

// Get profile data from session
$profileData = $_SESSION['hous_profile'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOUS Profile | RelaxU</title>
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
        
        /* Profile section styles */
        .profile-container {
            display: flex;
            gap: 30px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .profile-sidebar {
            flex: 1;
            min-width: 250px;
            max-width: 300px;
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e0f5f0;
            font-size: 50px;
            color: #009f77;
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
            margin-bottom: 20px;
        }
        
        .profile-info {
            margin-top: 15px;
        }
        
        .profile-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .profile-info-icon {
            margin-right: 15px;
            color: #009f77;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }
        
        .profile-info-text {
            color: #555;
        }
        
        .profile-content {
            flex: 2;
            min-width: 300px;
        }
        
        .profile-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-form h3 {
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: #009f77;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 159, 119, 0.2);
        }
        
        .required-field::after {
            content: "*";
            color: #e74c3c;
            margin-left: 5px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #009f77;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #00815f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .password-form {
            margin-top: 30px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-container {
                flex-direction: column;
            }
            
            .profile-sidebar {
                max-width: 100%;
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
          <li><a href="../controller/HOUSDashboardController.php">Dashboard</a></li>
          <li><a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous">Academic Requests</a></li>
          <li><a href="../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Forwarded-Replied Questions</a></li>
          <li><a href="../controller/LecturerController.php?action=list">List of Lecturers</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="../../controller/HOUSProfileController.php?action=viewProfile" class="profile-btn active"><b>Profile</b></a>
            <form action="../../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="logout-btn"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
        <h2>My Profile</h2>
        
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
                <div class="profile-picture">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="profile-name"><?= htmlspecialchars($profileData['username'] ?? 'User') ?></h3>
                <div class="profile-role">Head of Undergraduate Studies</div>
                
                <div class="profile-info">
                    <div class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="profile-info-text">
                            <?= htmlspecialchars($profileData['email'] ?? 'Email not available') ?>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="profile-info-text">
                            <?= htmlspecialchars($profileData['phone'] ?? 'Phone not available') ?>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="profile-info-text">
                            Member since: <?= isset($profileData['created_at']) ? date('M d, Y', strtotime($profileData['created_at'])) : 'Unknown' ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="profile-content">
                <div class="profile-form">
                    <h3>Edit Profile Information</h3>
                    <form action="../../controller/HOUSProfileController.php?action=updateProfile" method="POST">
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
                
                <div class="profile-form password-form">
                    <h3>Change Password</h3>
                    <form action="../../controller/HOUSProfileController.php?action=updatePassword" method="POST">
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
                    <li><a href="houg_home.php">Dashboard</a></li>
                    <li><a href="../../controller/AcademicQuestionsController.php?action=viewQuestions">Academic Questions</a></li>
                    <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions">Forwarded Questions</a></li>
                    <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Replied Questions</a></li>
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
        // Password confirmation validation
        document.addEventListener('DOMContentLoaded', function() {
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordForm = document.querySelector('.password-form form');
            
            passwordForm.addEventListener('submit', function(e) {
                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    e.preventDefault();
                    alert('New passwords do not match. Please try again.');
                }
            });
        });
    </script>
</body>
</html>