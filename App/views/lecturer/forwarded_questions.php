<?php
session_start();

// Check if user is logged in as lecturer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php?error=unauthorized');
    exit();
}

// Get question details from session
$question = $_SESSION['question_details'] ?? null;

// If no question details, redirect to dashboard
if (!$question) {
    header('Location: lecturer_home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Details | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Main container styles */
        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        /* Page title */
        .page-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
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
        
        /* Question card */
        .question-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .question-header {
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .question-meta {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        
        .student-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0f5f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #009f77;
            font-weight: 600;
        }
        
        .student-details {
            display: flex;
            flex-direction: column;
        }
        
        .student-name {
            font-weight: 600;
            color: #333;
        }
        
        .student-id {
            font-size: 0.8rem;
            color: #666;
        }
        
        .question-date {
            font-size: 0.9rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .question-category {
            margin-top: 5px;
        }
        
        .category-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .category-coursework {
            background-color: #e0f5f0;
            color: #009f77;
        }
        
        .category-assignment {
            background-color: #e0f0ff;
            color: #0066cc;
        }
        
        .category-examination {
            background-color: #fff8e0;
            color: #ffaa00;
        }
        
        .category-financial {
            background-color: #f0e0ff;
            color: #8800cc;
        }
        
        .category-mahapola {
            background-color: #ffe0e0;
            color: #cc0000;
        }
        
        .category-registration {
            background-color: #e0ffe0;
            color: #00cc00;
        }
        
        .category-medical {
            background-color: #ffe0f0;
            color: #cc0066;
        }
        
        .category-accommodation {
            background-color: #e0e0ff;
            color: #0000cc;
        }
        
        .category-other {
            background-color: #f0f0f0;
            color: #666666;
        }
        
        .question-content {
            padding: 25px;
        }
        
        .question-text {
            font-size: 1rem;
            line-height: 1.6;
            color: #333;
            white-space: pre-wrap;
        }
        
        /* Reply section */
        .reply-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }
        
        .reply-section h3 {
            color: #009f77;
            margin-bottom: 20px;
            font-size: 1.3rem;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .reply-form {
            display: flex;
            flex-direction: column;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group textarea {
            width: 100%;
            height: 150px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            border: none;
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-cancel {
            background-color: #f1f1f1;
            color: #333;
        }
        
        .btn-cancel:hover {
            background-color: #e1e1e1;
        }
        
        .btn-submit {
            background-color: #009f77;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-submit:hover {
            background-color: #00815f;
        }
        
        /* Navigation buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background-color: #f1f1f1;
            color: #333;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .btn-back:hover {
            background-color: #e1e1e1;
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
                <li><a href="lecturer_home.php">Dashboard</a></li>
                <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions" class="active">Academic Questions</a></li>
                <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Replied Questions</a></li>
                <li><a href="scheduler.php">Class Schedule</a></li>
                <li><a href="grading.php">Grading</a></li>
                <li><a href="resources.php">Resources</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="lecturer_profile.php" class="profile-btn"><b>Profile</b></a>
            <form action="../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="logout-btn"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
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
        
        <div class="nav-buttons">
            <a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Questions
            </a>
        </div>
        
        <h1 class="page-title">Academic Question</h1>
        
        <div class="question-card">
            <div class="question-header">
                <div class="question-meta">
                    <div class="student-info">
                        <div class="student-avatar">
                            <?= strtoupper(substr($question['student_name'], 0, 1)) ?>
                        </div>
                        <div class="student-details">
                            <div class="student-name"><?= htmlspecialchars($question['student_name']) ?></div>
                            <?php if (isset($question['student_id'])): ?>
                                <div class="student-id">ID: <?= htmlspecialchars($question['student_id']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="question-date">
                        <i class="far fa-calendar-alt"></i>
                        Asked: <?= date('M d, Y', strtotime($question['question_date'])) ?>
                    </div>
                </div>
                <div class="question-category">
                    <span class="category-badge category-<?= strtolower(str_replace(' ', '_', $question['category'])) ?>">
                        <?= htmlspecialchars($question['category']) ?>
                    </span>
                </div>
            </div>
            <div class="question-content">
                <div class="question-text"><?= htmlspecialchars($question['question']) ?></div>
            </div>
        </div>
        
        <div class="reply-section">
            <h3>Your Response</h3>
            <form class="reply-form" action="../../controller/ForwardedQuestionController.php?action=respondToQuestion" method="POST">
                <input type="hidden" name="forwarded_id" value="<?= $question['id'] ?>">
                <input type="hidden" name="question_id" value="<?= $question['question_id'] ?>">
                
                <div class="form-group">
                    <label for="reply_text">Type your response below:</label>
                    <textarea id="reply_text" name="reply_text" placeholder="Provide a helpful and detailed response to the student's question..." required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="window.location.href='../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions'" class="btn btn-cancel">Cancel</button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane"></i> Send Response
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <!-- Footer Section -->
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
                    <li><a href="lecturer_home.php">Dashboard</a></li>
                    <li><a href="../../controller/ForwardedQuestionController.php?action=viewForwardedQuestions">Academic Questions</a></li>
                    <li><a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Replied Questions</a></li>
                    <li><a href="scheduler.php">Class Schedule</a></li>
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