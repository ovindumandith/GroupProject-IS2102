<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in as a counselor
if (!isset($_SESSION['counselor'])) {
    header('Location: ../../views/counselor_login.php');
    exit();
}

// Ensure stress trend data is available
if (!isset($_SESSION['stress_trend']) || !isset($_SESSION['student_name']) || !isset($_SESSION['student_id'])) {
    header('Location: ../../../controller/AppointmentController.php?action=showPendingAppointments');
    exit();
}

$trendData = $_SESSION['stress_trend'];
$studentName = $_SESSION['student_name'];
$studentId = $_SESSION['student_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Stress Trend - <?= htmlspecialchars($studentName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/header_footer.css" type="text/css" />
    <link rel="stylesheet" href="../../../assets/css/counselor_dashboard.css" type="text/css" />
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
        
        .student-info {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3f72af;
        }
        
        .current-status {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
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
            text-align: center;
        }
        
        .back-btn {
            background-color: #dbe2ef;
            color: #3f72af;
        }
        
        .approve-btn {
            background-color: #4CAF50;
            color: white;
        }
        
        .reject-btn {
            background-color: #f44336;
            color: white;
        }
        
        .back-btn:hover {
            background-color: #c9d6e8;
        }
        
        .approve-btn:hover {
            background-color: #45a049;
        }
        
        .reject-btn:hover {
            background-color: #d32f2f;
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
                margin: 5px 0;
            }

            .student-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 15px;
    margin: 15px 0;
}

.detail-item {
    margin-bottom: 10px;
}

.detail-item strong {
    color: #3f72af;
    font-weight: 600;
}

.appointment-details {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px dashed #bbdefb;
}

.appointment-details h4 {
    color: #3f72af;
    margin-bottom: 10px;
}

/* Enhanced status styles */
.current-status {
    margin-top: 20px;
    font-size: 1.2rem;
    font-weight: bold;
    padding: 15px;
    border-radius: 5px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.trending-indicator {
    display: inline-block;
    margin-left: 10px;
    font-size: 0.9rem;
    padding: 3px 8px;
    border-radius: 20px;
}

.trend-up {
    background-color: #ffcdd2;
    color: #c62828;
}

.trend-down {
    background-color: #c8e6c9;
    color: #2e7d32;
}

.trend-stable {
    background-color: #e1f5fe;
    color: #0277bd;
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
                <li><a href="../../views/counselling/counselor_dashboard.php">Dashboard</a></li>
                <li><a href="../../controller/AppointmentController.php?action=showPendingAppointments">Appointments</a></li>
                <li><a href="../../views/counselling/messages.php">Messages</a></li>
                <li><a href="../../views/counselling/reviews.php">Reviews</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <!-- Profile button form -->
            <form action="../../controller/CounselorController.php?action=viewLoggedInCounselorProfile" method="GET">
                <button type="submit" class="login"><b>Profile</b></button>
            </form>
            
            <!-- Logout button form -->
            <form action="../../util/counselor_logout.php" method="POST" style="display: inline;">
                <button type="submit" class="login"><b>Log Out</b></button>
            </form>
        </div>
    </header>

    <main>
        <div class="trend-container">
            <h1 class="trend-title">Stress Assessment Trend for <?= htmlspecialchars($studentName) ?></h1>
            
            <div class="student-info">
                <h3>Student Information</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($studentName) ?></p>
                <p><strong>ID:</strong> <?= htmlspecialchars($studentId) ?></p>
                
                <?php
                // Determine current stress level from the most recent assessment
                $latestAssessment = $trendData[count($trendData) - 1];
                $currentStressLevel = $latestAssessment['stress_level'];
                ?>
                
                <div class="current-status <?= strtolower($currentStressLevel) ?>">
                    Current Stress Level: <?= htmlspecialchars($currentStressLevel) ?>
                </div>
            </div>
            
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
            $totalAssessments = count($trendData);
            $firstAssessment = $trendData[0];
            $latestAssessment = $trendData[$totalAssessments - 1];
            
            $section1Change = $latestAssessment['section1_score'] - $firstAssessment['section1_score'];
            $section2Change = $latestAssessment['section2_score'] - $firstAssessment['section2_score'];
            
            $stressIncreasing = $section1Change > 0 || $section2Change < 0;
            $stressDecreasing = $section1Change < 0 || $section2Change > 0;
            
            // Get recommended techniques based on stress level
            require_once '../models/StressAssessmentModel.php';
            $stressModel = new StressAssessmentModel();
            $recommendedTechniques = $stressModel->getRecommendedTechniques($currentStressLevel);
            ?>
            
            <div class="recommendations">
                <h3>Recommended Counseling Approach</h3>
                <p>Based on the student's stress assessment data, the following approaches are recommended:</p>
                <ul class="techniques-list">
                    <?php foreach ($recommendedTechniques as $technique): ?>
                        <li><?= htmlspecialchars($technique) ?></li>
                    <?php endforeach; ?>
                </ul>
                
                <?php if ($totalAssessments > 1): ?>
                    <p>
                        <strong>Trend Analysis:</strong> 
                        <?php if ($stressIncreasing): ?>
                            The student's stress levels appear to be <?= abs($section1Change) > 2 || abs($section2Change) > 2 ? 'significantly' : 'slightly' ?> increasing over time. Consider a more intensive support approach.
                        <?php elseif ($stressDecreasing): ?>
                            The student's stress levels appear to be <?= abs($section1Change) > 2 || abs($section2Change) > 2 ? 'significantly' : 'slightly' ?> decreasing over time. The current approach seems to be effective.
                        <?php else: ?>
                            The student's stress levels have remained relatively stable over time.
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
            
<!-- Replace the action-buttons div in counselor_view_student_stress.php with this -->
<?php
// Check if we have an appointment ID to work with
$hasAppointment = isset($_SESSION['appointment_id']) && $_SESSION['appointment_id'];
$appointmentStatus = isset($_SESSION['appointment_details']['status']) ? $_SESSION['appointment_details']['status'] : null;
?>

<div class="action-buttons">
    <a href="../../../controller/AppointmentController.php?action=showPendingAppointments" class="action-button back-btn">Back to Appointments</a>
    
    <?php if ($hasAppointment && $appointmentStatus === 'Pending'): ?>
        <!-- Add form to directly approve or reject from this view -->
        <form method="POST" action="../../controller/AppointmentController.php?action=updateAppointmentStatus" style="display:inline; flex-grow: 1; margin: 0 10px;">
            <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($_SESSION['appointment_id']) ?>">
            <button type="submit" name="status" value="Accepted" class="action-button approve-btn" style="width: 100%;">
                Approve Appointment
            </button>
        </form>
        
        <form method="POST" action="../../controller/AppointmentController.php?action=updateAppointmentStatus" style="display:inline; flex-grow: 1; margin: 0 10px;">
            <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($_SESSION['appointment_id']) ?>">
            <button type="submit" name="status" value="Denied" class="action-button reject-btn" style="width: 100%;">
                Reject Appointment
            </button>
        </form>
    <?php elseif ($hasAppointment && $appointmentStatus): ?>
        <div class="appointment-status-indicator" style="flex-grow: 1; text-align: center; padding: 10px; background: <?= $appointmentStatus === 'Accepted' ? '#e8f5e9' : '#ffebee' ?>; border-radius: 5px; color: <?= $appointmentStatus === 'Accepted' ? '#2e7d32' : '#c62828' ?>;">
            Appointment status: <strong><?= htmlspecialchars($appointmentStatus) ?></strong>
        </div>
    <?php endif; ?>
</div>

        </div>

        <?php
// Get student details from session
$studentDetails = $_SESSION['student_details'] ?? null;
$latestAssessment = $_SESSION['latest_assessment'] ?? null;
$currentStressLevel = $latestAssessment ? $latestAssessment['stress_level'] : 'Unknown';
?>

<div class="student-info">
    <h3>Student Information</h3>
    <?php if ($studentDetails): ?>
    <div class="student-details-grid">
        <div class="detail-item">
            <strong>Name:</strong> <?= htmlspecialchars($studentDetails['name'] ?? 'Unknown') ?>
        </div>
        <div class="detail-item">
            <strong>ID:</strong> <?= htmlspecialchars($studentDetails['id'] ?? 'Unknown') ?>
        </div>
        <?php if (isset($studentDetails['email'])): ?>
        <div class="detail-item">
            <strong>Email:</strong> <?= htmlspecialchars($studentDetails['email']) ?>
        </div>
        <?php endif; ?>
        <?php if (isset($studentDetails['phone'])): ?>
        <div class="detail-item">
            <strong>Phone:</strong> <?= htmlspecialchars($studentDetails['phone']) ?>
        </div>
        <?php endif; ?>
        <?php if (isset($studentDetails['department'])): ?>
        <div class="detail-item">
            <strong>Department:</strong> <?= htmlspecialchars($studentDetails['department']) ?>
        </div>
        <?php endif; ?>
        <?php if (isset($studentDetails['academic_year'])): ?>
        <div class="detail-item">
            <strong>Academic Year:</strong> <?= htmlspecialchars($studentDetails['academic_year']) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <p>Limited student information available.</p>
    <?php endif; ?>
    
    <div class="current-status <?= strtolower($currentStressLevel) ?>">
        Current Stress Level: <?= htmlspecialchars($currentStressLevel) ?>
    </div>
    
    <?php if (isset($_SESSION['appointment_details']) && $_SESSION['appointment_details']): ?>
    <div class="appointment-details">
        <h4>Appointment Request Details</h4>
        <p><strong>Topic:</strong> <?= htmlspecialchars($_SESSION['appointment_details']['topic']) ?></p>
        <p><strong>Requested Date:</strong> <?= htmlspecialchars($_SESSION['appointment_details']['appointment_date']) ?></p>
    </div>
    <?php endif; ?>
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
                    <li><a href="../../views/counselling/counselor_dashboard.php">Dashboard</a></li>
                    <li><a href="../../controller/AppointmentController.php?action=showPendingAppointments">Appointments</a></li>
                    <li><a href="../../views/counselling/messages.php">Messages</a></li>
                    <li><a href="../../views/counselling/reviews.php">Reviews</a></li>
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
            // PHP data to JavaScript
            const trendData = <?= json_encode($trendData) ?>;
            
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