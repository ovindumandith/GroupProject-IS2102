<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Get the latest assessment results
require_once '../../models/StressAssessmentModel.php';
$model = new StressAssessmentModel();
$userId = $_SESSION['user_id'];
$records = $model->getStressAssessmentRecords($userId);

// If no records found, redirect to the form
if (!$records) {
    $_SESSION['error_message'] = "No assessment records found. Please complete an assessment first.";
    header('Location: assessment_form.php');
    exit();
}

// Get the latest record (should be the first one)
$latestAssessment = $records[0];

// Get recommended techniques based on stress level
$recommendedTechniques = $model->getRecommendedTechniques($latestAssessment['stress_level']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Assessment Results - RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../../assets/css/stress_assessment.css" />
    <style>
        .results-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .results-title {
            color: #3f72af;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }
        
        .results-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .result-card {
            flex: 1;
            min-width: 200px;
            margin: 10px;
            padding: 20px;
            background-color: #f9fbfd;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        .result-card h3 {
            margin-bottom: 10px;
            color: #112d4e;
            font-size: 1.2rem;
        }
        
        .stress-level {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 15px 0;
            padding: 10px 0;
            border-radius: 5px;
        }
        
        .high {
            color: #b71c1c;
            background-color: #ffebee;
        }
        
        .moderate {
            color: #e65100;
            background-color: #fff3e0;
        }
        
        .low {
            color: #1b5e20;
            background-color: #e8f5e9;
        }
        
        .score-value {
            font-size: 2rem;
            font-weight: 700;
            color: #3f72af;
        }
        
        .score-label {
            font-size: 0.9rem;
            color: #777;
            margin-top: 5px;
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
            flex-wrap: wrap;
        }
        
        .action-button {
            flex: 1;
            margin: 10px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        
        .primary-btn {
            background-color: #3f72af;
            color: white;
        }
        
        .primary-btn:hover {
            background-color: #112d4e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(63, 114, 175, 0.3);
        }
        
        .secondary-btn {
            background-color: #dbe2ef;
            color: #3f72af;
        }
        
        .secondary-btn:hover {
            background-color: #c9d6e8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(63, 114, 175, 0.2);
        }
        
        .date-display {
            text-align: right;
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        /* Progress bar for scores */
        .score-bar-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 10px;
            margin: 10px 0;
            height: 15px;
            overflow: hidden;
        }
        
        .score-bar {
            height: 100%;
            border-radius: 10px;
            background-color: #3f72af;
            transition: width 1s ease-in-out;
        }
        
        @media (max-width: 768px) {
            .results-summary {
                flex-direction: column;
            }
            
            .result-card {
                margin: 10px 0;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-button {
                margin: 5px 0;
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
                        <li><a href="../../views/relaxation_activities_suggester.php">Relaxation Activities</a></li>
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
        <div class="results-container">
            <h1 class="results-title">Your Stress Assessment Results</h1>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="date-display">
                Assessment completed on: <?= date("F j, Y, g:i a", strtotime($latestAssessment['assessment_date'])) ?>
            </div>
            
            <div class="results-summary">
                <div class="result-card">
                    <h3>Overall Stress Level</h3>
                    <div class="stress-level <?= strtolower($latestAssessment['stress_level']) ?>">
                        <?= $latestAssessment['stress_level'] ?>
                    </div>
                </div>
                
                <div class="result-card">
                    <h3>Section 1: Stress Perception</h3>
                    <div class="score-value"><?= $latestAssessment['section1_score'] ?>/20</div>
                    <div class="score-label">Higher score indicates higher stress</div>
                    <div class="score-bar-container">
                        <div class="score-bar" style="width: <?= ($latestAssessment['section1_score'] / 20) * 100 ?>%;"></div>
                    </div>
                </div>
                
                <div class="result-card">
                    <h3>Section 2: Coping & Resilience</h3>
                    <div class="score-value"><?= $latestAssessment['section2_score'] ?>/20</div>
                    <div class="score-label">Lower score indicates higher stress</div>
                    <div class="score-bar-container">
                        <div class="score-bar" style="width: <?= ($latestAssessment['section2_score'] / 20) * 100 ?>%;"></div>
                    </div>
                </div>
            </div>
            
            <div class="recommendations">
                <h3>Personalized Recommendations</h3>
                <p>Based on your stress assessment, we recommend the following techniques to help manage your stress:</p>
                <ul class="techniques-list">
                    <?php foreach ($recommendedTechniques as $technique): ?>
                        <li><?= htmlspecialchars($technique) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="../../views/stress_assessment/assessment_form.php" class="action-button secondary-btn">Take Another Assessment</a>
                <a href="../../controller/StressAssessmentController.php?action=view_records" class="action-button secondary-btn">View Assessment History</a>
                <a href="../../views/relaxation_activities.php" class="action-button primary-btn">Explore Relaxation Activities</a>
                <?php if ($latestAssessment['stress_level'] === 'High'): ?>
                    <a href="../../controller/CounselorController.php?action=list" class="action-button primary-btn">Connect with a Counselor</a>
                <?php endif; ?>
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
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate the score bars when the page loads
        const scoreBars = document.querySelectorAll('.score-bar');
        setTimeout(() => {
            scoreBars.forEach(bar => {
                bar.style.width = bar.style.width;
            });
        }, 300);
    });
    </script>
</body>
</html>