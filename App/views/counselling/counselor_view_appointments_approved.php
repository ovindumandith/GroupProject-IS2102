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
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #009f77;
            text-align: center;
            margin: 20px 0;
            font-size: 28px;
        }

        .nav-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 10px;
        }

        .nav-tab {
            padding: 8px 16px;
            background-color: #f1f1f1;
            border-radius: 5px;
            text-decoration: none;
            color: #555;
            font-weight: 500;
        }

        .nav-tab.active {
            background-color: #4CAF50;
            color: white;
        }

        .nav-tab:hover {
            background-color: #e0e0e0;
        }

        .nav-tab.active:hover {
            background-color: #3d9140;
        }

        .table-container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
            font-weight: 600;
            color: #009f77;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            background-color: #e8f5e9;
            color: #4CAF50;
            font-weight: 500;
        }

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #666;
        }

        .date-format {
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
            
            .nav-tabs {
                flex-wrap: wrap;
            }
        }
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td data-label="ID"><?= htmlspecialchars($appointment['id']) ?></td>
                        <td data-label="Student ID"><?= htmlspecialchars($appointment['student_id']) ?></td>
                        <td data-label="Date & Time" class="date-format"><?= date('M d, Y - h:i A', strtotime($appointment['appointment_date'])) ?></td>
                        <td data-label="Topic"><?= htmlspecialchars($appointment['topic']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($appointment['email']) ?></td>
                        <td data-label="Phone"><?= htmlspecialchars($appointment['phone']) ?></td>
                        <td data-label="Status"><span class="status-badge approve-badge"><?= htmlspecialchars($appointment['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <style>
        .appointment-table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .appointment-table th, .appointment-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .appointment-table th {
            background-color: #009f77;
            color: white;
        }
        
        .appointment-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .approve-badge {
            background-color: #e8f5e9;
            color: #4CAF50;
        }
        
        .date-format {
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            .appointment-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
<?php else: ?>

                <div class="empty-message">
                    <p>No approved appointments at the moment.</p>
                </div>
            <?php endif; ?>
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