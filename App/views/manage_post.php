<?php
session_start();
require_once '../controller/ManagePostController.php';

$currentUserId = $_SESSION['user_id'] ?? null;

if (!$currentUserId) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'] ?? 'student';

if ($role === 'CommunityAdmin') {
    $controllerPath = '../controller/CommunityAdminController.php?action=list';
} else {
    $controllerPath = '../controller/CommunityController.php?action=list';
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
  <main class="create-whole-container">
    <!-- Header Section -->
    <div class="header">
      <div class="header-left">
        <img src="../../assets/images/managepost.png" alt="Header Image" class="header-image">
      </div>
      <div class="header-right">
        <button class="add-post-btn" onclick="window.location.href='<?= $controllerPath ?>';">
            Back to Community
        </button>
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
                
                    <?php
                        $baseUrl = '/GroupProject-IS2102/App/';
                    ?>

                    <?php if (!empty($post['image'])): ?>
                        <img src="<?= htmlspecialchars($baseUrl . $post['image']) ?>"
                        alt="<?= htmlspecialchars($post['title']) ?>" class="post-image">
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
        <p>No more posts found.</p>
    <?php endif; ?>
    </main> 
</body>
</html>
