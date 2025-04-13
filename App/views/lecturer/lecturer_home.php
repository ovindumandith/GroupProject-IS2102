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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your existing head content -->
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
    </style>
</head>
<body>
    <!-- Your existing header -->
    
    <main>
        <!-- Add this section to display forwarded questions -->
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
                                <a href="../controller/ForwardedQuestionController.php?action=viewQuestion&id=<?= $question['id'] ?>" class="btn-view">
                                    <?= $question['status'] === 'Unread' ? 'View New' : 'View' ?>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="../controller/ForwardedQuestionController.php?action=viewForwardedQuestions" class="view-all-link">View All Forwarded Questions</a>
            <?php else: ?>
                <p class="no-questions">No forwarded questions at this time.</p>
            <?php endif; ?>
        </div>
        
        <!-- Your existing content -->
    </main>
    
    <!-- Your existing footer -->
</body>
</html>