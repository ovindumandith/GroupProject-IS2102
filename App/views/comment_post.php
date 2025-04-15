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
require_once __DIR__ . '/../models/PostsModel.php';
require_once __DIR__ . '/../../config/config.php';

// Initialize models
$commentModel = new CommentModel();
$postsModel = new PostsModel();

// Get post ID from URL or session
$postId = $_GET['post_id'] ?? $_SESSION['current_post_id'] ?? null;

// Validate post ID
if (!$postId || !is_numeric($postId)) {
    die("Invalid post ID. Please go back and select a valid post.");
}

// Get post details
$post = $postsModel->getPostById($postId);
if (!$post) {
    die("Post not found.");
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $commentText = trim($_POST['comment_text']);
    
    if (!empty($commentText)) {
        $success = $commentModel->addComment($postId, $_SESSION['user_id'], $commentText);
        if ($success) {
            header("Location: comment_post.php?post_id=" . $postId);
            exit();
        } else {
            $error = "Failed to add comment. Please try again.";
        }
    } else {
        $error = "Comment cannot be empty.";
    }
}

// Get comments for this post
$comments = $commentModel->getCommentsByPostId($postId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comments for: <?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="../../assets/css/comment_post.css">
</head>
<body>
<main class="create-whole-container">
        <div class="header">
            <div class="header-left">
                <img src="../../assets/images/STRESS.png" alt="Header Image" class="header-image">
            </div>
            
            <div class="header-right">
            <button class="add-post-btn" onclick="window.location.href='../controller/CommunityController.php?action=list';">Back to Community</button>
            <br><br>
                <h1>Comment Section</h1>
                <hr>
            </div>
        </div>

    <div class="container">
        <h2>Comments for: <?= htmlspecialchars($post['title']) ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="comments-container">
            <h3>Comments (<?= $commentModel->getCommentCount($postId) ?>)</h3>
            
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-section">
                        <div class="comment-header">
                            <span class="user-id"><?= htmlspecialchars($comment['username']) ?></span><br>
                        </div>
                        <div class="comment-text">
                            <?= nl2br(htmlspecialchars($comment['comment_text'])) ?>
                        </div>
                        <span class="date"><?= date('M j, Y g:i a', strtotime($comment['created_at'])) ?></span><br>
                    </div><br>
                    <a href="/GroupProject-IS2102/App/controller/CommentController.php?id=<?= $comment['comment_id'] ?>" 
                    onclick="return confirm('Delete this comment?')"class="btn">Delete</a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>
        
        <div class="comments-container">
            <form method="POST">
                <textarea name="comment_text" class="description-input" placeholder="Add your comment..." required></textarea><br>
                <button type="submit" class="comment-btn">Add Comment</button>
            </form>
        </div>
    </div>
</body>
</html>