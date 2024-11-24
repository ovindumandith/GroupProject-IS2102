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
  <title>Create Post</title>
  <link rel="stylesheet" href="../../../assets/css/create_post.css">
</head>
<body>
  <div class="create-whole-container">
    <!-- Header Section -->
    <div class="header">
      <div class="header-left">
        <img src="../../../assets/images/STRESS.png" alt="Header Image" class="header-image">
      </div>
      <div class="header-right">
        <h1>Manage Your Post</h1>
        <hr>
      </div>
    </div>

    <div class="create-post-container">
      <!-- Form for Adding Posts -->
      <form action="../../../controllers/CommunityController.php" method="post" enctype="multipart/form-data">
        <!-- Profile Section -->
        <div class="profile-section">
          <img src="../../../assets/images/Account.png" alt="Profile" class="profile-img">
          <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
          <input type="text" class="username-btn" value="<?= htmlspecialchars($_SESSION['user_id']) ?>" readonly>

          <button class="icon-btn" type="button">
            <img src="../../../assets/images/Edit.png" alt="Edit" class="btn-image">
          </button>
          <button class="icon-btn" type="button">
            <img src="../../../assets/images/Delete.png" alt="Delete" class="btn-image">
          </button>
        </div>

        <br>
      
        <!-- Add Title -->
        <input type="text" class="title-input" name="title" placeholder="Add Title" required>
      
        <!-- Image Upload Section -->
        <div class="image-upload">
          <div class="upload-box">
            <i class="icon upload-icon"></i>
            <img src="../../../assets/images/AddImage.png" alt="Add-image" class="btn-image2">
            <input type="file" id="upload-input" name="image" class="upload-input" accept="image/*">
          </div>
        </div>
      
        <!-- Description -->
        <textarea class="description-input" name="description" placeholder="Add Description" required></textarea>
      
        <!-- Add Post Button -->
        <button class="add-post-btn" type="submit">Add Post</button>
      </form>
    </div>

    <script src="../../../assets/js/create_post.js"></script>
  </div>
</body>
</html>
