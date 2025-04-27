<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['counselor'])) {
    header('Location: counselor_login.php');
    exit();
}

// Get counselor details from the session
$counselor = $_SESSION['counselor'];

// Include the Counselor Dashboard Model to get real data
require_once '../../models/CounselorDashboardModel.php';
$dashboardModel = new CounselorDashboardModel();

// Get dashboard data
$dashboardData = $dashboardModel->getAllDashboardMetrics($counselor['id']);

// Prepare data for display
$totalAppointments = $dashboardData['total_appointments'];
$pendingAppointments = $dashboardData['pending_appointments'];
$acceptedAppointments = $dashboardData['accepted_appointments'];
$deniedAppointments = $dashboardData['denied_appointments'];
$upcomingAppointments = $dashboardData['upcoming_appointments'];
$pendingAppointmentList = $dashboardData['pending_appointment_list'];
$totalReviews = $dashboardData['total_reviews'];
$averageRating = $dashboardData['average_rating'];
$recentReviews = $dashboardData['recent_reviews'];
$monthlyStats = $dashboardData['monthly_stats'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RelaxU - Counselor Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link
      rel="stylesheet"
      href="../../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../../assets/css/counselor_dashboard.css" type="text/css" />
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
          <li><a href="../../views/counselling/counselor_dashboard.php" class="active">Dashboard</a></li>
          <li class="services">
            <a href="#">Appointments </a>
            <ul class="dropdown">
              <li><a href="../../controller/AppointmentController.php?action=showPendingAppointments">Pending (<?php echo $pendingAppointments; ?>)</a></li>
              <li><a href="../../controller/AppointmentController.php?action=showApprovedAppointments">Approved (<?php echo $acceptedAppointments; ?>)</a></li>
              <li><a href="../../controller/AppointmentController.php?action=showDeniedAppointments">Denied (<?php echo $deniedAppointments; ?>)</a></li>
            </ul>
          </li>
          <li><a href="../views/messages.php">Messages</a></li>
          <li><a href="../../views/counselling/reviews.php">Reviews (<?php echo $totalReviews; ?>)</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <a href="/GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile" class="login" style="display: inline-block; text-decoration: none; background-color: #fa8128; color: white; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; margin-left: 10px; font-size: 1rem; transition: background-color 0.3s ease;">
            <b>Profile</b>
        </a>

        <!-- Logout button form -->
        <form action="../../../util/counselor_logout.php" method="POST" style="display: inline;">
          <button type="submit" class="login"><b>Log Out</b></button>
        </form>
      </div>
    </header>

    <!-- Content Section -->
    <section class="hero">
      <div class="hero-content">
        <div class="hero-text">
          <h1>
            Welcome, <?php echo htmlspecialchars($counselor['name']); ?>!
            <span>Your Dashboard Awaits</span>
          </h1>
          <p>
            Manage appointments, assist students, and monitor your progress in
            supporting mental wellness.
          </p>
          <a href="../../controller/AppointmentController.php?action=showPendingAppointments" class="get-started-btn">
            <?php if ($pendingAppointments > 0): ?>
                View <?php echo $pendingAppointments; ?> Pending Appointments
            <?php else: ?>
                Check Schedule
            <?php endif; ?>
          </a>
        </div>
      </div>
    </section>

    <div class="dashboard-container">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Appointments</h3>
                <div class="stat-count"><?php echo $totalAppointments; ?></div>
                <div class="stat-description">
                    All appointments scheduled with you
                </div>
            </div>
            
            <div class="stat-card pending">
                <h3>Pending Approval</h3>
                <div class="stat-count"><?php echo $pendingAppointments; ?></div>
                <div class="stat-description">
                    <?php if ($pendingAppointments > 0): ?>
                        <span class="stat-increase">Action required</span>
                    <?php else: ?>
                        No pending appointments
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="stat-card accepted">
                <h3>Approved Sessions</h3>
                <div class="stat-count"><?php echo $acceptedAppointments; ?></div>
                <div class="stat-description">
                    Scheduled counseling sessions
                </div>
            </div>
            
            <div class="stat-card rating">
                <h3>Average Rating</h3>
                <div class="stat-count"><?php echo number_format($averageRating, 1); ?>/5</div>
                <div class="stat-description">
                    Based on <?php echo $totalReviews; ?> student reviews
                </div>
            </div>
        </div>
        
        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Appointments Section -->
                <div class="appointments-section">
                    <div class="section-header">
                        <h2><i class="fas fa-calendar-check"></i> Pending Appointments</h2>
                        <a href="../../controller/AppointmentController.php?action=showPendingAppointments" class="view-all-btn">View All</a>
                    </div>
                    <div class="section-body">
                        <?php if (empty($pendingAppointmentList)): ?>
                            <div style="text-align: center; padding: 2rem;">
                                <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--green); margin-bottom: 1rem;"></i>
                                <p>No pending appointments to approve.</p>
                            </div>
                        <?php else: ?>
                            <table class="appointments-table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Date & Time</th>
                                        <th>Topic</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingAppointmentList as $appointment): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($appointment['student_name']); ?></td>
                                            <td>
                                                <?php 
                                                    $date = new DateTime($appointment['appointment_date']);
                                                    echo $date->format('M d, Y - h:i A'); 
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($appointment['topic']); ?></td>
                                            <td class="action-btns">
                                                <a href="../../controller/AppointmentController.php?action=acceptAppointment&id=<?php echo $appointment['id']; ?>" class="action-btn accept">Accept</a>
                                                <a href="../../controller/AppointmentController.php?action=denyAppointment&id=<?php echo $appointment['id']; ?>" class="action-btn deny">Deny</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Chart Section -->
                <div class="chart-section">
                    <div class="section-header">
                        <h2><i class="fas fa-chart-line"></i> Appointment Trends</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="appointmentTrendChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="right-column">
                <!-- Calendar Section -->
                <div class="calendar-section">
                    <div class="section-header">
                        <h2><i class="fas fa-calendar-alt"></i> Upcoming Sessions</h2>
                    </div>
                    <div class="calendar-container">
                        <?php if (empty($upcomingAppointments)): ?>
                            <div style="text-align: center; padding: 2rem;">
                                <i class="fas fa-calendar" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                                <p>No upcoming sessions scheduled.</p>
                            </div>
                        <?php else: ?>
                            <ul class="upcoming-sessions">
                                <?php foreach ($upcomingAppointments as $appointment): ?>
                                    <div style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                            <span style="font-weight: 600;"><?php echo htmlspecialchars($appointment['student_name']); ?></span>
                                            <span style="color: var(--primary-color);">
                                                <?php 
                                                    $date = new DateTime($appointment['appointment_date']);
                                                    echo $date->format('h:i A'); 
                                                ?>
                                            </span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="color: var(--text-light);"><?php echo htmlspecialchars($appointment['topic']); ?></span>
                                            <span style="color: var(--text-lighter);">
                                                <?php echo $date->format('M d, Y'); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
                <br/>
                <!-- Reviews Section -->
                <div class="reviews-section">
                    <div class="section-header">
                        <h2><i class="fas fa-star"></i> Recent Reviews</h2>
                        <a href="../views/reviews.php" class="view-all-btn">View All</a>
                    </div>
                    <div class="section-body">
                        <?php if (empty($recentReviews)): ?>
                            <div style="text-align: center; padding: 2rem;">
                                <i class="fas fa-star" style="font-size: 3rem; color: var(--yellow); margin-bottom: 1rem;"></i>
                                <p>No reviews received yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="reviews-list">
                                <?php foreach ($recentReviews as $review): ?>
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-name">
                                                <?php echo htmlspecialchars($review['student_name']); ?>
                                            </div>
                                            <div class="review-date">
                                                <?php 
                                                    $date = new DateTime($review['created_at']);
                                                    echo $date->format('M d, Y'); 
                                                ?>
                                            </div>
                                        </div>
                                        <div class="review-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?php echo ($i <= $review['rating']) ? 'filled' : ''; ?>">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="review-text">
                                            <?php echo htmlspecialchars($review['review_text']); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Features Section -->
        <section class="dashboard-features">
            <h2>Your Counseling Toolkit</h2>
            <div class="features">
                <div class="feature-item">
                    <img src="../../../assets/images/manage_appointments.png" alt="Manage Appointments" />
                    <h3>Manage Appointments</h3>
                    <p>View, approve, or reschedule appointments with students in need of guidance.</p>
                </div>
                <div class="feature-item">
                    <img src="../../../assets/images/messages.png" alt="Messages" />
                    <h3>Message Students</h3>
                    <p>Communicate directly with students and provide personalized guidance.</p>
                </div>
                <div class="feature-item">
                    <img src="../../../assets/images/reviews.png" alt="Reviews" />
                    <h3>Track Feedback</h3>
                    <p>Monitor your performance through student reviews to improve your services.</p>
                </div>
                <div class="feature-item">
                    <img src="../../../assets/images/tools.png" alt="Tools" />
                    <h3>Access Resources</h3>
                    <p>Use our specialized tools to deliver effective counseling and stress management.</p>
                </div>
            </div>
        </section>
    </div>

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
            <li><a href="../../views/counselling/counselor_dashboard.php">Dashboard</a></li>
            <li><a href="../../controller/AppointmentController.php?action=showPendingAppointments">Appointments</a></li>
            <li><a href="../views/messages.php">Messages</a></li>
            <li><a href="../views/reviews.php">Reviews</a></li>
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

    <!-- Chart.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <!-- Initialize Charts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart data for appointment trends
            const monthlyStats = <?php 
                // Format data for chart
                $labels = [];
                $pending = [];
                $accepted = [];
                $denied = [];
                
                // Default to last 6 months if no data
                if (empty($monthlyStats)) {
                    for ($i = 5; $i >= 0; $i--) {
                        $month = date('Y-m', strtotime("-$i months"));
                        $monthName = date('M Y', strtotime("-$i months"));
                        $labels[] = $monthName;
                        $pending[] = 0;
                        $accepted[] = 0;
                        $denied[] = 0;
                    }
                } else {
                    foreach ($monthlyStats as $stat) {
                        $monthName = date('M Y', strtotime($stat['month'] . '-01'));
                        $labels[] = $monthName;
                        $pending[] = (int)$stat['pending'];
                        $accepted[] = (int)$stat['accepted'];
                        $denied[] = (int)$stat['denied'];
                    }
                }
                
                $chartData = [
                    'labels' => $labels,
                    'pending' => $pending,
                    'accepted' => $accepted,
                    'denied' => $denied
                ];
                
                echo json_encode($chartData);
            ?>;
            
            // Colors
            const colors = {
                primary: '#009f77',
                secondary: '#fa8128',
                yellow: '#f1c40f',
                green: '#2ecc71',
                red: '#e74c3c'
            };
            
            // Appointment Trend Chart
            const ctx = document.getElementById('appointmentTrendChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyStats.labels,
                    datasets: [
                        {
                            label: 'Pending',
                            data: monthlyStats.pending,
                            backgroundColor: colors.yellow,
                            borderWidth: 0,
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Accepted',
                            data: monthlyStats.accepted,
                            backgroundColor: colors.green,
                            borderWidth: 0,
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Denied',
                            data: monthlyStats.denied,
                            backgroundColor: colors.red,
                            borderWidth: 0,
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'white',
                            titleColor: '#333',
                            bodyColor: '#666',
                            borderColor: '#ddd',
                            borderWidth: 1,
                            bodyFont: {
                                family: "'Poppins', sans-serif"
                            },
                            titleFont: {
                                family: "'Poppins', sans-serif",
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2],
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    family: "'Poppins', sans-serif"
                                },
                                precision: 0
                            }
                        }
                    }
                }
            });
            
            // Get Started Button Action
            const getStartedBtn = document.querySelector('.get-started-btn');
            if (getStartedBtn) {
                getStartedBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = this.getAttribute('href');
                });
            }
        });
    </script>
  </body>
</html>