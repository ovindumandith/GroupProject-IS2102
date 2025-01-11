<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}
if (isset($_GET['counselor_id'])) {
    $counselorId = $_GET['counselor_id'];
} else {
    // Redirect or show an error if no counselor_id is provided
    die("Counselor ID is missing.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Schedule an Appointment</h2>
        <form action="../../app/controllers/AppointmentController.php" method="POST">
            <input type="hidden" name="counselor_id" value="<?= htmlspecialchars($counselorId) ?>">

            <label for="appointment_date">Appointment Date and Time:</label>
            <input type="datetime-local" id="appointment_date" name="appointment_date" required>

            <label for="topic">Topic of Interest or Discussion:</label>
            <input type="text" id="topic" name="topic" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Your Phone Number:</label>
            <input type="text" id="phone" name="phone" required>

            <button type="submit">📅 Schedule Appointment</button>
        </form>
    </div>
</body>
</html>
