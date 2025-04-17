<?php

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Get assessment data
$assessments = $_SESSION['all_assessments'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Stress Monitoring</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../assets/css/admin_dashboard.css" />
    <style>
        .assessment-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .assessment-title {
            color: #3f72af;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }
        
        .assessment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .assessment-table th,
        .assessment-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        
        .assessment-table th {
            background-color: #3f72af;
            color: white;
            font-weight: 600;
        }
        
        .assessment-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .stress-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            text-align: center;
        }
        
        .high {
            background-color: #ffebee;
            color: #b71c1c;
        }
        
        .moderate {
            background-color: #fff3e0;
            color: #e65100;
        }
        
        .low {
            background-color: #e8f5e9;
            color: #1b5e20;
        }
        
        .action-button {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            text-align: center;
            color: white;
            background-color: #3f72af;
        }
        
        .action-button:hover {
            background-color: #2c5282;
        }
        
        .search-filter {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .search-box {
            flex-grow: 1;
            max-width: 400px;
            margin-right: 20px;
        }
        
        .search-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .filter-options {
            display: flex;
            gap: 10px;
        }
        
        .filter-options select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
        }
        
        @media (max-width: 768px) {
            .search-filter {
                flex-direction: column;
            }
            
            .search-box {
                max-width: none;
                margin-right: 0;
                margin-bottom: 10px;
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
                <li><a href="./admin_home.php">Home</a></li>
                <li class="services">
                    <a href="#">Services </a>
                    <ul class="dropdown">
                        <li><a href="../controller/StressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
                        <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
                        <li><a href="./workload.php">Workload Management Tools</a></li>
                    </ul>
                </li>
                <li><a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions">Academic Help</a></li>
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

    <main>
        <div class="assessment-container">
            <h1 class="assessment-title">Student Stress Assessment Records</h1>
            
            <div class="search-filter">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by student name...">
                </div>
                <div class="filter-options">
                    <select id="stressLevelFilter">
                        <option value="">All Stress Levels</option>
                        <option value="High">High</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Low">Low</option>
                    </select>
                    <select id="dateFilter">
                        <option value="">All Dates</option>
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>
                </div>
            </div>
            
            <?php if (!empty($assessments)): ?>
                <table class="assessment-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Section 1 Score</th>
                            <th>Section 2 Score</th>
                            <th>Stress Level</th>
                            <th>Assessment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assessments as $assessment): ?>
                            <tr>
                                <td><?= htmlspecialchars($assessment['assessment_id']) ?></td>
                                <td><?= htmlspecialchars($assessment['student_name']) ?></td>
                                <td><?= htmlspecialchars($assessment['section1_score']) ?>/20</td>
                                <td><?= htmlspecialchars($assessment['section2_score']) ?>/20</td>
                                <td>
                                    <span class="stress-badge <?= strtolower($assessment['stress_level']) ?>">
                                        <?= htmlspecialchars($assessment['stress_level']) ?>
                                    </span>
                                </td>
                                <td><?= date("F j, Y, g:i a", strtotime($assessment['assessment_date'])) ?></td>
                                <td>
                                    <a href="../controller/AdminStressAssessmentController.php?action=viewAssessmentDetails&id=<?= $assessment['assessment_id'] ?>" class="action-button">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center;">No assessment records found.</p>
            <?php endif; ?>
        </div>
    </main>

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
                    <li><a href="../controller/StressAssessmentController.php?action=viewAllAssessments">Stress Monitoring</a></li>
                    <li><a href="./admin_activities_portal.php">Relaxation Activities</a></li>
                    <li><a href="../views/Academic_Help.php">Academic Help</a></li>
                    <li><a href="#">Counseling</a></li>
                    <li><a href="#">Community</a></li>
                    <li><a href="./workload.php">Workload Management Tools</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p><i class="fa fa-phone"></i> +14 5464 8272</p>
                <p><i class="fa fa-envelope"></i> admin@relaxu.com</p>
                <p><i class="fa fa-map-marker"></i> University Admin Building</p>
            </div>
            <div class="footer-section">
                <h3>Links</h3>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms Of Use</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>copyright 2024 @RelaxU all rights reserved</p>
        </div>
    </footer>

    <script>
        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const stressLevelFilter = document.getElementById('stressLevelFilter');
            const dateFilter = document.getElementById('dateFilter');
            const tableRows = document.querySelectorAll('.assessment-table tbody tr');
            
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const stressLevel = stressLevelFilter.value;
                const dateDays = dateFilter.value ? parseInt(dateFilter.value) : 0;
                
                const today = new Date();
                const cutoffDate = new Date();
                cutoffDate.setDate(today.getDate() - dateDays);
                
                tableRows.forEach(row => {
                    const studentName = row.cells[1].textContent.toLowerCase();
                    const rowStressLevel = row.cells[4].textContent.trim();
                    const assessmentDate = new Date(row.cells[5].textContent);
                    
                    const matchesSearch = studentName.includes(searchTerm);
                    const matchesStressLevel = !stressLevel || rowStressLevel.includes(stressLevel);
                    const matchesDate = !dateDays || assessmentDate >= cutoffDate;
                    
                    if (matchesSearch && matchesStressLevel && matchesDate) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            searchInput.addEventListener('input', filterTable);
            stressLevelFilter.addEventListener('change', filterTable);
            dateFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>