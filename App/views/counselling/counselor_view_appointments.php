<?php
session_start();

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
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
</head>
<body>
    <h1>Pending Appointments</h1>

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
</body>
</html>
