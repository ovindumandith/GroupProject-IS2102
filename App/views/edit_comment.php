<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Comment</title>
    <link rel="stylesheet" href="../../assets/css/edit_comment.css">
</head>
<body>
    <div class="create-whole-container">
        <h2>Edit Comment</h2>

        <?php if (isset($error)): ?>
            <div class="error" style="color: red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($comment): ?>
            <form action="../../App/controller/EditCommentController.php" method="POST">
                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['comment_id']) ?>">
                <textarea name="comment_text" required rows="4" class="textarea"><?= htmlspecialchars($comment['comment_text']) ?></textarea>
                <br><br>
                <button type="submit" class="add-post-btn ">Update Comment</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
