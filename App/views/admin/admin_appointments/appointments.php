<?php
// File: views/admin/appointments.php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php?error=unauthorized');
    exit();
}

// Get appointments data from session
$appointments = $_SESSION['appointments'] ?? [];
$counts = $_SESSION['appointment_counts'] ?? [
    'pending_count' => 0,
    'accepted_count' => 0,
    'denied_count' => 0,
    'total_count' => 0
];
$selectedStatus = $_SESSION['selected_status'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counseling Appointments | RelaxU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../../../assets/css/admin_home.css" />
    <link rel="stylesheet" href="../../../../assets//css/admin_appointments.css" />
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="logo">
            <img src="../../../../assets/images/logo.jpg" alt="RelaxU Logo" />
            <h1>RelaxU</h1>
        </div>
      <nav class="navbar">
        <ul>
          <li><a href="./admin_home.php">Home</a></li>
          <li class="services">
            <a href="#">Services </a>
            <ul class="dropdown">
              <li><a href="../../../../App/controller/AdminStressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
              <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
              <li><a href="./workload.php">Workload Management Tools</a></li>
            </ul>
          </li>
          <li><a href="../../../../App/controller/Academic_QuestionsController.php?action=viewAllQuestions">Academic Help</a></li>
          <li><a href="../../../../App/controller/AppointmentController.php?action=viewAppointments">Counseling</a></li>
          <li><a href="#">About Us</a></li>
        </ul>
      </nav>
        <div class="auth-buttons">
            <form action="../../../../App/controller/AdminProfileController.php" method="GET">
  <input type="hidden" name="action" value="viewProfile">
  <button type="submit" class="login"><b>Profile</b></button>
</form>
            <form action="../../../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="logout-btn"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
        <h2>Counseling Appointments</h2>
        
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
        
        <div class="stats-container">
            <a href="../../../../App/controller/AppointmentController.php?action=viewAppointments" class="stat-card stat-total <?= !$selectedStatus ? 'active' : '' ?>">
                <div class="stat-number"><?= $counts['total_count'] ?></div>
                <div class="stat-label">All Appointments</div>
            </a>
            <a href="../../../../App/controller/AppointmentController.php?action=viewAppointments&status=Pending" class="stat-card stat-pending <?= $selectedStatus === 'Pending' ? 'active' : '' ?>">
                <div class="stat-number"><?= $counts['pending_count'] ?></div>
                <div class="stat-label">Pending</div>
            </a>
            <a href="../../../../App/controller/AppointmentController.php?action=viewAppointments&status=Accepted" class="stat-card stat-accepted <?= $selectedStatus === 'Accepted' ? 'active' : '' ?>">
                <div class="stat-number"><?= $counts['accepted_count'] ?></div>
                <div class="stat-label">Accepted</div>
            </a>
            <a href="../../../../App/controller/AppointmentController.php?action=viewAppointments&status=Denied" class="stat-card stat-denied <?= $selectedStatus === 'Denied' ? 'active' : '' ?>">
                <div class="stat-number"><?= $counts['denied_count'] ?></div>
                <div class="stat-label">Denied</div>
            </a>
        </div>
        
        <div class="filter-search-container">
            <div class="filter-container">
                <a href="../../../../App/controller/AppointmentController.php?action=viewAppointments" class="filter-link all <?= !$selectedStatus ? 'active' : '' ?>">All</a>
                <a href="../../../../App/controller/AppointmentController.php?action=viewAppointments&status=Pending" class="filter-link pending <?= $selectedStatus === 'Pending' ? 'active' : '' ?>">Pending</a>
                <a href="../../../../App/controller/AppointmentController.php?action=viewAppointments&status=Accepted" class="filter-link accepted <?= $selectedStatus === 'Accepted' ? 'active' : '' ?>">Accepted</a>
                <a href="../../../../App/controller/AppointmentController.php?action=viewAppointments&status=Denied" class="filter-link denied <?= $selectedStatus === 'Denied' ? 'active' : '' ?>">Denied</a>
            </div>
            
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Search appointments..." />
            </div>
        </div>
        
        <div class="appointments-container">
            <?php if (empty($appointments)): ?>
                <div class="no-appointments">
                    <p>No appointments found<?= $selectedStatus ? " with status '$selectedStatus'" : "" ?>.</p>
                </div>
            <?php else: ?>
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Counselor</th>
                            <th>Topic</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr data-search="<?= strtolower($appointment['student_name'] . ' ' . $appointment['counselor_name'] . ' ' . $appointment['topic']) ?>">
                                <td>#<?= $appointment['id'] ?></td>
                                <td><?= htmlspecialchars($appointment['student_name']) ?></td>
                                <td>
                                    <?= htmlspecialchars($appointment['counselor_name']) ?>
                                    <span class="counselor-type <?= strtolower($appointment['counselor_type']) ?>">
                                        <?= $appointment['counselor_type'] ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($appointment['topic']) ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($appointment['appointment_date'])) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($appointment['status']) ?>">
                                        <?= $appointment['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/GroupProject-IS2102/App/controller/AppointmentController.php?action=viewAppointmentDetails&id=<?= $appointment['id'] ?>" class="action-btn view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1>RelaxU</h1>
                <p>Your mental health, your priority.</p>
                <img id="footer-logo" src="../../../../assets/images/logo.jpg" alt="RelaxU Logo" />
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
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('.appointments-table tbody tr');
            
            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const searchData = row.getAttribute('data-search').toLowerCase();
                    
                    if (searchData.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show no results message if all rows are hidden
                const visibleRows = document.querySelectorAll('.appointments-table tbody tr:not([style*="display: none"])');
                const noAppointments = document.querySelector('.no-appointments');
                
                if (visibleRows.length === 0 && !noAppointments) {
                    const noResults = document.createElement('div');
                    noResults.className = 'no-appointments';
                    noResults.innerHTML = '<p>No appointments found matching your search.</p>';
                    document.querySelector('.appointments-container').appendChild(noResults);
                } else if (visibleRows.length > 0 && document.querySelector('.no-appointments')) {
                    document.querySelector('.no-appointments').remove();
                }
            });
        });
    </script>
</body>
</html>