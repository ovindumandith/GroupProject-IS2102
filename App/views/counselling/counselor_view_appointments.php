<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}

// Redirect to login if not logged in as a counselor
if (!isset($_SESSION['counselor'])) {
    header('Location: counselor_login.php');
    exit();
}


// Display success or error messages
if (isset($_SESSION['status_update_success'])) {
    echo "<div class='toast-success'>{$_SESSION['status_update_success']}</div>";
    unset($_SESSION['status_update_success']);
}
if (isset($_SESSION['status_update_error'])) {
    echo "<div class='toast-error'>{$_SESSION['status_update_error']}</div>";
    unset($_SESSION['status_update_error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Appointments</title>
    <style>

         table {
            width: 90%; /* Adjust width as needed */
            border-collapse: collapse;
            margin: 20px auto; /* Center the table horizontally and add vertical spacing */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add a shadow for better visuals */
}
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #009f77;
            color: white;
        }
        #appointment-heading {
            text-align: center;
            margin-top: 20px;
        }
        .action-btn {
            padding: 6px 12px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .accept-btn {
            background-color: #4CAF50;
        }
        .reject-btn {
            background-color: #f44336;
        }
        .toast-success {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .toast-error {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../../assets/css/header_footer.css"
      type="text/css"
    />
    <link rel="stylesheet" href="../../assets/css/counselor_dashboard.css" type="text/css" />
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
          <li><a href="../../controller/AppointmentController.php?action=showPendingAppointments">Appointments</a></li>

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



    <h1 id="appointment-heading">Pending Appointments</h1>

    <?php if (!empty($appointments)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Appointment Date</th>
                    <th>Topic</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['topic']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['email']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['phone']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['updated_at']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                        <td>
                            <form method="POST" action="../controllers/AppointmentController.php?action=updateAppointmentStatus" style="display: inline;">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                <input type="hidden" name="status" value="Accepted">
                                <button type="submit" class="action-btn accept-btn">Accept</button>
                            </form>
                            <form method="POST" action="../controllers/AppointmentController.php?action=updateAppointmentStatus" style="display: inline;">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="action-btn reject-btn">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending appointments at the moment.</p>
    <?php endif; ?>
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




