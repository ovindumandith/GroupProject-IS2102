<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Check if stress trend data is available in the session
$trend = isset($_SESSION['stress_trend']) ? $_SESSION['stress_trend'] : null;

// If not available in the session or not enough data, redirect to history
if (!$trend || count($trend) < 2) {
    $_SESSION['info_message'] = "Not enough assessment data to display a trend. You need at least two assessments.";
    header('Location: assessment_history.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stress Trend - RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../../assets/css/stress_assessment.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .trend-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .trend-title {
            color: #3f72af;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }
        
        .chart-container {
            margin: 30px 0;
            background-color: #f9fbfd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dbe2ef;
        }
        
        .chart-section {
            position: relative;
            height: 400px;
            margin-bottom: 30px;
        }
        
        .trend-summary {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3f72af;
        }
        
        .summary-title {
            color: #112d4e;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        
        .insight-item {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
            line-height: 1.5;
        }
        
        .insight-item:before {
            content: "→";
            position: absolute;
            left: 0;
            color: #3f72af;
            font-weight: bold;
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
        
        /* Legend styling */
        .chart-legend {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin: 0 10px;
        }
        
        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            margin-right: 5px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .trend-container {
                margin: 20px;
                padding: 20px;
            }
            
            .chart-section {
                height: 300px;
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
        <div class="trend-container">
            <h1 class="trend-title">Your Stress Level Trend</h1>
            
            <div class="chart-container">
                <div class="chart-section">
                    <canvas id="stressChart"></canvas>
                </div>
                
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ef5350;"></div>
                        <span>Section 1 Score (Stress Perception)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #66bb6a;"></div>
                        <span>Section 2 Score (Coping &amp; Resilience)</span>
                    </div>
                </div>
            </div>
            
            <?php
            // Calculate trend insights
            $totalAssessments = count($trend);
            $firstAssessment = $trend[0];
            $latestAssessment = $trend[$totalAssessments - 1];
            
            $section1Change = $latestAssessment['section1_score'] - $firstAssessment['section1_score'];
            $section2Change = $latestAssessment['section2_score'] - $firstAssessment['section2_score'];
            
            $stressIncreasing = $section1Change > 0 || $section2Change < 0;
            $stressDecreasing = $section1Change < 0 || $section2Change > 0;
            ?>
            
            <div class="trend-summary">
                <h3 class="summary-title">Your Stress Management Insights</h3>
                
                <?php if ($totalAssessments > 1): ?>
                    <div class="insight-item">
                        You have completed <?= $totalAssessments ?> stress assessments.
                    </div>
                    
                    <?php if ($stressIncreasing): ?>
                        <div class="insight-item">
                            Your stress levels appear to be <?= $section1Change > 2 || $section2Change < -2 ? 'significantly' : 'slightly' ?> increasing over time. Consider implementing the recommended stress management techniques.
                        </div>
                    <?php elseif ($stressDecreasing): ?>
                        <div class="insight-item">
                            Your stress levels appear to be <?= $section1Change < -2 || $section2Change > 2 ? 'significantly' : 'slightly' ?> decreasing over time. Keep up the good work with your stress management techniques!
                        </div>
                    <?php else: ?>
                        <div class="insight-item">
                            Your stress levels have remained relatively stable over time.
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($latestAssessment['section1_score'] > 15 || $latestAssessment['section2_score'] < 5): ?>
                        <div class="insight-item">
                            Your current stress level is high. Consider consulting with a counselor for additional support.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="insight-item">
                    Regular assessments help track your stress patterns and the effectiveness of management techniques.
                </div>
            </div>
            
<div class="action-buttons">
    <a href="../../controller/StressAssessmentController.php?action=view_records" class="action-button back-btn">Back to History</a>
    <a href="../../controller/StressAssessmentController.php?action=download_report" class="action-button back-btn">Download Report</a>
    <a href="../../views/relaxation_activities.php" class="action-button new-assessment-btn">Explore Relaxation Activities</a>
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
            // PHP data to JavaScript
            const trendData = <?= json_encode($trend) ?>;
            
            // Prepare data for chart
            const dates = trendData.map(item => {
                const date = new Date(item.assessment_date);
                return date.toLocaleDateString('en-US', {month: 'short', day: 'numeric'});
            });
            
            const section1Scores = trendData.map(item => item.section1_score);
            const section2Scores = trendData.map(item => item.section2_score);
            
            // Create the chart
            const ctx = document.getElementById('stressChart').getContext('2d');
            const stressChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'Section 1: Stress Perception',
                            data: section1Scores,
                            borderColor: '#ef5350',
                            backgroundColor: 'rgba(239, 83, 80, 0.1)',
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: true
                        },
                        {
                            label: 'Section 2: Coping & Resilience',
                            data: section2Scores,
                            borderColor: '#66bb6a',
                            backgroundColor: 'rgba(102, 187, 106, 0.1)',
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 20,
                            title: {
                                display: true,
                                text: 'Score'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Assessment Date'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel: function(context) {
                                    // Add interpretation to the tooltip
                                    const datasetIndex = context.datasetIndex;
                                    const value = context.raw;
                                    
                                    if (datasetIndex === 0) { // Section 1
                                        if (value > 15) return 'High Stress Level';
                                        if (value >= 10) return 'Moderate Stress Level';
                                        return 'Low Stress Level';
                                    } else { // Section 2
                                        if (value < 5) return 'High Stress Level';
                                        if (value <= 10) return 'Moderate Stress Level';
                                        return 'Low Stress Level';
                                    }
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