<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($counselor['name']) ?>'s Profile</title>
</head>
<body>
    <h1><?= htmlspecialchars($counselor['name']) ?>'s Profile</h1>
    <img src="<?= htmlspecialchars($counselor['profile_image']) ?>" alt="<?= htmlspecialchars($counselor['name']) ?>'s Image">
    <p><strong>Type:</strong> <?= htmlspecialchars($counselor['type']) ?></p>
    <p><strong>Specialization:</strong> <?= htmlspecialchars($counselor['specialization']) ?: 'N/A' ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($counselor['description']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($counselor['email']) ?></p>

    <!-- Actions -->
    <a href="message_counselor.php?id=<?= $counselor['id'] ?>">Message Counselor</a>
    <a href="schedule_appointment.php?id=<?= $counselor['id'] ?>">Schedule Appointment</a>
</body>
</html>
