<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stress Assessment - RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" />
    <link rel="stylesheet" href="../../../assets/css/stress_assessment.css" />
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
        <div class="container">
            <h1 class="assessment-title">Stress Assessment Questionnaire</h1>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error">
                    <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="assessment-instructions">
                <p>Please answer the following questions based on your experiences in the past month. For each question, select the option that best describes how often you have felt or thought a certain way.</p>
                <p><strong>Score Options:</strong> Never (0), Almost Never (1), Sometimes (2), Fairly Often (3), Very Often (4)</p>
            </div>
            
            <form id="stress-assessment-form" action="../../controller/StressAssessmentController.php?action=submit_assessment" method="POST">
                <div class="form-section">
                    <h2>Section 1: Stress Perception</h2>
                    
                    <div class="question-item">
                        <label for="section1_q1">How many times have you been emotionally distressed by unexpected things happening?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section1_q1_0" name="section1_q1" value="0" required>
                                <label for="section1_q1_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q1_1" name="section1_q1" value="1">
                                <label for="section1_q1_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q1_2" name="section1_q1" value="2">
                                <label for="section1_q1_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q1_3" name="section1_q1" value="3">
                                <label for="section1_q1_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q1_4" name="section1_q1" value="4">
                                <label for="section1_q1_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-item">
                        <label for="section1_q2">How many times have you felt pressured to not be able to control or accomplish the important things in your life the way you want?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section1_q2_0" name="section1_q2" value="0" required>
                                <label for="section1_q2_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q2_1" name="section1_q2" value="1">
                                <label for="section1_q2_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q2_2" name="section1_q2" value="2">
                                <label for="section1_q2_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q2_3" name="section1_q2" value="3">
                                <label for="section1_q2_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q2_4" name="section1_q2" value="4">
                                <label for="section1_q2_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-item">
                        <label for="section1_q3">How much suffering did you experience because of hasty and restless behavior?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section1_q3_0" name="section1_q3" value="0" required>
                                <label for="section1_q3_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q3_1" name="section1_q3" value="1">
                                <label for="section1_q3_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q3_2" name="section1_q3" value="2">
                                <label for="section1_q3_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q3_3" name="section1_q3" value="3">
                                <label for="section1_q3_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q3_4" name="section1_q3" value="4">
                                <label for="section1_q3_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-item">
                        <label for="section1_q4">How many times have you been angry with yourself because things were out of your control?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section1_q4_0" name="section1_q4" value="0" required>
                                <label for="section1_q4_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q4_1" name="section1_q4" value="1">
                                <label for="section1_q4_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q4_2" name="section1_q4" value="2">
                                <label for="section1_q4_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q4_3" name="section1_q4" value="3">
                                <label for="section1_q4_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q4_4" name="section1_q4" value="4">
                                <label for="section1_q4_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-item">
                        <label for="section1_q5">How many times have you felt like you couldn't overcome them or that problems would be filled with difficulties?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section1_q5_0" name="section1_q5" value="0" required>
                                <label for="section1_q5_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q5_1" name="section1_q5" value="1">
                                <label for="section1_q5_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q5_2" name="section1_q5" value="2">
                                <label for="section1_q5_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q5_3" name="section1_q5" value="3">
                                <label for="section1_q5_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section1_q5_4" name="section1_q5" value="4">
                                <label for="section1_q5_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Section 2: Coping & Resilience</h2>
                    
                    <div class="question-item">
                        <label for="section2_q1">How confident were you in your ability to solve your personal problems?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section2_q1_0" name="section2_q1" value="0" required>
                                <label for="section2_q1_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q1_1" name="section2_q1" value="1">
                                <label for="section2_q1_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q1_2" name="section2_q1" value="2">
                                <label for="section2_q1_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q1_3" name="section2_q1" value="3">
                                <label for="section2_q1_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q1_4" name="section2_q1" value="4">
                                <label for="section2_q1_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-item">
                        <label for="section2_q2">Did you feel that things happened the way you wanted them to?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section2_q2_0" name="section2_q2" value="0" required>
                                <label for="section2_q2_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q2_1" name="section2_q2" value="1">
                                <label for="section2_q2_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q2_2" name="section2_q2" value="2">
                                <label for="section2_q2_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q2_3" name="section2_q2" value="3">
                                <label for="section2_q2_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q2_4" name="section2_q2" value="4">
                                <label for="section2_q2_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-item">
                        <label for="section2_q3">How much of the bullying or frustration in your life was manageable?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section2_q3_0" name="section2_q3" value="0" required>
                                <label for="section2_q3_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q3_1" name="section2_q3" value="1">
                                <label for="section2_q3_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q3_2" name="section2_q3" value="2">
                                <label for="section2_q3_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q3_3" name="section2_q3" value="3">
                                <label for="section2_q3_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q3_4" name="section2_q3" value="4">
                                <label for="section2_q3_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-item">
                        <label for="section2_q4">How many times have you realized that not everything you do needs to be shared?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section2_q4_0" name="section2_q4" value="0" required>
                                <label for="section2_q4_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q4_1" name="section2_q4" value="1">
                                <label for="section2_q4_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q4_2" name="section2_q4" value="2">
                                <label for="section2_q4_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q4_3" name="section2_q4" value="3">
                                <label for="section2_q4_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q4_4" name="section2_q4" value="4">
                                <label for="section2_q4_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-item">
                        <label for="section2_q5">How many times have you felt satisfied and happy with the tasks you have completed?</label>
                        <div class="rating-options">
                            <div class="rating-option">
                                <input type="radio" id="section2_q5_0" name="section2_q5" value="0" required>
                                <label for="section2_q5_0">Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q5_1" name="section2_q5" value="1">
                                <label for="section2_q5_1">Almost Never</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q5_2" name="section2_q5" value="2">
                                <label for="section2_q5_2">Sometimes</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q5_3" name="section2_q5" value="3">
                                <label for="section2_q5_3">Fairly Often</label>
                            </div>
                            <div class="rating-option">
                                <input type="radio" id="section2_q5_4" name="section2_q5" value="4">
                                <label for="section2_q5_4">Very Often</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="submit-container">
                    <button type="submit" class="submit-btn">Submit Assessment</button>
                </div>
            </form>
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

    <script src="../../../assets/js/stress_management_form.js"></script>
</body>
</html>