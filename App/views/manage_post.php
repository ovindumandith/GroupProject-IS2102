<?php
session_start(); // Start session to get user ID
require_once '../controller/ManagePostController.php';

// Get the logged-in user's ID from the session
$currentUserId = $_SESSION['user_id'] ?? null;

// Check if the user is logged in
if (!$currentUserId) {
    // Redirect to login page or show error
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Your Posts</title>
  <link rel="stylesheet" href="../../assets/css/manage_posts.css">
</head>
<body>
  <div class="create-whole-container">
    <!-- Header Section -->
    <div class="header">
      <div class="header-left">
        <img src="../../assets/images/managepost.png" alt="Header Image" class="header-image">
      </div>
      <div class="header-right">
        <button class="add-post-btn" onclick="window.location.href='../controller/CommunityController.php?action=list';">Back to Community</button>
        <br><br>
        <h1>Manage Your Posts</h1>
        <hr>
      </div>
    </div>

    <!-- Posts Section -->
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <?php if ($post['user_id'] == $currentUserId): ?> <!-- Only show posts by current user -->

                <div class="post">
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                
                    <?php if (!empty($post['image'])): ?>
                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-image">
                    <?php endif; ?>

                    <p><?= nl2br(htmlspecialchars($post['description'])) ?></p>
                    <small>Posted on: <?= htmlspecialchars($post['created_at']) ?></small>

                    <div class="post-actions">
                        <a href="../controller/UpdatePostController.php?action=edit&post_id=<?= htmlspecialchars($post['post_id']) ?>" class="icon-btn">
                            <img src="../../assets/images/Edit.png" alt="Edit" class="btn-image">
                        </a>

                        <form method="POST" action="">
                            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['post_id']) ?>">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($post['user_id']) ?>"> 
                            <button type="submit" name="delete" class="icon-btn">
                                <img src="../../assets/images/Delete.png" alt="Delete" class="btn-image">
                            </button>
                        </form>
                    </div>
                </div>

            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>
  </div> 
</body>
</html>
