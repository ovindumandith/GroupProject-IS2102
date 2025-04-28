<?php
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}

// Include the Admin Dashboard Model to get real data
require_once '../models/AdminDashboardModel.php';
$dashboardModel = new AdminDashboardModel();

// Get dashboard metrics
$dashboardData = $dashboardModel->getAllDashboardMetrics();

// Prepare data for charts and counters
$totalUsers = $dashboardData['total_users'];
$userRoles = $dashboardData['user_role_distribution'];
$totalStudents = $userRoles['student'];
$totalCounselors = $userRoles['counselor'];
$totalLecturers = $userRoles['lecturer'];
$totalAppointments = $dashboardData['total_appointments'];
$appointmentStatusData = [
    'Pending' => $dashboardData['pending_appointments'],
    'Accepted' => $dashboardData['accepted_appointments'],
    'Denied' => $dashboardData['denied_appointments']
];
$stressLevels = $dashboardData['stress_level_distribution'];
$totalQuestions = $dashboardData['total_academic_questions'];
$pendingQuestions = $dashboardData['pending_academic_questions'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Admin Dashboard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Base Styles -->
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../assets/css/home.css" />
    <!-- Dashboard Styles -->
    <link rel="stylesheet" href="../../assets/css/admin_home.css" />
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
                <li><a href="../views/admin_home.php" class="active">Home</a></li>
                <li class="services">
                    <a href="#">Services </a>
                    <ul class="dropdown">
                        <li><a href="../controller/AdminStressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
                        <li><a href="../views/admin_activities_portal.php">Relaxation Activities</a></li>
                        <li><a href="../workload.php">Workload Management Tools</a></li>
                    </ul>
                </li>
                <li><a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions">Academic Help</a></li>
                <li><a href="../controller/AppointmentController.php?action=viewAppointments">Counseling</a></li>
                <li><a href="#">About Us</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <form action="../../App/controller/AdminProfileController.php" method="GET">
                <input type="hidden" name="action" value="viewProfile">
                <button type="submit" class="login"><b>Profile</b></button>
            </form>
            <form action="../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="login"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Admin Dashboard</h1>
                <p class="dashboard-description">Overview of system activities and user metrics</p>
            </div>
            <div class="refresh-wrapper">
                <div class="last-updated">
                    <i class="fas fa-sync-alt"></i> Last updated: <?php echo date('M d, Y - h:i A'); ?>
                </div>
                <button id="refresh-btn" class="refresh-btn">
                    <i class="fas fa-redo"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Summary Stats Row -->
        <div class="summary-stats">
            <div class="stat-card primary">
                <div class="stat-text">
                    <h3>Total Users</h3>
                    <div class="value"><?php echo htmlspecialchars($totalUsers); ?></div>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            
            <div class="stat-card secondary">
                <div class="stat-text">
                    <h3>Students</h3>
                    <div class="value"><?php echo htmlspecialchars($totalStudents); ?></div>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            
            <div class="stat-card blue">
                <div class="stat-text">
                    <h3>Appointments</h3>
                    <div class="value"><?php echo htmlspecialchars($totalAppointments); ?></div>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            
            <div class="stat-card yellow">
                <div class="stat-text">
                    <h3>Questions</h3>
                    <div class="value"><?php echo htmlspecialchars($totalQuestions); ?></div>
                </div>
                <div class="icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-grid">
                <a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions" class="action-btn">
                    <i class="fas fa-question-circle"></i>
                    <span>Academic Questions</span>
                </a>
                <a href="../controller/AppointmentController.php?action=viewAppointments" class="action-btn">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
                <a href="../controller/AdminStressAssessmentController.php?action=viewAllAssessments" class="action-btn">
                    <i class="fas fa-heart-pulse"></i>
                    <span>Stress Assessments</span>
                </a>
                <a href="./admin_activities_portal.php" class="action-btn">
                    <i class="fas fa-spa"></i>
                    <span>Relaxation Activities</span>
                </a>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Left Column - Charts -->
            <div class="left-column">
                <!-- Stress Level Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h2><i class="fas fa-heart-pulse"></i> Stress Level Distribution</h2>
                    </div>
                    <div class="chart-body">
                        <canvas id="stressLevelChart"></canvas>
                    </div>
                </div>
                
                <!-- User Distribution Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h2><i class="fas fa-users"></i> User Distribution</h2>
                    </div>
                    <div class="chart-body">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="right-column">
                <!-- Appointment Status -->
                <div class="detail-card">
                    <div class="detail-header">
                        <h2><i class="fas fa-calendar-check"></i> Appointment Status</h2>
                    </div>
                    <div class="detail-body">
                        <div class="appointment-status">
                            <div class="status-item status-pending">
                                <div class="status-value"><?php echo htmlspecialchars($appointmentStatusData['Pending']); ?></div>
                                <div class="status-label">Pending</div>
                            </div>
                            <div class="status-item status-accepted">
                                <div class="status-value"><?php echo htmlspecialchars($appointmentStatusData['Accepted']); ?></div>
                                <div class="status-label">Accepted</div>
                            </div>
                            <div class="status-item status-denied">
                                <div class="status-value"><?php echo htmlspecialchars($appointmentStatusData['Denied']); ?></div>
                                <div class="status-label">Denied</div>
                            </div>
                        </div>
                        <div style="height: 240px; padding-top: 20px;">
                            <canvas id="appointmentChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Stress Distribution -->
                <div class="detail-card">
                    <div class="detail-header">
                        <h2><i class="fas fa-heart-pulse"></i> Stress Levels</h2>
                    </div>
                    <div class="detail-body">
                        <ul class="stress-list">
                            <li class="stress-item">
                                <div class="stress-level">
                                    <div class="stress-indicator stress-low"></div>
                                    <span>Low Stress</span>
                                </div>
                                <div class="stress-value"><?php echo htmlspecialchars($stressLevels['low']); ?> students</div>
                            </li>
                            <li class="stress-item">
                                <div class="stress-level">
                                    <div class="stress-indicator stress-moderate"></div>
                                    <span>Moderate Stress</span>
                                </div>
                                <div class="stress-value"><?php echo htmlspecialchars($stressLevels['moderate']); ?> students</div>
                            </li>
                            <li class="stress-item">
                                <div class="stress-level">
                                    <div class="stress-indicator stress-high"></div>
                                    <span>High Stress</span>
                                </div>
                                <div class="stress-value"><?php echo htmlspecialchars($stressLevels['high']); ?> students</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="detail-card">
                    <div class="detail-header">
                        <h2><i class="fas fa-clock"></i> Recent Activity</h2>
                    </div>
                    <div class="detail-body">
                        <ul class="activity-list">
                            <li class="activity-item">
                                <div class="activity-icon questions">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div class="activity-details">
                                    <h4><?php echo htmlspecialchars($dashboardData['recent_academic_questions']); ?> new academic questions</h4>
                                    <p>Students asking for academic help</p>
                                    <div class="activity-time">
                                        <i class="fas fa-clock"></i> Past 7 days
                                    </div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon appointments">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="activity-details">
                                    <h4><?php echo htmlspecialchars($dashboardData['recent_appointments']); ?> new appointments</h4>
                                    <p>Counseling sessions scheduled</p>
                                    <div class="activity-time">
                                        <i class="fas fa-clock"></i> Past 7 days
                                    </div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon stress">
                                    <i class="fas fa-heart-pulse"></i>
                                </div>
                                <div class="activity-details">
                                    <h4><?php echo htmlspecialchars($dashboardData['recent_stress_assessments']); ?> new stress assessments</h4>
                                    <p>Students monitoring their stress levels</p>
                                    <div class="activity-time">
                                        <i class="fas fa-clock"></i> Past 7 days
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Role Distribution Card -->
        <div class="detail-card">
            <div class="detail-header">
                <h2><i class="fas fa-users"></i> User Role Distribution</h2>
            </div>
            <div class="detail-body">
                <div class="user-distribution">
                    <?php
                    $userRoles = $dashboardData['user_role_distribution'];
                    $totalUsers = array_sum($userRoles);
                    
                    // Define role colors and icons
                    $roleInfo = [
                        'student' => ['color' => '#2ecc71', 'icon' => 'fa-user-graduate'],
                        'admin' => ['color' => '#e74c3c', 'icon' => 'fa-user-shield'],
                        'lecturer' => ['color' => '#3498db', 'icon' => 'fa-chalkboard-teacher'],
                        'hous' => ['color' => '#9b59b6', 'icon' => 'fa-user-tie'],
                        'counselor' => ['color' => '#e67e22', 'icon' => 'fa-user-md'],
                        'super_admin' => ['color' => '#2c3e50', 'icon' => 'fa-user-cog']
                    ];
                    
                    foreach ($userRoles as $role => $count) {
                        $percentage = $totalUsers > 0 ? round(($count / $totalUsers) * 100) : 0;
                        $color = isset($roleInfo[$role]) ? $roleInfo[$role]['color'] : '#888';
                        $icon = isset($roleInfo[$role]) ? $roleInfo[$role]['icon'] : 'fa-user';
                        $roleName = ucfirst(str_replace('_', ' ', $role));
                    ?>
                    <div class="role-stat">
                        <div class="role-info">
                            <div class="role-icon" style="background-color: <?php echo $color; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            <div class="role-details">
                                <h4><?php echo htmlspecialchars($roleName); ?></h4>
                                <span class="role-count"><?php echo htmlspecialchars($count); ?> users</span>
                            </div>
                        </div>
                        <div class="progress-wrapper">
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: <?php echo $percentage; ?>%; background-color: <?php echo $color; ?>"></div>
                            </div>
                            <span class="percentage"><?php echo $percentage; ?>%</span>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
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
                    <li><a href="./admin_home.php">Home</a></li>
                    <li><a href="../controller/AdminStressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
                    <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
                    <li><a href="./workload.php">Workload Management Tools</a></li>
                    <li><a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions">Academic Help</a></li>
                    <li><a href="../controller/AppointmentController.php?action=viewAppointments">Counseling</a></li>
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

    <!-- Chart.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <!-- Pass PHP data to JavaScript -->
    <script>
        // Chart data for user roles
        const userChartData = {
            labels: <?php echo json_encode(array_map(function($role) { 
                return ucfirst(str_replace('_', ' ', $role)); 
            }, array_keys($userRoles))); ?>,
            values: <?php echo json_encode(array_values($userRoles)); ?>
        };
        
        // Chart data for stress levels
        const stressChartData = [
            <?php echo $stressLevels['low']; ?>,
            <?php echo $stressLevels['moderate']; ?>,
            <?php echo $stressLevels['high']; ?>
        ];
        
        // Chart data for appointments
        const appointmentChartData = [
            <?php echo $appointmentStatusData['Pending']; ?>,
            <?php echo $appointmentStatusData['Accepted']; ?>,
            <?php echo $appointmentStatusData['Denied']; ?>
        ];
    </script>
    
    <!-- Dashboard JavaScript -->
    <script src="../../assets/js/admin_home.js"></script>
</body>
</html>