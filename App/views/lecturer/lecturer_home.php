<?php
// In lecturer_home.php, at the beginning of the file
session_start();

// Check if user is logged in as lecturer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php');
    exit();
}

// Get lecturer ID
$lecturerId = $_SESSION['user_id'];

// Require the ForwardedQuestionModel
require_once '../../models/ForwardedQuestionModel.php';
$forwardedModel = new ForwardedQuestionModel();

// Get unread and recent forwarded questions
$unreadCount = $forwardedModel->getUnreadCount($lecturerId);
$recentForwardedQuestions = $forwardedModel->getForwardedQuestionsForLecturer($lecturerId);
// Limit to 5 most recent questions
$recentForwardedQuestions = array_slice($recentForwardedQuestions, 0, 5);

// Require the RepliedQuestionsModel
require_once '../../models/RepliedQuestionsModel.php';
$repliedModel = new RepliedQuestionsModel();

// Get recent replies by the lecturer
$recentReplies = $repliedModel->getRepliedQuestionsByLecturer($lecturerId);
// Limit to 5 most recent replies
$recentReplies = array_slice($recentReplies, 0, 5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link
      rel="stylesheet"
      href="../../../assets/css/header_footer.css"
      type="text/css"
    />
    <style>
        /* Add these styles for the forwarded questions section */
        .dashboard-section {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .section-title {
            color: #009f77;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .section-title h3 {
            margin: 0;
        }
        
        .notification-badge {
            background-color: #fa8128;
            color: white;
            border-radius: 50%;
            min-width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            padding: 0 5px;
        }
        
        .forwarded-questions-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .forwarded-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }
        
        .forwarded-item:last-child {
            border-bottom: none;
        }
        
        .forwarded-item:hover {
            background-color: #f9f9f9;
        }
        
        .forwarded-item.unread {
            background-color: #f0f7f4;
            border-left: 3px solid #009f77;
            font-weight: 600;
        }
        
        .forwarded-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .forwarded-category {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            background-color: #e0f5f0;
            color: #009f77;
        }
        
        .forwarded-question {
            margin: 8px 0;
            line-height: 1.4;
        }
        
        .forwarded-actions {
            margin-top: 10px;
        }
        
        .btn-view {
            background-color: #009f77;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }
        
        .btn-view:hover {
            background-color: #00815f;
        }
        
        .view-all-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #009f77;
            font-weight: 500;
            text-decoration: none;
        }
        
        /* Add these styles for the replied questions list */
        .replied-questions-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .replied-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }
        
        .replied-item:last-child {
            border-bottom: none;
        }
        
        .replied-item:hover {
            background-color: #f9f9f9;
        }
        
        .replied-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .replied-question {
            margin: 8px 0 4px;
            line-height: 1.4;
            color: #333;
        }
        
        .replied-answer {
            margin: 4px 0 8px;
            line-height: 1.4;
            color: #009f77;
            font-style: italic;
        }
        
        /* Dashboard layout */
        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .dashboard-greeting {
            margin-bottom: 30px;
        }
        
        .dashboard-greeting h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .dashboard-greeting p {
            color: #666;
            font-size: 16px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        @media (min-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .no-questions {
            padding: 20px;
            text-align: center;
            color: #666;
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
                <li><a href="lecturer_home.php" class="active">Dashboard</a></li>
                <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions">Academic Questions</a></li>
                <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Replied Questions</a></li>
                <li><a href="scheduler.php">Class Schedule</a></li>
                <li><a href="grading.php">Grading</a></li>
                <li><a href="resources.php">Resources</a></li>
            </ul>
        </nav>
      <div class="auth-buttons">
        <!-- Profile button form -->
<form action="hous_profile.php" method="GET">
    <button type="submit" class="login"><b>Profile</b></button>
</form>

    
        <!-- Logout button form -->
        <form action="../../../util/logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>
    
    <main>
        <div class="dashboard-greeting">
            <h2>Welcome, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Lecturer' ?></h2>
            <p>Here's an overview of your academic questions and activities</p>
        </div>
        
        <div class="dashboard-grid">
            <!-- Forwarded Questions Section -->
            <div class="dashboard-section">
                <div class="section-title">
                    <h3>Forwarded Academic Questions</h3>
                    <?php if ($unreadCount > 0): ?>
                        <span class="notification-badge"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($recentForwardedQuestions)): ?>
                    <ul class="forwarded-questions-list">
                        <?php foreach ($recentForwardedQuestions as $question): ?>
                            <li class="forwarded-item <?= $question['status'] === 'Unread' ? 'unread' : '' ?>">
                                <div class="forwarded-meta">
                                    <span>From: <?= htmlspecialchars($question['student_name']) ?></span>
                                    <span>Forwarded: <?= date('M d, Y', strtotime($question['forwarded_at'])) ?></span>
                                </div>
                                <span class="forwarded-category"><?= htmlspecialchars($question['category']) ?></span>
                                <p class="forwarded-question"><?= htmlspecialchars(substr($question['question'], 0, 150)) . (strlen($question['question']) > 150 ? '...' : '') ?></p>
                                <div class="forwarded-actions">
                                    <a href="../../controller/ForwardedQuestionController.php?action=viewQuestion&id=<?= $question['id'] ?>" class="btn-view">
                                        <?= $question['status'] === 'Unread' ? 'View New' : 'View' ?>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions" class="view-all-link">View All Forwarded Questions</a>
                <?php else: ?>
                    <p class="no-questions">No forwarded questions at this time.</p>
                <?php endif; ?>
            </div>
            
            <!-- Recently Replied Questions Section -->
            <div class="dashboard-section">
                <div class="section-title">
                    <h3>Recently Replied Questions</h3>
                </div>
                
                <?php if (!empty($recentReplies)): ?>
                    <ul class="replied-questions-list">
                        <?php foreach ($recentReplies as $reply): ?>
                            <li class="replied-item">
                                <div class="replied-meta">
                                    <span>To: <?= htmlspecialchars($reply['student_name']) ?></span>
                                    <span>Replied: <?= date('M d, Y', strtotime($reply['reply_date'])) ?></span>
                                </div>
                                <span class="forwarded-category"><?= htmlspecialchars($reply['category']) ?></span>
                                <p class="replied-question">
                                    <strong>Q:</strong> <?= htmlspecialchars(substr($reply['question'], 0, 100)) . (strlen($reply['question']) > 100 ? '...' : '') ?>
                                </p>
                                <p class="replied-answer">
                                    <strong>A:</strong> <?= htmlspecialchars(substr($reply['reply_text'], 0, 100)) . (strlen($reply['reply_text']) > 100 ? '...' : '') ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions" class="view-all-link">View All Replied Questions</a>
                <?php else: ?>
                    <p class="no-questions">You haven't replied to any questions yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Your existing content goes here -->
    </main>
    
    <!-- Footer Section -->
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