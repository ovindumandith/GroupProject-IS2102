<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../models/ViewRelaxationActivityModel.php';
require_once '../controller/ViewRelaxationActivityController.php';

// Initialize model and controller
$model      = new ViewRelaxationActivityModel();
$controller = new ViewRelaxationActivityController($model);

// Handle request and get data
$data               = $controller->handleRequest();
$lowStressActivities = $data['lowStressActivities'] ?? [];
$role                = $data['role'] ?? 'user';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/relaxation_activities.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
                <?php if ($role === 'admin' || $role === 'superadmin'): ?>
                    <li><a href="../controller/AdminDashboardController.php?action=loadDashboard">Home</a></li>
                <?php elseif ($role === 'student'): ?>
                    <li><a href="./home.php">Home</a></li>
                <?php endif; ?>
                <li class="services">
                    <a href="#">Services</a>
                    <ul class="dropdown">
                        <li><a href="../stress_management/stress_management_index.php">Stress Monitoring</a></li>
                        <?php if ($role === 'admin' || $role === 'superadmin'): ?>
                            <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
                        <?php elseif ($role === 'student'): ?>
                            <li><a href="./relaxation_activities_suggester.php">Relaxation Activities</a></li>
                        <?php endif; ?>
                        <li><a href="#">Workload Management Tools</a></li>
                    </ul>
                </li>
                <li><a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions">Academic Help</a></li>
                <li><a href="../controller/AppointmentController.php?action=viewAppointments">Counseling</a></li>

                <li><a href="#">About Us</a></li>
            </ul>
        </nav>
        <!-- <div class="auth-buttons">
            <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
            <form action="../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="login"><b>Log Out</b></button>
            </form>
        </div> -->
        <div class="auth-buttons">
            <!-- <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button> -->
            <?php if ($role === 'admin' || $role === 'superadmin'): ?>
                <button class="signup" onclick="location.href='../../App/views/admin/admin_profile.php'"><b>Profile</b></button>  
                        <?php elseif ($role === 'student'): ?>
                            <button class="signup" onclick="location.href='../../App/controller/UserProfileController.php?action=showProfile'"><b>Profile</b></button>
                        <?php endif; ?>
            <form action="../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="login"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <!-- Display Messages -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert success">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <button class="close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert error">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button class="close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Content Section -->
    <div class="content">
        <?php if (!empty($lowStressActivities)): ?>
            <?php foreach ($lowStressActivities as $row): 
                $activityId   = htmlspecialchars($row['id']);
                $name         = htmlspecialchars($row['activity_name']);
                $description  = htmlspecialchars($row['description']);
                $file_name    = htmlspecialchars($row['image_url']);
                $playlist_url = htmlspecialchars($row['playlist_url']);
            ?>
                <div class="card">
                    <div class="image-content">
                        <span class="overlay">
                            <?php if ($role === 'admin' || $role === 'superadmin'): ?>
                                <form action="../views/update_relaxation_activities.php" method="GET">
                                    <input type="hidden" name="id"            value="<?= $activityId ?>">
                                    <input type="hidden" name="activity_name" value="<?= $name ?>">
                                    <input type="hidden" name="description"   value="<?= $description ?>">
                                    <input type="hidden" name="image_url"     value="<?= $file_name ?>">
                                    <input type="hidden" name="playlist_url"  value="<?= $playlist_url ?>">
                                    <input type="hidden" name="stress_level" value="<?= htmlspecialchars($row['stress_level']) ?>">
                                    <button type="submit" class="delete-update-button"><i class="fas fa-edit"></i></button>
                                </form>

                                <form method="POST" class="delete-form">
                                    <input type="hidden" name="delete_id"     value="<?= $activityId ?>">
                                    <input type="hidden" name="redirect_page" value="low_level_relaxation_activities.php">
                                    <button type="submit" class="delete-update-button"><i class="fas fa-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </span>
                        <div class="card-image">
                            <img src="./uploads/<?= $file_name ?>" alt="<?= $name ?>" class="card-img">
                        </div>
                    </div>
                    <div class="card-content">
                        <h2 class="activity"><?= $name ?></h2>
                        <p class="description"><?= $description ?></p>
                        <button class="button">
                            <a href="<?= $playlist_url ?>" target="_blank" rel="noopener noreferrer">View More</a>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-activities-container">
                <div class="no-activities">
                    <i class="fas fa-frown"></i>
                    <p>No relaxation activities found.</p>
                </div>
            </div>
        <?php endif; ?>
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

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="modal-content">
            <p class="modal-message">Are you sure you want to delete this activity?</p>
            <div class="modal-buttons">
                <button type="button" class="modal-button cancel-btn" id="cancelDelete">Cancel</button>
                <button type="button" class="modal-button confirm-btn" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/delete_confirm.js"></script>
</body>
</html>