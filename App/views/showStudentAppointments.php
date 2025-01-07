<!-- showAppointments.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
</head>
<body>

    <h1>Your Pending Appointments</h1>

    <?php if (!empty($appointments)): ?>
        <table>
            <tr>
                <th>Appointment Date</th>
                <th>Counselor</th>
                <th>Topic</th>
                <th>Status</th>
            </tr>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['counselor_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['topic']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have no pending appointments.</p>
    <?php endif; ?>

    <a href="../../views/profile.php">Back to Profile</a>

</body>
</html>
