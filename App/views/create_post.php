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
    <title>Create Your Post</title>
    <link rel="stylesheet" href="../../assets/css/create_post.css">
    <!--<script src="../../assets/js/create_post.js"></script>-->
</head>
<body>
    <main class="create-whole-container">
        <div class="header">
            <div class="header-left">
                <img src="../../assets/images/STRESS.png" alt="Header Image" class="header-image">
            </div>
            <div class="header-right">
                <h1>Create Your Post</h1>
                <hr>
            </div>
        </div>

        <div class="create-post-container">
            <form action="../controller/CreateController.php?action=addPost" method="POST" enctype="multipart/form-data">
                <div class="profile-section">
                    <img src="../../assets/images/Account.png" alt="Profile" class="profile-img">   
                    <input type="text" class="username-btn" name="user_id" placeholder="User ID" required>
                </div>
                <br/><br/>
                <input type="text" class="title-input" name="title" placeholder="Title" required><br/>
                <div class="upload-box">
                    <input type="file" name="image" accept="image/*">
                </div>
                <br/>
                <textarea class="description-input" name="description" placeholder="Description" required></textarea>
                <button class="add-post-btn" type="submit">Add Post</button>
            </form>

        </div>
    </main>
</body>
</html>