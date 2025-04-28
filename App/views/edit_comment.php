<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Comment</title>
    <link rel="stylesheet" href="../../assets/css/edit_comment.css">
    <link rel="stylesheet" href="../../assets/css/edit_post.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Comment Updated!',
            text: 'Your comment has been successfully updated.',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>

<main class="create-whole-container">
    <div class="header">
        <div class="header-left">
            <img src="../../assets/images/editpost.png" alt="Header Image" class="header-image">
        </div>
        <div class="header-right">
            <button class="add-post-btn" onclick="window.location.href='comment_post.php';">Back to Comment Section</button>
            <br><br><h1>Edit Comment</h1>
            <hr>
        </div>
    </div>

    <div class="edit-whole-container">
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
