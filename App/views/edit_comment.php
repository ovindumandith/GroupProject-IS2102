<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Comment</title>
    <link rel="stylesheet" href="../../assets/css/comment_post.css">
</head>
<body>
    <div class="container">
        <h2>Edit Comment</h2>

        <?php if (isset($error)): ?>
            <div class="error" style="color: red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($comment): ?>
            <form action="../../App/controller/EditCommentController.php" method="POST">
                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['comment_id']) ?>">
                <textarea name="comment_text" required rows="4"><?= htmlspecialchars($comment['comment_text']) ?></textarea>
                <br><br>
                <button type="submit">Update Comment</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
