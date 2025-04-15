<?php
// Check if user is logged in as lecturer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php?error=unauthorized');
    exit();
}

// Get lecturer profile from session
$profile = $_SESSION['lecturer_profile'] ?? [];
$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';

// Clear messages from session
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        main {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .profile-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e0f5f0;
            color: #009f77;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            margin-right: this;
        }
        
        .profile-name-container {
            margin-left: 20px;
        }
        
        .profile-name {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 5px;
        }
        
        .profile-role {
            color: #009f77;
            font-weight: 500;
        }
        
        .section-title {
            color: #009f77;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            border: none;
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-primary {
            background-color: #009f77;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #00815f;
        }
        
        .btn-secondary {
            background-color: #f1f1f1;
            color: #333;
        }
        
        .btn-secondary:hover {
            background-color: #e1e1e1;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .form-col {
            flex: 1;
        }
        
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
        
        .nav-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .nav-tab {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .nav-tab.active {
            border-bottom: 2px solid #009f77;
            color: #009f77;
            font-weight: 500;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .edit-btn {
            background: none;
            border: none;
            color: #009f77;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .edit-btn:hover {
            color: #00815f;
        }
        
        .info-item {
            margin-bottom: 15px;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
        }
    </style>
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
                <li><a href="lecturer_home.php">Dashboard</a></li>
                <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions">Academic Questions</a></li>
                <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Replied Questions</a></li>
                <li><a href="scheduler.php">Class Schedule</a></li>
                <li><a href="grading.php">Grading</a></li>
                <li><a href="resources.php">Resources</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="../../controller/LecturerController.php?action=myProfile" class="profile-btn"><b>Profile</b></a>
            <form action="../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="logout-btn"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <?= $successMessage ?>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-error">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?= strtoupper(substr($profile['name'] ?? $profile['username'] ?? 'L', 0, 1)) ?>
                </div>
                <div class="profile-name-container">
                    <h2 class="profile-name"><?= htmlspecialchars($profile['name'] ?? $profile['username'] ?? 'Lecturer') ?></h2>
                    <p class="profile-role">Lecturer</p>
                </div>
            </div>
            
            <div class="nav-tabs">
                <div class="nav-tab active" data-tab="profile">Profile Information</div>
                <div class="nav-tab" data-tab="security">Change Password</div>
            </div>
            
            <!-- Profile Tab -->
            <div id="profile-tab" class="tab-content active">
                <h3 class="section-title">
                    <span>Personal Information</span>
                    <button type="button" class="edit-btn" id="edit-profile-btn">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </h3>
                
                <!-- View Mode -->
                <div id="profile-view-mode">
                    <div class="info-item">
                        <div class="info-label">Name:</div>
                        <div class="info-value"><?= htmlspecialchars($profile['name'] ?? 'Not provided') ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Email:</div>
                        <div class="info-value"><?= htmlspecialchars($profile['email'] ?? 'Not provided') ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Username:</div>
                        <div class="info-value"><?= htmlspecialchars($profile['username'] ?? 'Not provided') ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Department:</div>
                        <div class="info-value"><?= htmlspecialchars($profile['department'] ?? 'Not provided') ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Category:</div>
                        <div class="info-value"><?= htmlspecialchars($profile['category'] ?? 'Not provided') ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Phone:</div>
                        <div class="info-value"><?= htmlspecialchars($profile['phone'] ?? 'Not provided') ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Bio:</div>
                        <div class="info-value"><?= nl2br(htmlspecialchars($profile['bio'] ?? 'No bio provided')) ?></div>
                    </div>
                </div>
                
                <!-- Edit Mode -->
                <div id="profile-edit-mode" style="display: none;">
                    <form action="../../controller/LecturerController.php?action=myProfile" method="POST">
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="department">Department:</label>
                                    <input type="text" class="form-control" id="department" name="department" value="<?= htmlspecialchars($profile['department'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="category">Category:</label>
                                    <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($profile['category'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($profile['username'] ?? '') ?>" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="bio">Bio:</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                            <button type="button" id="cancel-edit-btn" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Security Tab -->
            <div id="security-tab" class="tab-content">
                <h3 class="section-title">Change Password</h3>
                <form action="../../controller/LecturerController.php?action=myProfile" method="POST">
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password:</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1>RelaxU</h1>
                <p>Your mental health, your priority.</p>
                <img id="footer-logo" src="../../assets/images/logo.jpg" alt="RelaxU Logo" />
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="lecturer_home.php">Dashboard</a></li>
                    <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions">Academic Questions</a></li>
                    <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Replied Questions</a></li>
                    <li><a href="scheduler.php">Class Schedule</a></li>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabs = document.querySelectorAll('.nav-tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });
                    
                    // Show the corresponding tab content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId + '-tab').classList.add('active');
                });
            });
            
            // Edit profile toggling
            const editProfileBtn = document.getElementById('edit-profile-btn');
            const cancelEditBtn = document.getElementById('cancel-edit-btn');
            const viewMode = document.getElementById('profile-view-mode');
            const editMode = document.getElementById('profile-edit-mode');
            
            editProfileBtn.addEventListener('click', function() {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
            });
            
            cancelEditBtn.addEventListener('click', function() {
                editMode.style.display = 'none';
                viewMode.style.display = 'block';
            });
            
            // Password matching validation
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            
            confirmPasswordInput.addEventListener('input', function() {
                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity("Passwords don't match");
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            });
            
            newPasswordInput.addEventListener('input', function() {
                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity("Passwords don't match");
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            });
        });
    </script>
</body>
</html>