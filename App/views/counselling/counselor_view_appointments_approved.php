<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in as a counselor
if (!isset($_SESSION['counselor'])) {
    header('Location: ../../views/counselor_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Appointments | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/header_footer.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/counselor_dashboard.css" type="text/css" />
    <link rel="stylesheet" href="../../assets/css/counselor_view_appointments_approved.css" type="text/css" />
    <style>
        
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
          <li><a href="../views/counselor_dashboard.php">Dashboard</a></li>
          <li class="services">
            <a href="#">Appointments</a>
            <ul class="dropdown">
              <li><a href="../../controller/AppointmentController.php?action=showPendingAppointments">Pending</a></li>
              <li><a href="../../controller/AppointmentController.php?action=showApprovedAppointments">Approved</a></li>
              <li><a href="../../controller/AppointmentController.php?action=showDeniedAppointments">Denied</a></li>
            </ul>
          </li>
          <li><a href="../views/messages.php">Messages</a></li>
          <li><a href="../views/reviews.php">Reviews</a></li>
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

    <div class="container">
        <h1>Approved Appointments</h1>
        <div class="table-container">
<?php if (!empty($appointments)): ?>
    <div style="overflow-x: auto;">
        <table class="appointment-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Date & Time</th>
                    <th>Topic</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['id']) ?></td>
                        <td><?= htmlspecialchars($appointment['student_id']) ?></td>
                        <td class="date-format"><?= date('M d, Y - h:i A', strtotime($appointment['appointment_date'])) ?></td>
                        <td><?= htmlspecialchars($appointment['topic']) ?></td>
                        <td><?= htmlspecialchars($appointment['email']) ?></td>
                        <td><?= htmlspecialchars($appointment['phone']) ?></td>
                        <td><span class="status-badge"><?= htmlspecialchars($appointment['status']) ?></span></td>
                        <td>
<button type="button" class="reschedule-btn" onclick="openRescheduleModal(<?= htmlspecialchars($appointment['id']) ?>)">
    Reschedule
</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Reschedule Modal -->
    <div id="rescheduleModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRescheduleModal()">&times;</span>
            <h2>Reschedule Appointment</h2>
            <p>Please select a new date and time for this appointment:</p>
            
            <form id="rescheduleForm" action="../controller/AppointmentController.php?action=rescheduleAppointment" method="POST">
                <input type="hidden" id="appointmentId" name="appointment_id" value="">
                
                <div class="form-group">
                    <label for="newDate">New Date and Time:</label>
                    <input type="datetime-local" id="newDate" name="new_date" required>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="action-btn accept-btn">Confirm Reschedule</button>
                    <button type="button" class="action-btn reject-btn" onclick="closeRescheduleModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Get the modal
        const modal = document.getElementById("rescheduleModal");
        
        // Function to open the modal and set the appointment ID
        function openRescheduleModal(appointmentId) {
            document.getElementById("appointmentId").value = appointmentId;
            
            // Set minimum date to today
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const hours = String(today.getHours()).padStart(2, '0');
            const minutes = String(today.getMinutes()).padStart(2, '0');
            
            const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            document.getElementById("newDate").min = minDateTime;
            
            modal.style.display = "block";
        }
        
        // Function to close the modal
        function closeRescheduleModal() {
            modal.style.display = "none";
        }
        
        // Close the modal if user clicks outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                closeRescheduleModal();
            }
        }
    </script>
<?php else: ?>
    <p style="text-align: center;">No approved appointments at the moment.</p>
<?php endif; ?>

<!-- Display toast messages -->
<?php if (isset($_SESSION['reschedule_success'])): ?>
    <div class="toast-success">
        <?= htmlspecialchars($_SESSION['reschedule_success']) ?>
    </div>
    <?php unset($_SESSION['reschedule_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['reschedule_error'])): ?>
    <div class="toast-error">
        <?= htmlspecialchars($_SESSION['reschedule_error']) ?>
    </div>
    <?php unset($_SESSION['reschedule_error']); ?>
<?php endif; ?>

<script>
    // Auto-hide toast messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast-success, .toast-error');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 500);
            }, 5000);
        });
    });
</script>


        </div>
    </div>

    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">
          <h1>RelaxU</h1>
          <p>Your mental health, your priority.</p>
          <img
            id="footer-logo"
            src="../../assets/images/logo.jpg"
            alt="RelaxU Logo"
          />
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
</body>
</html>