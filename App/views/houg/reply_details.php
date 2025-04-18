<?php
session_start();

// Check if user is logged in as head of undergraduate studies
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
    header('Location: ../login.php?error=unauthorized');
    exit();
}

// Get reply details from session
$replyDetails = $_SESSION['reply_details'] ?? null;

// If no reply details, redirect to list page
if (!$replyDetails) {
    header('Location: ../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply Details | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link
      rel="stylesheet"
      href="../../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../../assets/css/houg_home.css" type="text/css" />
    <style>
        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        h2 {
            color: #009f77;
            margin-bottom: 25px;
            text-align: center;
            font-size: 28px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 20px;
            color: #009f77;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .details-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .card-header {
            background-color: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .card-title {
            color: #333;
            margin: 0;
            font-size: 1.3rem;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            width: 150px;
            color: #333;
            padding-right: 15px;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .category-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
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
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
            margin-top: 5px;
            white-space: pre-wrap;
        }
        
        .reply-content {
            padding: 15px;
            background-color: #f0f7f4;
            border-radius: 4px;
            margin-top: 5px;
            white-space: pre-wrap;
            border-left: 3px solid #009f77;
        }
        
        .section-header {
            margin-top: 25px;
            margin-bottom: 10px;
            color: #009f77;
            font-size: 1.2rem;
        }
        
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #666;
            font-size: 0.9rem;
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
          <li><a href="../controller/HOUSDashboardController.php">Dashboard</a></li>
          <li><a href="../controller/Academic_QuestionsController.php?action=viewAllQuestions_hous">Academic Requests</a></li>
          <li><a href="../controller/RepliedQuestionsController.php?action=viewRepliedQuestions">Forwarded-Replied Questions</a></li>
          <li><a href="../controller/LecturerController.php?action=list">List of Lecturers</a></li>
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
        <a href="../../controller/RepliedQuestionsController.php?action=viewRepliedQuestions" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Replied Questions
        </a>
        
        <h2>Question & Reply Details</h2>
        
        <div class="details-card">
            <div class="card-header">
                <h3 class="card-title">Question Information</h3>
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <div class="detail-label">Student Name:</div>
                    <div class="detail-value"><?= htmlspecialchars($replyDetails['student_name']) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Student ID:</div>
                    <div class="detail-value"><?= htmlspecialchars($replyDetails['student_id']) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value"><?= htmlspecialchars($replyDetails['student_email']) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Category:</div>
                    <div class="detail-value">
                        <span class="category-badge category-<?= strtolower(str_replace(' ', '_', $replyDetails['category'])) ?>">
                            <?= htmlspecialchars($replyDetails['category']) ?>
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Question Date:</div>
                    <div class="detail-value"><?= date('M d, Y', strtotime($replyDetails['question_date'])) ?></div>
                </div>
                
                <h4 class="section-header">Question</h4>
                <div class="question-content">
                    <?= htmlspecialchars($replyDetails['question']) ?>
                </div>
            </div>
        </div>
        
        <div class="details-card">
            <div class="card-header">
                <h3 class="card-title">Reply Information</h3>
            </div>
            <div class="card-body">
                <div class="meta-info">
                    <span>
                        <strong>Replied by:</strong> <?= htmlspecialchars($replyDetails['responder_name']) ?>
                    </span>
                    <span>
                        <strong>Date:</strong> <?= date('M d, Y h:i A', strtotime($replyDetails['reply_date'])) ?>
                    </span>
                </div>
                
                <h4 class="section-header">Reply</h4>
                <div class="reply-content">
                    <?= htmlspecialchars($replyDetails['reply_text']) ?>
                </div>
            </div>
        </div>
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