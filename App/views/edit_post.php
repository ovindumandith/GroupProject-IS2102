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
    <link rel="stylesheet" href="../../assets/css/edit_post.css">
</head>
<body>
<main class="create-whole-container">
        <div class="header">
            <div class="header-left">
                <img src="../../assets/images/editpost.png" alt="Header Image" class="header-image">
            </div>
            <div class="header-right">
            <button class="add-post-btn" onclick="window.location.href='../controller/CommunityController.php?action=list';">Back to Community</button>
                <br><br><h1>Edit Your Post</h1>
                <hr>
            </div>
        </div>

    <?php if ($post): ?>
        <form action="../controller/UpdatePostController.php?action=update" method="POST" class="create-post-container">
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['post_id']) ?>">
            <label for="title">Title:</label>
            <input type="text" class="title-input" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            <br><br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" value="<?= htmlspecialchars($post['image']) ?>">
            <br/><br>
            <label for="description">Description:</label>
            <textarea class="description-input" id="description" name="description" rows="5" required><?= htmlspecialchars($post['description']) ?></textarea>   
            <br><br>
            <button class="add-post-btn" type="submit" name="update_post">Update Post</button>
        </form>
    <?php else: ?>
        <p>Post not found.</p>
    <?php endif; ?>

    </main>
</body>
</html>



