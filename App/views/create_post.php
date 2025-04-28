<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
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
    <title>Create Your Post</title>
    <link rel="stylesheet" href="../../assets/css/create_post.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <main class="create-whole-container">
        <div class="header">
            <div class="header-left">
                <img src="../../assets/images/STRESS.png" alt="Header Image" class="header-image">
            </div>
            
            <div class="header-right">
            <button class="add-post-btn" onclick="window.location.href='<?= $controllerPath ?>';">
            Back to Community
            </button>
         <br><br>
                <h1>Create Your Post</h1>
                <hr>
            </div>
        </div>

        <div class="create-post-container">
            <form action="../controller/CreateController.php?action=addPost" method="POST" enctype="multipart/form-data">
                <div class="profile-section">
                    <img src="../../assets/images/Account.png" alt="Profile" class="profile-img">   
                    <!-- Automatically populate user_id from session -->
            <input type="text" class="username-btn" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" readonly>
       </div>
                <br/><br/>
                <input type="text" class="title-input" name="title" placeholder="Title" required><br/>
                <div class="upload-box">
                <label for="file">Uploads (optional)</label><br><br>
                <input type="file" name="image"><br><br>
                </div>

        
                <br/>
                <textarea class="description-input" name="description" placeholder="Description" required></textarea>
                <button class="add-post-btn" type="submit">Add Post</button>
            </form>

        </div>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
<script>
    Swal.fire({
        title: 'Success!',
        text: 'Post created successfully!',
        icon: 'success',
        confirmButtonColor: '#28a745',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (window.history.replaceState) {
            const url = window.location.href.split('?')[0];
            window.history.replaceState(null, null, url);
        }
    });
</script>
<?php endif; ?>


    </main>
</body>
</html>
