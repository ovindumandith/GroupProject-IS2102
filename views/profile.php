<?php
session_start();
require '../config/config.php'; // Ensure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Create a new instance of Database
$db = new Database();
$conn = $db->connect(); // Call the connect method to establish the connection

// Check if the connection was established
if (!$conn) {
    die("Database connection failed.");
}

// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, phone, year, role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bindParam(1, $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If the user doesn't exist, handle it
if (!$user) {
    echo "User not found.";
    exit();
}

// Process the form submission to update user information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $year = $_POST['year'];

    $update_query = "UPDATE users SET username = ?, email = ?, phone = ?, year = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(1, $username);
    $update_stmt->bindParam(2, $email);
    $update_stmt->bindParam(3, $phone);
    $update_stmt->bindParam(4, $year);
    $update_stmt->bindParam(5, $user_id, PDO::PARAM_INT);
    
    if ($update_stmt->execute()) {
        echo "Profile updated successfully!";
        header('Location: profile.php'); // Redirect to the profile page after update
        exit();
    } else {
        echo "Error updating profile: " . $conn->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/profile.css" type="text/css">
</head>
<body>
    <header>
        <h1>User Profile</h1>
        <nav>
            <a href="home.php">Home</a>
            <a href="logout.php">Log Out</a>
        </nav>
    </header>
    
    <form method="POST" action="profile.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

        <label for="year">Year:</label>
        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($user['year']); ?>" required>

        <input type="submit" value="Update Profile">
    </form>
</body>
</html>
