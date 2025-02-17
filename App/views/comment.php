<?php
require_once '../models/PostsModel.php';

// Validate and fetch post_id
if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    echo "<script>alert('Invalid post ID.'); window.location = '../views/manage_post.php';</script>";
    exit;
}

$postId = intval($_GET['post_id']);
$model = new PostsModel();
$post = $model->getPostById($postId);

if (!$post) {
    echo "<script>alert('Post not found.'); window.location = '../views/manage_post.php';</script>";
    exit;
}

// Debug: Ensure $post contains the correct data
// echo "<pre>"; print_r($post); echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../../assets/css/comment.css">
    <link rel="stylesheet" href="../../assets/js/comment.js">
</head>
<body>
  <div class="post">
    <h2>We all face challenges...</h2>
    <p>This is a sample community blog post...</p>
    <div class="post-actions">
      <span>❤️ 50 Likes</span>
      <button id="toggleComments">💬 3 Comments</button>
    </div>
    <div id="commentSection" class="hidden">
      <h3>Comments</h3>
      <ul class="comments">
        <li><strong>Julian:</strong> Thank you for sharing this! It’s inspiring.</li>
        <li><strong>Alice:</strong> I can relate to this so much. Great read!</li>
        <li><strong>Mark:</strong> This made my day. Looking forward to more posts like this!</li>
      </ul>
      <textarea id="newComment" placeholder="Write your comment..."></textarea>
      <button id="addComment">Add Comment</button>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>



