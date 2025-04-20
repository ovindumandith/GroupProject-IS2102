<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start(); // Start session if not already started
}

// Redirect to login if not logged in as a counselor
if (!isset($_SESSION['counselor'])) {
  header('Location: ../../views/counselor_login.php');
  exit();
}

// Function to format date and time for better readability
function formatDateTime($dateTimeString) {
  $dateTime = new DateTime($dateTimeString);
  return $dateTime->format('M d, Y - h:i A');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Appointments | RelaxU</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/counselor_dashboard.css" type="text/css" />
  <link rel="stylesheet" href="../../assets/css/counselor_view_appointments.css" type="text/css" />
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
        <li><a href="../views/counselling/counselor_dashboard.php"> Dashboard</a></li>
        <li class="services">
          <a href="#"></i> Appointments </a>
          <ul class="dropdown">
            <li><a href="../controller/AppointmentController.php?action=showPendingAppointments">Pending</a></li>
            <li><a href="../controller/AppointmentController.php?action=showApprovedAppointments">Approved</a></li>
            <li><a href="../controller/AppointmentController.php?action=showDeniedAppointments">Denied</a></li>
          </ul>
        </li>
        <li><a href="../views/messages.php"> Messages</a></li>
        <li><a href="../views/reviews.php"> Reviews</a></li>
      </ul>
    </nav>
    <div class="auth-buttons">
      <!-- Profile button form -->
<a href="/GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile" class="login" style="display: inline-block; text-decoration: none; background-color: #fa8128; color: white; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; margin-left: 10px; font-size: 1rem; transition: background-color 0.3s ease;">
    <b>Profile</b>
</a>
      <!-- Logout button form -->
      <form action="../../util/counselor_logout.php" method="POST" style="display: inline;">
        <button type="submit" class="login"> <b>Log Out</b></button>
      </form>
    </div>
  </header>

  <div class="container">
<div class="page-header" style="margin: 0 auto; text-align: center; width: fit-content;">
  <h1> Pending Appointments</h1>
</div>


    <!-- Notification Area -->
    <div class="notification-area">
      <?php if (isset($_SESSION['status_update_success'])): ?>
        <div class="toast toast-success">
          <i class="fas fa-check-circle"></i>
          <span><?= htmlspecialchars($_SESSION['status_update_success']) ?></span>
        </div>
        <?php unset($_SESSION['status_update_success']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['status_update_error'])): ?>
        <div class="toast toast-error">
          <i class="fas fa-exclamation-circle"></i>
          <span><?= htmlspecialchars($_SESSION['status_update_error']) ?></span>
        </div>
        <?php unset($_SESSION['status_update_error']); ?>
      <?php endif; ?>
    </div>

    <div class="card">
      <div class="card-header">
        <span>Student Appointment Requests</span>
        <span class="badge badge-pending">
          <span class="badge-circle circle-pending"></span>
          Pending: <?= !empty($appointments) ? count($appointments) : 0 ?>
        </span>
      </div>
      
      <?php if (!empty($appointments)): ?>
        <div style="overflow-x: auto;">
          <table class="appointment-table">
            <thead>
              <tr>
                <th>Student</th>
                <th>Topic</th>
                <th>Date & Time</th>
                <th>Contact</th>
                <th>Requested</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($appointments as $appointment): 
                // Get first letter of student ID for avatar
                $studentInitial = substr($appointment['student_id'], 0, 1);
              ?>
                <tr>
                  <td>
                    <div class="student-info">
                      <div class="student-avatar"><?= strtoupper($studentInitial) ?></div>
                      <div>
                        Student #<?= htmlspecialchars($appointment['student_id']) ?>
                        <div style="font-size: 0.8rem; color: var(--gray-600);">ID: <?= htmlspecialchars($appointment['id']) ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="truncate" title="<?= htmlspecialchars($appointment['topic']) ?>">
                    <?= htmlspecialchars($appointment['topic']) ?>
                  </td>
                  <td><?= formatDateTime($appointment['appointment_date']) ?></td>
                  <td>
                    <div><?= htmlspecialchars($appointment['email']) ?></div>
                    <div style="font-size: 0.85rem;"><?= htmlspecialchars($appointment['phone']) ?></div>
                  </td>
                  <td><?= formatDateTime($appointment['created_at']) ?></td>
                  <td class="actions-cell">
                    <form method="POST" action="../../App/controller/AppointmentController.php?action=updateAppointmentStatus" style="display:inline;">
                      <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>">
                      <button type="submit" name="status" value="Accepted" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> Accept
                      </button>
                    </form>
                    <form method="POST" action="../../App/controller/AppointmentController.php?action=updateAppointmentStatus" style="display:inline;">
                      <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>">
                      <button type="submit" name="status" value="Denied" class="btn btn-sm btn-danger">
                        <i class="fas fa-times"></i> Reject
                      </button>
                    </form>
                    <a href="/GroupProject-IS2102/App/controller/AppointmentController.php?action=viewStudentStressTrend&student_id=<?= htmlspecialchars($appointment['student_id']) ?>&appointment_id=<?= htmlspecialchars($appointment['id']) ?>" 
                       class="btn btn-sm" 
                       style="background-color: #7795f8; color: white; transition: all 0.3s ease;">
                      <i class="fas fa-chart-line"></i> Stress Data
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-calendar-check"></i>
          <p>No pending appointments at the moment.</p>
          <a href="../views/counselor_dashboard.php" class="btn btn-info">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

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
          <li><a href="../views/counselor_dashboard.php">Dashboard</a></li>
          <li><a href="../views/manage_appointments.php">Appointments</a></li>
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

  <script>
    // Auto-hide toast messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
      const toasts = document.querySelectorAll('.toast');
      toasts.forEach(toast => {
        setTimeout(() => {
          toast.style.opacity = '0';
          setTimeout(() => {
            toast.style.display = 'none';
          }, 300);
        }, 5000);
      });
    });
  </script>
</body>
</html>
