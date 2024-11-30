<?php
session_start();

// Debugging: Check session variables
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// Check if the counselor is logged in
if (!isset($_SESSION['counselor']) || empty($_SESSION['counselor']['id'])) {
    echo "Session invalid or not set. Redirecting to login.";
    header('Location: /GroupProject-IS2102/App/views/counselling/counselor_login.php'); // Redirect to login page
    exit();
}

// Include the CounselorModel to fetch profile data
require_once '../../models/CounselorModel.php';

// Instantiate the CounselorModel
$counselorModel = new CounselorModel();

// Get the counselor ID from the session
$counselorId = $_SESSION['counselor']['id'];

// Fetch counselor details using the model
$counselor = $counselorModel->getCounselorById($counselorId);

// Check if counselor data is valid
if (!$counselor) {
    echo "Counselor data not found. Please contact support.";
    exit();
}

// Extract counselor details
$profileImage = htmlspecialchars($counselor['profile_image'] ?? '/GroupProject-IS2102/assets/images/default_profile.png');
$name = htmlspecialchars($counselor['name'] ?? 'N/A');
$email = htmlspecialchars($counselor['email'] ?? 'N/A');
$type = htmlspecialchars($counselor['type'] ?? 'N/A');
$specialization = htmlspecialchars($counselor['specialization'] ?? 'Not specified.');
$description = htmlspecialchars($counselor['description'] ?? 'No description available.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Profile</title>
    <link rel="stylesheet" href="/GroupProject-IS2102/assets/css/style.css">
</head>
<body>
    <!-- Profile Page Content -->
</body>
</html>
