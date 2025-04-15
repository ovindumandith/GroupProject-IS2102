<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Security check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=unauthorized');
    exit();
}

require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../controller/CommentController.php';
require_once __DIR__ . '/../../config/config.php';

// ✅ Create DB connection using your Database class
$database = new Database();
$db = $database->connect();

$postId = $_GET['post_id'] ?? 1;

$controller = new CommentController($db);
$comments = $controller->handleRequest($postId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comment Section</title>
    <link rel="stylesheet" href="../../assets/css/comment_post.css">
</head>
<body>
<main class="create-whole-container">
    <div class="header">
        <div class="header-left">
            <img src="../../assets/images/editpost.png" alt="Header Image" class="header-image">
        </div>
        <div class="header-right">
            <button class="add-post-btn" onclick="window.location.href='../../community.php';">Back to Community</button>
            <br><br><h1>Comment Section</h1>
            <hr>
        </div>
    </div>

    <div class="comment-section">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <strong><?= htmlspecialchars($comment['username'] ?? 'User') ?></strong>
                    <p><?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
                    <small><?= $comment['created_at'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($postId) ?>">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
        <textarea name="comment_text" placeholder="Add your comment..." required></textarea>
        <button type="submit">Add Comment</button>
    </form>
</main>
</body>
</html>
