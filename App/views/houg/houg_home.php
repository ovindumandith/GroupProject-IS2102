<?php
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
    header('Location: ../login.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Head of Undergraduate Studies Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../../assets/css/houg_home.css" type="text/css" />
    <script src="../../../assets/js/search_academic_requests.js" defer></script>
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
          <li><a href="../../controller/HOUSDashboardController.php">Dashboard</a></li>
          <li><a href="../../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous">Academic Requests</a></li>
          <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Forwarded-Replied Questions</a></li>
           <li><a href="../../controller/LecturerController.php?action=list">List of Lecturers</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">

        <!-- Profile button form -->
        <form action="../../controller/HOUSProfileController.php" method="GET">
          <input type="hidden" name="action" value="viewProfile">
          <button type="submit" class="login"><b>Profile</b></button>
        </form>


    
        <!-- Logout button form -->
        <form action="../../../util/logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
    <!-- Replace the <main> section in views/houg/houg_home.php -->
<main>
    <div class="dashboard-container">
        <h2>Head of Undergraduate Studies Dashboard</h2>
        
        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Pending Requests</h3>
                    <p class="stat-number" id="pending-count"><?php echo $_SESSION['dashboard_data']['counts']['pending_count'] ?? 0; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-share"></i>
                </div>
                <div class="stat-content">
                    <h3>Forwarded Questions</h3>
                    <p class="stat-number" id="forwarded-count"><?php echo $_SESSION['dashboard_data']['counts']['forwarded_count'] ?? 0; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-reply"></i>
                </div>
                <div class="stat-content">
                    <h3>Answered Questions</h3>
                    <p class="stat-number" id="answered-count"><?php echo $_SESSION['dashboard_data']['counts']['answered_count'] ?? 0; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Lecturers</h3>
                    <p class="stat-number" id="lecturer-count"><?php echo $_SESSION['dashboard_data']['lecturer_count'] ?? 0; ?></p>
                </div>
            </div>
        </div>
        
        <!-- Recent Academic Requests -->
        <div class="dashboard-section">
            <div class="section-header">
                <h3>Recent Academic Requests</h3>
                <a href="../../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous" class="view-all">View All</a>
            </div>
            <div class="recent-requests" id="recent-requests">
                <?php if (empty($_SESSION['dashboard_data']['recent_questions'])): ?>
                    <div class="no-data">No recent requests found.</div>
                <?php else: ?>
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['dashboard_data']['recent_questions'] as $question): ?>
                                <tr class="status-<?php echo strtolower($question['status']); ?>">
                                    <td>#<?php echo $question['id']; ?></td>
                                    <td><?php echo htmlspecialchars($question['student_name']); ?></td>
                                    <td>
                                        <span class="category-badge category-<?php echo strtolower(str_replace(' ', '_', $question['category'])); ?>">
                                            <?php echo htmlspecialchars($question['category']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($question['created_at'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($question['status']); ?>">
                                            <?php echo $question['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="../../controller/Academic_QuestionsController.php?action=viewQuestion&id=<?php echo $question['id']; ?>" class="action-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Category Distribution -->
        <div class="dashboard-section">
            <div class="section-header">
                <h3>Requests by Category</h3>
            </div>
            <div class="category-chart">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="dashboard-section">
            <div class="section-header">
                <h3>Recent Activity</h3>
            </div>
            <div class="activity-timeline" id="activity-timeline">
                <?php if (empty($_SESSION['dashboard_data']['recent_activity'])): ?>
                    <div class="no-data">No recent activity found.</div>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($_SESSION['dashboard_data']['recent_activity'] as $activity): ?>
                            <div class="timeline-item timeline-<?php echo $activity['activity_type']; ?>">
                                <div class="timeline-icon">
                                    <i class="fas <?php echo $activity['activity_type'] === 'forward' ? 'fa-share' : 'fa-reply'; ?>"></i>
                                </div>
                                <div class="timeline-content">
                                    <?php if ($activity['activity_type'] === 'forward'): ?>
                                        <p><strong><?php echo htmlspecialchars($activity['actor_name']); ?></strong> forwarded a question to <strong><?php echo htmlspecialchars($activity['target_name']); ?></strong></p>
                                    <?php else: ?>
                                        <p><strong><?php echo htmlspecialchars($activity['actor_name']); ?></strong> replied to a question from <strong><?php echo htmlspecialchars($activity['target_name']); ?></strong></p>
                                    <?php endif; ?>
                                    <span class="timeline-time"><?php echo getTimeAgo($activity['activity_time']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Add the time ago function -->
    <?php
    function getTimeAgo($timestamp) {
        $time = strtotime($timestamp);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) {
            return "Just now";
        } elseif ($diff < 3600) {
            $mins = round($diff / 60);
            return $mins . " minute" . ($mins > 1 ? "s" : "") . " ago";
        } elseif ($diff < 86400) {
            $hours = round($diff / 3600);
            return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
        } elseif ($diff < 604800) {
            $days = round($diff / 86400);
            return $days . " day" . ($days > 1 ? "s" : "") . " ago";
        } else {
            return date('M d, Y', $time);
        }
    }
    ?>
    
    <!-- Link Font Awesome and Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize charts
        initCategoryChart();
        
        // Set up auto-refresh (every 30 seconds)
        setInterval(refreshDashboardData, 30000);
    });
    
    function initCategoryChart() {
        // Get category data
        const categoryData = <?php echo json_encode($_SESSION['dashboard_data']['category_distribution']); ?>;
        
        if (categoryData.length === 0) {
            document.querySelector('.category-chart').innerHTML = '<div class="no-data">No category data available.</div>';
            return;
        }
        
        // Extract labels and data
        const labels = categoryData.map(item => item.category);
        const data = categoryData.map(item => item.count);
        
        // Define colors
        const backgroundColors = [
            '#009f77', '#3498db', '#f39c12', '#e74c3c', 
            '#9b59b6', '#34495e', '#95a5a6', '#1abc9c',
            '#d35400', '#8e44ad', '#27ae60', '#2980b9'
        ];
        
        // Get the canvas
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        // Create the chart
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors.slice(0, labels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Function to refresh dashboard data via AJAX
    function refreshDashboardData() {
        fetch('../../controller/HOUSDashboardController.php?ajax=true')
        .then(response => response.json())
        .then(data => {
            // Update stats
            document.getElementById('pending-count').textContent = data.counts.pending_count;
            document.getElementById('forwarded-count').textContent = data.counts.forwarded_count;
            document.getElementById('answered-count').textContent = data.counts.answered_count;
            document.getElementById('lecturer-count').textContent = data.lecturer_count;
            
            // Update would continue here for recent questions and activity...
            // For a complete implementation, we would update all sections
            
            console.log('Dashboard data refreshed at', new Date().toLocaleTimeString());
        })
        .catch(error => {
            console.error('Error refreshing dashboard data:', error);
        });
    }
    </script>
    
    <style>
    /* Dashboard Styles */
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .dashboard-container h2 {
        color: #009f77;
        margin-bottom: 25px;
        text-align: center;
        font-size: 28px;
    }
    
    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #e0f5f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
        color: #009f77;
    }
    
    .stat-content h3 {
        font-size: 16px;
        color: #555;
        margin: 0 0 5px 0;
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    /* Dashboard Sections */
    .dashboard-section {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .section-header h3 {
        color: #333;
        margin: 0;
        font-size: 18px;
    }
    
    .view-all {
        color: #009f77;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.3s;
    }
    
    .view-all:hover {
        color: #00815f;
    }
    
    /* Table Styles */
    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .dashboard-table th {
        background-color: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #eee;
    }
    
    .dashboard-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .dashboard-table tr:last-child td {
        border-bottom: none;
    }
    
    .dashboard-table tr:hover td {
        background-color: #f9f9f9;
    }
    
    /* Category Badges */
    .category-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .category-coursework { background-color: #e0f5f0; color: #009f77; }
    .category-examination { background-color: #e0f0ff; color: #3498db; }
    .category-assignment { background-color: #fff8e0; color: #f39c12; }
    .category-registration { background-color: #ffe0e0; color: #e74c3c; }
    .category-financial { background-color: #f0e0ff; color: #9b59b6; }
    .category-medical { background-color: #e0e0e0; color: #34495e; }
    .category-accommodation { background-color: #f0e0ff; color: #9b59b6; }
    .category-mahapola { background-color: #ffe0e0; color: #e74c3c; }
    .category-other { background-color: #f0f0f0; color: #95a5a6; }
    
    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .status-pending { background-color: #fff8e0; color: #f39c12; }
    .status-forwarded { background-color: #e0f0ff; color: #3498db; }
    .status-answered { background-color: #e0f5f0; color: #009f77; }
    
    /* Action Buttons */
    .action-btn {
        display: inline-block;
        padding: 5px 10px;
        background-color: #009f77;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.8rem;
        transition: background-color 0.3s;
    }
    
    .action-btn:hover {
        background-color: #00815f;
    }
    
    /* Category Chart */
    .category-chart {
        height: 300px;
        position: relative;
        margin: 0 auto;
    }
    
    /* Timeline */
    .timeline {
        position: relative;
        margin: 20px 0;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 20px;
        height: 100%;
        width: 2px;
        background-color: #eee;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 50px;
        margin-bottom: 20px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e0f5f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #009f77;
        font-size: 16px;
        z-index: 1;
    }
    
    .timeline-forward .timeline-icon { background-color: #e0f0ff; color: #3498db; }
    .timeline-reply .timeline-icon { background-color: #e0f5f0; color: #009f77; }
    
    .timeline-content {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 4px;
    }
    
    .timeline-content p {
        margin: 0 0 5px 0;
    }
    
    .timeline-time {
        font-size: 0.8rem;
        color: #777;
    }
    
    /* No Data */
    .no-data {
        text-align: center;
        padding: 20px;
        color: #777;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .dashboard-table th, .dashboard-table td {
            padding: 10px;
        }
        
        .category-chart {
            height: 250px;
        }
    }
    
    @media (max-width: 576px) {
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .dashboard-table {
            font-size: 0.85rem;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .section-header h3 {
            margin-bottom: 10px;
        }
    }
    </style>
</main>
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Your mental health, your priority.</p>
          <img
            id="footer-logo"
            src="../../../assets/images/logo.jpg"
            alt="RelaxU Logo"
          />
        </div>
        <div class="footer-section">
          <h3>Quick Links</h3>
          <ul>
          <li><a href="../../views/houg/houg_home.php">Dashboard</a></li>
          <li><a href="#">Academic Requests</a></li>
          <li><a href="#">List of Lecturers</a></li>
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
  </body>
</html>


