<?php
// File: views/houg/lecturer_profile.php
session_start();

// Check if user is logged in as HOUS
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
    header('Location: ../../login.php?error=unauthorized');
    exit();
}

// Get lecturer profile data from session
$lecturerProfile = $_SESSION['lecturer_profile'] ?? [];

if (empty($lecturerProfile)) {
    header('Location: ../../controller/LecturerController.php?action=list');
    exit();
}

$lecturer = $lecturerProfile['details'];
$forwardedQuestions = $lecturerProfile['forwarded_questions'];
$replies = $lecturerProfile['replies'];
$stats = $lecturerProfile['stats'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lecturer['name']) ?> - Lecturer Profile | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Main content styles */
        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        /* Profile header */
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #e0f5f0;
            color: #009f77;
            font-size: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 30px;
        }
        
        .profile-info {
            flex: 1;
        }
        
        .profile-name {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin: 0 0 5px;
        }
        
        .profile-department {
            font-size: 1.2rem;
            color: #666;
            margin: 0 0 15px;
        }
        
        .profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
        }
        
        .meta-icon {
            margin-right: 10px;
            color: #009f77;
            width: 16px;
            text-align: center;
        }
        
        .meta-text {
            font-size: 0.95rem;
            color: #333;
        }
        
        .category-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 500;
            background-color: #e0f5f0;
            color: #009f77;
            margin-left: 10px;
        }
        
        .action-buttons {
            margin-top: 15px;
        }
        
        .action-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #009f77;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 10px;
            transition: background-color 0.3s;
        }
        
        .action-btn:hover {
            background-color: #00815f;
        }
        
        /* Profile content */
        .profile-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .profile-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
            /* Profile content */
        .profile-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .profile-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            color: #333;
            margin: 0 0 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-size: 1.2rem;
        }
        
        /* Stats cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 600;
            color: #009f77;
            margin: 0 0 5px;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: #666;
            margin: 0;
        }
        
        /* Bio section */
        .bio-section {
            margin-bottom: 20px;
        }
        
        .bio-text {
            line-height: 1.6;
            color: #333;
        }
        
        /* Questions table */
        .questions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .questions-table th {
            background-color: #f8f9fa;
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #eee;
            font-size: 0.9rem;
        }
        
        .questions-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }
        
        .questions-table tr:last-child td {
            border-bottom: none;
        }
        
        .questions-table tr:hover td {
            background-color: #f9f9f9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-unread { background-color: #fff8e0; color: #f39c12; }
        .status-read { background-color: #e0f0ff; color: #3498db; }
        .status-responded { background-color: #e0f5f0; color: #009f77; }
        
        .small-text {
            font-size: 0.8rem;
            color: #666;
        }
        
        .view-all-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #009f77;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .view-all-link:hover {
            text-decoration: underline;
        }
        
        /* Activity chart */
        .activity-chart {
            height: 300px;
            position: relative;
            margin-top: 20px;
        }
        
        /* No data */
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            background-color: #f9f9f9;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        /* Back button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            color: #009f77;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .back-btn:hover {
            color: #00815f;
        }
        
        .back-icon {
            margin-right: 8px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .profile-content {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-avatar {
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .profile-meta {
                justify-content: center;
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
                <li><a href="../../controller/HOUSDashboardController.php">Dashboard</a></li>
                <li><a href="../../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous">Academic Requests</a></li>
                <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Forwarded-Replied Questions</a></li>
                <li><a href="../../controller/LecturerController.php?action=list" class="active">List of Lecturers</a></li>
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

    <main>
        <a href="../../controller/LecturerController.php?action=list" class="back-btn">
            <span class="back-icon"><i class="fas fa-arrow-left"></i></span> Back to Lecturer List
        </a>
        
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <h1 class="profile-name"><?= htmlspecialchars($lecturer['name']) ?></h1>
                <h2 class="profile-department"><?= htmlspecialchars($lecturer['department']) ?></h2>
                
                <div class="profile-meta">
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="meta-text"><?= htmlspecialchars($lecturer['email']) ?></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="meta-text"><?= htmlspecialchars($lecturer['phone']) ?></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="meta-text">
                            Category:
                            <span class="category-badge"><?= htmlspecialchars($lecturer['category']) ?></span>
                        </div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="meta-text">
                            Member since: <?= date('M d, Y', strtotime($lecturer['join_date'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="profile-content">
            <div class="profile-section">
                <h3 class="section-title">Activity Summary</h3>
                
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['forwarded_count'] ?></div>
                        <div class="stat-label">Forwarded Questions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['unread_count'] ?></div>
                        <div class="stat-label">Unread</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['read_count'] ?></div>
                        <div class="stat-label">Read</div>
                    </div>

                </div>
                
                <div class="activity-chart">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
            
            <div class="profile-section">
                <h3 class="section-title">Lecturer Bio</h3>
                
                <div class="bio-section">
                    <?php if (!empty($lecturer['bio'])): ?>
                        <p class="bio-text"><?= nl2br(htmlspecialchars($lecturer['bio'])) ?></p>
                    <?php else: ?>
                        <p class="bio-text">No bio information available for this lecturer.</p>
                    <?php endif; ?>
                </div>
                
                <h3 class="section-title">Department Information</h3>
                <p>
                    <strong><?= htmlspecialchars($lecturer['department']) ?> Department</strong><br>
                    Specialization: <?= htmlspecialchars($lecturer['category']) ?><br>
                    Contact: <?= htmlspecialchars($lecturer['email']) ?>
                </p>
            </div>
        </div>
        
        <div class="profile-content">
            <div class="profile-section">
                <h3 class="section-title">Recent Forwarded Questions</h3>
                
                <?php if (empty($forwardedQuestions)): ?>
                    <div class="no-data">No forwarded questions found for this lecturer.</div>
                <?php else: ?>
                    <table class="questions-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Sort by most recent first and limit to 5
                            usort($forwardedQuestions, function($a, $b) {
                                return strtotime($b['forwarded_at']) - strtotime($a['forwarded_at']);
                            });
                            
                            $recentForwarded = array_slice($forwardedQuestions, 0, 5);
                            
                            foreach ($recentForwarded as $question): 
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($question['student_name']) ?></td>
                                    <td>
                                        <span class="category-badge category-<?= strtolower(str_replace(' ', '-', $question['category'])) ?>">
                                            <?= htmlspecialchars($question['category']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($question['status']) ?>">
                                            <?= htmlspecialchars($question['status']) ?>
                                        </span>
                                    </td>
                                    <td class="small-text"><?= date('M d, Y', strtotime($question['forwarded_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php if (count($forwardedQuestions) > 5): ?>
                        <a href="#" class="view-all-link">View All Forwarded Questions (<?= count($forwardedQuestions) ?>)</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="profile-section">
                <h3 class="section-title">Recent Replies</h3>
                
                <?php if (empty($replies)): ?>
                    <div class="no-data">No replies found from this lecturer.</div>
                <?php else: ?>
                    <table class="questions-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Category</th>
                                <th>Reply</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Sort by most recent first and limit to 5
                            usort($replies, function($a, $b) {
                                return strtotime($b['created_at']) - strtotime($a['created_at']);
                            });
                            
                            $recentReplies = array_slice($replies, 0, 5);
                            
                            foreach ($recentReplies as $reply): 
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($reply['student_name']) ?></td>
                                    <td>
                                        <span class="category-badge category-<?= strtolower(str_replace(' ', '-', $reply['category'])) ?>">
                                            <?= htmlspecialchars($reply['category']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        // Truncate reply text if too long
                                        $replyText = $reply['reply_text'];
                                        if (strlen($replyText) > 70) {
                                            $replyText = substr($replyText, 0, 70) . '...';
                                        }
                                        echo htmlspecialchars($replyText);
                                        ?>
                                    </td>
                                    <td class="small-text"><?= date('M d, Y', strtotime($reply['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php if (count($replies) > 5): ?>
                        <a href="#" class="view-all-link">View All Replies (<?= count($replies) ?>)</a>
                    <?php endif; ?>
                <?php endif; ?>
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
                    <li><a href="../../controller/HOUSDashboardController.php">Dashboard</a></li>
                    <li><a href="../../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous">Academic Requests</a></li>
                    <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Forwarded-Replied Questions</a></li>
                    <li><a href="../../controller/LecturerController.php?action=list">List of Lecturers</a></li>
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
            // Initialize activity chart
            const activityData = {
                labels: ['Unread', 'Read', 'Responded'],
                datasets: [{
                    label: 'Question Status',
                    data: [
                        <?= $stats['unread_count'] ?>, 
                        <?= $stats['read_count'] ?>, 
                        <?= $stats['responded_count'] ?>
                    ],
                    backgroundColor: [
                        '#fff8e0', // Unread (yellow)
                        '#e0f0ff', // Read (blue)
                        '#e0f5f0'  // Responded (green)
                    ],
                    borderColor: [
                        '#f39c12', // Unread (yellow)
                        '#3498db', // Read (blue)
                        '#009f77'  // Responded (green)
                    ],
                    borderWidth: 1
                }]
            };
            
            const ctx = document.getElementById('activityChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: activityData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>