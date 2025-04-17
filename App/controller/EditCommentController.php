<?php
require_once __DIR__ . '/../models/EditCommentModel.php';
session_start();

$commentId = $_GET['comment_id'] ?? $_POST['comment_id'] ?? null;
$model = new CommentModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentText = $_POST['comment_text'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;

    if ($commentId && $commentText && $userId) {
        if ($model->userOwnsComment($commentId, $userId)) {
            if ($model->updateComment($commentId, $commentText)) {
                // If update is successful, display success message using JavaScript
                echo "<script>
                    alert('Comment updated successfully!');
                    window.location.href = '../views/comment_post.php?comment_id=$commentId'; // Redirect to edit page or another page
                </script>";
                exit();
            } else {
                $error = "Failed to update the comment.";
            }
        } else {
            $error = "You are not authorized to edit this comment.";
        }
    } else {
        $error = "Missing data.";
    }
}

// If GET or update failed, load the comment for the form
if ($commentId) {
    $comment = $model->getCommentById($commentId);
    if (!$comment) {
        $error = "Comment not found.";
    }
} else {
    $error = "Comment ID missing.";
}

include __DIR__ . '/../views/edit_comment.php';
