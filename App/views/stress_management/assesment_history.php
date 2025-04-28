<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Check if assessment records are available in the session
$records = isset($_SESSION['assessment_records']) ? $_SESSION['assessment_records'] : null;

// If not available in the session, get them directly
if (!$records) {
    require_once '../../models/StressAssessmentModel.php';
    $model = new StressAssessmentModel();
    $userId = $_SESSION['user_id'];
    $records = $model->getStressAssessmentRecords($userId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Assessment History - RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../../assets/css/stress_assessment.css" />
    <style>
        .history-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .history-title {
            color: #3f72af;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background-color: #f9fbfd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #112d4e;
            margin-bottom: 15px;
            border-bottom: 1px solid #dbe2ef;
            padding-bottom: 10px;
        }

        .stress-level-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 15px;
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

        .card-info {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .card-info strong {
            color: #3f72af;
        }

        .date {
            color: #666;
            font-size: 0.9rem;
            margin-top: 15px;
            text-align: right;
        }

        .view-details-btn {
            display: block;
            background-color: #3f72af;
            color: white;
            text-align: center;
            padding: 8px 0;
            border-radius: 5px;
            margin-top: 15px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .view-details-btn:hover {
            background-color: #112d4e;
        }

        .no-records {
            text-align: center;
            padding: 40px 0;
            color: #666;
        }

        .new-assessment-btn {
            display: block;
            width: 220px;
            margin: 30px auto;
            background-color: #3f72af;
            color: white;
            text-align: center;
            padding: 12px 0;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .new-assessment-btn:hover {
            background-color: #112d4e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(63, 114, 175, 0.3);
        }

        @media (max-width: 768px) {
            .card-container {
                grid-template-columns: 1fr;
            }
            
            .history-container {
                margin: 20px;
                padding: 20px;
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
        <div class="history-container">
            <h1 class="history-title">Your Stress Assessment History</h1>
            
            <?php if (isset($_SESSION['info_message'])): ?>
                <div class="alert alert-info">
                    <?php 
                    echo $_SESSION['info_message']; 
                    unset($_SESSION['info_message']);
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
            
            <?php if ($records && count($records) > 0): ?>
                <a href="../../controller/StressAssessmentController.php?action=view_trend" class="view-details-btn" style="width: 220px; margin: 0 auto 20px auto;">View Stress Trend</a>
                
                <div class="card-container">
                    <?php foreach ($records as $record): ?>
                        <div class="card">
                            <h3>Assessment #<?= htmlspecialchars($record['assessment_id']) ?></h3>
                            <div class="stress-level-badge <?= strtolower($record['stress_level']) ?>">
                                <?= htmlspecialchars($record['stress_level']) ?> Stress
                            </div>
                            <div class="card-info">
                                <span>Section 1 Score:</span>
                                <strong><?= htmlspecialchars($record['section1_score']) ?>/20</strong>
                            </div>
                            <div class="card-info">
                                <span>Section 2 Score:</span>
                                <strong><?= htmlspecialchars($record['section2_score']) ?>/20</strong>
                            </div>
                            <div class="date">
                                <?= date("F j, Y, g:i a", strtotime($record['assessment_date'])) ?>
                            </div>
                            <a href="../../controller/StressAssessmentController.php?action=view_details&id=<?= $record['assessment_id'] ?>" class="view-details-btn">View Details</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-records">
                    <p>You haven't completed any stress assessments yet.</p>
                </div>
            <?php endif; ?>
            
            <a href="../../views/stress_assessment/assessment_form.php" class="new-assessment-btn">Take New Assessment</a>
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
                    <li><a href="../../views/relaxation_activities_suggester.php">Relaxation Activities</a></li>
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