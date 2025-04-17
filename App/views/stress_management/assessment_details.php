<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Check if assessment details are available in the session
$assessment = isset($_SESSION['assessment_details']) ? $_SESSION['assessment_details'] : null;
$recommendedTechniques = isset($_SESSION['recommended_techniques']) ? $_SESSION['recommended_techniques'] : null;

// If not available in the session, redirect to history
if (!$assessment || !$recommendedTechniques) {
    $_SESSION['error_message'] = "Assessment details not found. Please select an assessment from your history.";
    header('Location: assessment_history.php');
    exit();
}

// Define question texts for reference
$questions = [
    'section1_q1' => 'How many times have you been emotionally distressed by unexpected things happening?',
    'section1_q2' => 'How many times have you felt pressured to not be able to control or accomplish the important things in your life the way you want?',
    'section1_q3' => 'How much suffering did you experience because of hasty and restless behavior?',
    'section1_q4' => 'How many times have you been angry with yourself because things were out of your control?',
    'section1_q5' => 'How many times have you felt like you couldn\'t overcome them or that problems would be filled with difficulties?',
    'section2_q1' => 'How confident were you in your ability to solve your personal problems?',
    'section2_q2' => 'Did you feel that things happened the way you wanted them to?',
    'section2_q3' => 'How much of the bullying or frustration in your life was manageable?',
    'section2_q4' => 'How many times have you realized that not everything you do needs to be shared?',
    'section2_q5' => 'How many times have you felt satisfied and happy with the tasks you have completed?'
];

// Convert numerical values to text for display
$responseLabels = [
    0 => 'Never',
    1 => 'Almost Never',
    2 => 'Sometimes',
    3 => 'Fairly Often',
    4 => 'Very Often'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Assessment Details - RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../../assets/css/stress_assessment.css" />
    <style>
        .details-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .details-title {
            color: #3f72af;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }
        
        .assessment-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .stress-summary {
            display: flex;
            align-items: center;
        }
        
        .stress-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            margin-right: 15px;
        }
        
        .date-info {
            color: #666;
            font-size: 0.9rem;
        }
        
        .section-container {
            margin-bottom: 30px;
            background-color: #f9fbfd;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dbe2ef;
        }
        
        .section-header {
            color: #112d4e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dbe2ef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-score {
            background-color: #3f72af;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 600;
        }
        
        .question-response {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #e0e0e0;
        }
        
        .question-response:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .question-text {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }
        
        .response-value {
            display: flex;
            align-items: center;
        }
        
        .response-label {
            margin-left: 10px;
            font-weight: 600;
            color: #3f72af;
        }
        
        .response-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #bbdefb;
            margin-right: 10px;
            flex-grow: 1;
            position: relative;
        }
        
        .response-fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            border-radius: 4px;
            background-color: #3f72af;
        }
        
        .recommendations {
            margin-top: 30px;
            padding: 20px;
            background-color: #f1f8e9;
            border-radius: 8px;
            border-left: 4px solid #7cb342;
        }
        
        .recommendations h3 {
            color: #33691e;
            margin-bottom: 15px;
        }
        
        .techniques-list {
            list-style-type: none;
            padding: 0;
        }
        
        .techniques-list li {
            padding: 10px 0;
            border-bottom: 1px dashed #c5e1a5;
            display: flex;
            align-items: flex-start;
        }
        
        .techniques-list li:before {
            content: "•";
            color: #7cb342;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-right: 10px;
        }
        
        .techniques-list li:last-child {
            border-bottom: none;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .action-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .back-btn {
            background-color: #dbe2ef;
            color: #3f72af;
        }
        
        .new-assessment-btn {
            background-color: #3f72af;
            color: white;
        }
        
        .back-btn:hover {
            background-color: #c9d6e8;
        }
        
        .new-assessment-btn:hover {
            background-color: #112d4e;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .details-container {
                margin: 20px;
                padding: 20px;
            }
            
            .assessment-meta {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .date-info {
                margin-top: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .action-button {
                text-align: center;
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
                <li><a href="../../views/home.php">Home</a></li>
                <li class="services">
                    <a href="#">Services </a>
                    <ul class="dropdown">
                        <li><a href="../../views/stress_assessment/assessment_form.php">Stress Assessment</a></li>
                        <li><a href="../../views/relaxation_activities.php">Relaxation Activities</a></li>
                        <li><a href="#">Workload Management Tools</a></li>
                    </ul>
                </li>
                <li><a href="../../views/Academic_Help.php">Academic Help</a></li>
                <li><a href="../../controller/CounselorController.php?action=list">Counseling</a></li>
                <li><a href="../../controller/CommunityController.php?action=list">Community</a></li>
                <li><a href="../../views/About_Us.php">About Us</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <button class="signup" onclick="location.href='profile.php'"><b>Profile</b></button>
            <form action="../../../util/logout.php" method="post" style="display: inline">
                <button type="submit" class="login"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
        <div class="details-container">
            <h1 class="details-title">Assessment Details</h1>
            
            <div class="assessment-meta">
                <div class="stress-summary">
                    <div class="stress-badge <?= strtolower($assessment['stress_level']) ?>">
                        <?= htmlspecialchars($assessment['stress_level']) ?> Stress Level
                    </div>
                    <div>
                        <strong>Assessment #<?= htmlspecialchars($assessment['assessment_id']) ?></strong>
                    </div>
                </div>
                <div class="date-info">
                    Completed on: <?= date("F j, Y, g:i a", strtotime($assessment['assessment_date'])) ?>
                </div>
            </div>
            
            <!-- Section 1 -->
            <div class="section-container">
                <div class="section-header">
                    <h2>Section 1: Stress Perception</h2>
                    <div class="section-score"><?= $assessment['section1_score'] ?>/20</div>
                </div>
                
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php $questionKey = "section1_q{$i}"; ?>
                    <div class="question-response">
                        <div class="question-text"><?= htmlspecialchars($questions[$questionKey]) ?></div>
                        <div class="response-value">
                            <div class="response-bar">
                                <div class="response-fill" style="width: <?= ($assessment[$questionKey] / 4) * 100 ?>%;"></div>
                            </div>
                            <div class="response-label"><?= $responseLabels[$assessment[$questionKey]] ?> (<?= $assessment[$questionKey] ?>)</div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            
            <!-- Section 2 -->
            <div class="section-container">
                <div class="section-header">
                    <h2>Section 2: Coping & Resilience</h2>
                    <div class="section-score"><?= $assessment['section2_score'] ?>/20</div>
                </div>
                
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php $questionKey = "section2_q{$i}"; ?>
                    <div class="question-response">
                        <div class="question-text"><?= htmlspecialchars($questions[$questionKey]) ?></div>
                        <div class="response-value">
                            <div class="response-bar">
                                <div class="response-fill" style="width: <?= ($assessment[$questionKey] / 4) * 100 ?>%;"></div>
                            </div>
                            <div class="response-label"><?= $responseLabels[$assessment[$questionKey]] ?> (<?= $assessment[$questionKey] ?>)</div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            
            <!-- Recommendations -->
            <div class="recommendations">
                <h3>Recommended Stress Management Techniques</h3>
                <p>Based on your assessment results, here are personalized recommendations to help manage your stress:</p>
                <ul class="techniques-list">
                    <?php foreach ($recommendedTechniques as $technique): ?>
                        <li><?= htmlspecialchars($technique) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="../../controller/StressAssessmentController.php?action=view_records" class="action-button back-btn">Back to History</a>
                <a href="../../views/stress_assessment/assessment_form.php" class="action-button new-assessment-btn">Take New Assessment</a>
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1>RelaxU</h1>
                <p>Relax and Refresh while Excelling in your Studies</p>
                <img id="footer-logo" src="../../../assets/images/logo.jpg" alt="RelaxU Logo" />
            </div>
            <div class="footer-section">
                <h3>Services</h3>
                <ul>
                    <li><a href="../../views/stress_assessment/assessment_form.php">Stress Assessment</a></li>
                    <li><a href="../../views/relaxation_activities.php">Relaxation Activities</a></li>
                    <li><a href="../../views/Academic_Help.php">Academic Help</a></li>
                    <li><a href="../../controller/CounselorController.php?action=list">Counseling</a></li>
                    <li><a href="../../controller/CommunityController.php?action=list">Community</a></li>
                    <li><a href="#">Workload Management Tools</a></li>
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
                <li>
                    <a href="#"><img src="../../../assets/images/facebook.png" alt="Facebook" /></a>
                </li>
                <li>
                    <a href="#"><img src="../../../assets/images/twitter.png" alt="Twitter" /></a>
                </li>
                <li>
                    <a href="#"><img src="../../../assets/images/instagram.png" alt="Instagram" /></a>
                </li>
                <li>
                    <a href="#"><img src="../../../assets/images/youtube.png" alt="YouTube" /></a>
                </li>
            </ul>
        </div>
        <div class="footer-bottom">
            <p>copyright 2024 @RelaxU all rights reserved</p>
        </div>
    </footer>
</body>
</html>