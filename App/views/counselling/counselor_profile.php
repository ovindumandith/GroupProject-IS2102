<?php
// App/views/counselling/counselor_profile.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if counselor is logged in
if (!isset($_SESSION['counselor'])) {
    header('Location: ../../views/counselor_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Profile | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/counselor_dashboard.css" type="text/css" />
    <style>
        :root {
            --primary-color: #009f77;
            --primary-light: #e0f7f1;
            --primary-dark: #007a5a;
            --accent-color: #ff6b6b;
            --text-color: #333;
            --text-light: #666;
            --background-light: #f5f5f5;
            --border-color: #ddd;
            --success-color: #4CAF50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-light);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .profile-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .profile-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .profile-content {
            padding: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .profile-sidebar {
            flex: 1;
            min-width: 250px;
            text-align: center;
        }

        .profile-main {
            flex: 2;
            min-width: 300px;
        }

        .profile-image-container {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 5px solid var(--primary-light);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .profile-tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            font-weight: 500;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--text-color);
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 159, 119, 0.2);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border-left: 3px solid var(--success-color);
        }

        .alert-danger {
            background-color: #ffebee;
            color: var(--danger-color);
            border-left: 3px solid var(--danger-color);
        }

        .stats-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 20px;
        }

        .stats-info {
            flex: 1;
        }

        .stats-number {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .stats-title {
            color: var(--text-light);
            margin: 0;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 10px 15px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .custom-file-upload:hover {
            background-color: var(--primary-dark);
        }

        #profile_image {
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-content {
                flex-direction: column;
            }
            
            .profile-tabs {
                flex-wrap: wrap;
            }
            
            .profile-tab {
                flex: 1;
                min-width: 100px;
                text-align: center;
            }
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
                <li><a href="../views/counselor_dashboard.php">Dashboard</a></li>
                <li class="services">
                    <a href="#">Appointments</a>
                    <ul class="dropdown">
                        <li><a href="../../controller/AppointmentController.php?action=showPendingAppointments">Pending</a></li>
                        <li><a href="../../controller/AppointmentController.php?action=showApprovedAppointments">Approved</a></li>
                        <li><a href="../../controller/AppointmentController.php?action=showDeniedAppointments">Denied</a></li>
                    </ul>
                </li>
                <li><a href="../views/messages.php">Messages</a></li>
                <li><a href="../views/reviews.php">Reviews</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <!-- Profile button form -->
            <form action="../../controller/CounselorController.php?action=viewLoggedInCounselorProfile" method="GET">
                <button type="submit" class="login"><b>Profile</b></button>
            </form>
            
            <!-- Logout button form -->
            <form action="../../util/counselor_logout.php" method="POST" style="display: inline;">
                <button type="submit" class="login"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <h1>Counselor Profile</h1>
            </div>
            
            <div class="profile-content">
                <div class="profile-sidebar">
                    <div class="profile-image-container">
                        <img src="<?= !empty($counselor['profile_image']) ? '../../' . $counselor['profile_image'] : '../../assets/images/default-profile.jpg' ?>" alt="Profile Image" class="profile-image">
                    </div>
                    <h2><?= htmlspecialchars($counselor['name']) ?></h2>
                    <p><?= htmlspecialchars($counselor['type']) ?> - <?= htmlspecialchars($counselor['specialization']) ?></p>
                    
                    <form action="../controller/CounselorController.php?action=updateCounselorProfile" method="POST" enctype="multipart/form-data">
                        <label for="profile_image" class="custom-file-upload">
                            <i class="fas fa-camera"></i> Change Profile Picture
                        </label>
                        <input type="file" id="profile_image" name="profile_image" onchange="this.form.submit()">
                    </form>
                </div>
                
                <div class="profile-main">
                    <!-- Success/Error Messages -->
                    <?php if (isset($_SESSION['update_success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['update_success']) ?>
                        </div>
                        <?php unset($_SESSION['update_success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['update_error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['update_error']) ?>
                        </div>
                        <?php unset($_SESSION['update_error']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['password_success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['password_success']) ?>
                        </div>
                        <?php unset($_SESSION['password_success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['password_error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['password_error']) ?>
                        </div>
                        <?php unset($_SESSION['password_error']); ?>
                    <?php endif; ?>
                    
                    <div class="profile-tabs">
                        <div class="profile-tab active" onclick="openTab('profile-info')">Profile Information</div>
                        <div class="profile-tab" onclick="openTab('change-password')">Change Password</div>
                        <div class="profile-tab" onclick="openTab('stats')">Statistics</div>
                    </div>
                    
                    <!-- Profile Information Tab -->
                    <div id="profile-info" class="tab-content active">
                        <form action="../controller/CounselorController.php?action=updateCounselorProfile" method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($counselor['name']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($counselor['username']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($counselor['email']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="type">Counselor Type</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="Academic" <?= $counselor['type'] === 'Academic' ? 'selected' : '' ?>>Academic</option>
                                    <option value="Career" <?= $counselor['type'] === 'Career' ? 'selected' : '' ?>>Career</option>
                                    <option value="Mental Health" <?= $counselor['type'] === 'Mental Health' ? 'selected' : '' ?>>Mental Health</option>
                                    <option value="Personal" <?= $counselor['type'] === 'Personal' ? 'selected' : '' ?>>Personal</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="specialization">Specialization</label>
                                <input type="text" id="specialization" name="specialization" class="form-control" value="<?= htmlspecialchars($counselor['specialization']) ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="description">About Me</label>
                                <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($counselor['description']) ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                    
                    <!-- Change Password Tab -->
                    <div id="change-password" class="tab-content">
                        <form action="../controller/CounselorController.php?action=changePassword" method="POST">
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                    
                    <!-- Statistics Tab -->
                    <div id="stats" class="tab-content">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stats-info">
                                <h3 class="stats-number">
                                    <?php
                                    // You can add PHP code here to fetch the actual count
                                    echo "25"; // Placeholder
                                    ?>
                                </h3>
                                <p class="stats-title">Total Appointments</p>
                            </div>
                        </div>
                        
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stats-info">
                                <h3 class="stats-number">
                                    <?php
                                    // You can add PHP code here to fetch the actual count
                                    echo "18"; // Placeholder
                                    ?>
                                </h3>
                                <p class="stats-title">Completed Sessions</p>
                            </div>
                        </div>
                        
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stats-info">
                                <h3 class="stats-number">
                                    <?php
                                    // You can add PHP code here to fetch the actual rating
                                    echo "4.7"; // Placeholder
                                    ?>
                                </h3>
                                <p class="stats-title">Average Rating</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <li><a href="../views/counselor_dashboard.php">Dashboard</a></li>
                    <li><a href="../views/manage_appointments.php">Appointments</a></li>
                    <li><a href="../views/messages.php">Messages</a></li>
                    <li><a href="../views/reviews.php">Reviews</a></li>
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
        // Tab switching functionality
        function openTab(tabId) {
            // Hide all tab contents
            const tabContents = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            // Remove active class from all tabs
            const tabs = document.getElementsByClassName('profile-tab');
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            // Show the selected tab content
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to the clicked tab
            const clickedTab = document.querySelector(`.profile-tab[onclick="openTab('${tabId}')"]`);
            clickedTab.classList.add('active');
        }
        
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.getElementsByClassName('alert');
            for (let i = 0; i < alerts.length; i++) {
                setTimeout(function() {
                    alerts[i].style.opacity = '0';
                    alerts[i].style.transition = 'opacity 0.5s ease';
                    setTimeout(function() {
                        alerts[i].style.display = 'none';
                    }, 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>