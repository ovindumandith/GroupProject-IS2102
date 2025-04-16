<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Comment</title>
    <link rel="stylesheet" href="../../assets/css/edit_comment.css">
</head>
<body>
<div class="edit-container">
        <h2>Edit Comment</h2>
        <form method="POST" action="/GroupProject-IS2102/App/controller/EditCommentController.php">
            <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
            <textarea name="comment_text" required><?= htmlspecialchars($comment['comment_text']) ?></textarea>
            <button type="submit">Save Changes</button>
            <a href="/GroupProject-IS2102/App/views/comment_post.php?post_id=<?= $comment['post_id'] ?>">Cancel</a>
        </form>
    </div>
</body>
</html>