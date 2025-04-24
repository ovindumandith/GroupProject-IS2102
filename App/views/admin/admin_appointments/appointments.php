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
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Main content styles */
        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
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
        
        /* Stats container */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.active {
            background-color: #e0f5f0;
            border-left: 4px solid #009f77;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #555;
        }
        
        .stat-pending .stat-number { color: #f39c12; }
        .stat-accepted .stat-number { color: #009f77; }
        .stat-denied .stat-number { color: #e74c3c; }
        .stat-total .stat-number { color: #3498db; }
        
        /* Filter and search */
        .filter-search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-container {
            display: flex;
            gap: 10px;
        }
        
        .filter-link {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .filter-link.all {
            background-color: #f1f1f1;
            color: #333;
        }
        
        .filter-link.pending {
            background-color: #fff8e0;
            color: #f39c12;
        }
        
        .filter-link.accepted {
            background-color: #e0f5f0;
            color: #009f77;
        }
        
        .filter-link.denied {
            background-color: #ffe0e0;
            color: #e74c3c;
        }
        
        .filter-link:hover, .filter-link.active {
            opacity: 0.8;
        }
        
        .search-box {
            position: relative;
            flex: 1;
            max-width: 300px;
        }
        
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        
        /* Appointments table */
        .appointments-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .appointments-table th {
            padding: 15px 10px;
            text-align: left;
            border-bottom: 2px solid #eee;
            font-weight: 600;
            color: #333;
        }
        
        .appointments-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .appointments-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .appointments-table tbody tr:hover {
            background-color: #f9f9f9;
        }
        
        .counselor-type {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            background-color: #f1f1f1;
        }
        
        .counselor-type.professional {
            background-color: #e0f5f0;
            color: #009f77;
        }
        
        .counselor-type.student {
            background-color: #e0f0ff;
            color: #3498db;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fff8e0;
            color: #f39c12;
        }
        
        .status-accepted {
            background-color: #e0f5f0;
            color: #009f77;
        }
        
        .status-denied {
            background-color: #ffe0e0;
            color: #e74c3c;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .view-btn {
            background-color: #3498db;
            color: white;
        }
        
        .accept-btn {
            background-color: #009f77;
            color: white;
        }
        
        .deny-btn {
            background-color: #e74c3c;
            color: white;
        }
        
        .action-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        /* No appointments */
        .no-appointments {
            text-align: center;
            padding: 30px;
            color: #666;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .filter-search-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                max-width: none;
            }
            
            .appointments-table {
                font-size: 0.9rem;
            }
            
            .appointments-table th, .appointments-table td {
                padding: 10px 8px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                margin-bottom: 5px;
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
                <li><a href="admin_home.php">Dashboard</a></li>
                <li><a href="../../controller/AppointmentController.php?action=viewAppointments" class="active">Counseling</a></li>
                <li><a href="#">Users</a></li>
                <li><a href="#">Reports</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="#" class="profile-btn"><b>Profile</b></a>
            <form action="../../../util/logout.php" method="post" style="display: inline">
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
            <a href="../../controller/AppointmentController.php?action=viewAppointments" class="stat-card stat-total <?= !$selectedStatus ? 'active' : '' ?>">
                <div class="stat-number"><?= $counts['total_count'] ?></div>
                <div class="stat-label">All Appointments</div>
            </a>
            <a href="../../controller/AppointmentController.php?action=viewAppointments&status=Pending" class="stat-card stat-pending <?= $selectedStatus === 'Pending' ? 'active' : '' ?>">
                <div class="stat-number"><?= $counts['pending_count'] ?></div>
                <div class="stat-label">Pending</div>
            </a>
            <a href="../../controller/AppointmentController.php?action=viewAppointments&status=Accepted" class="stat-card stat-accepted <?= $selectedStatus === 'Accepted' ? 'active' : '' ?>">
                <div class="stat-number"><?= $counts['accepted_count'] ?></div>
                <div class="stat-label">Accepted</div>
            </a>
            <a href="../../controller/AppointmentController.php?action=viewAppointments&status=Denied" class="stat-card stat-denied <?= $selectedStatus === 'Denied' ? 'active' : '' ?>">
                <div class="stat-number"><?= $counts['denied_count'] ?></div>
                <div class="stat-label">Denied</div>
            </a>
        </div>
        
        <div class="filter-search-container">
            <div class="filter-container">
                <a href="../../controller/AppointmentController.php?action=viewAppointments" class="filter-link all <?= !$selectedStatus ? 'active' : '' ?>">All</a>
                <a href="../../controller/AppointmentController.php?action=viewAppointments&status=Pending" class="filter-link pending <?= $selectedStatus === 'Pending' ? 'active' : '' ?>">Pending</a>
                <a href="../../controller/AppointmentController.php?action=viewAppointments&status=Accepted" class="filter-link accepted <?= $selectedStatus === 'Accepted' ? 'active' : '' ?>">Accepted</a>
                <a href="../../controller/AppointmentController.php?action=viewAppointments&status=Denied" class="filter-link denied <?= $selectedStatus === 'Denied' ? 'active' : '' ?>">Denied</a>
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
                                        <a href="../../controller/AppointmentController.php?action=viewAppointmentDetails&id=<?= $appointment['id'] ?>" class="action-btn view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        <?php if ($appointment['status'] === 'Pending'): ?>
                                            <form action="../../controller/AppointmentController.php?action=updateStatus" method="POST" style="display: inline;">
                                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                <input type="hidden" name="status" value="Accepted">
                                                <button type="submit" class="action-btn accept-btn">
                                                    <i class="fas fa-check"></i> Accept
                                                </button>
                                            </form>
                                            
                                            <form action="../../controller/AppointmentController.php?action=updateStatus" method="POST" style="display: inline;">
                                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                <input type="hidden" name="status" value="Denied">
                                                <button type="submit" class="action-btn deny-btn">
                                                    <i class="fas fa-times"></i> Deny
                                                </button>
                                            </form>
                                        <?php endif; ?>
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