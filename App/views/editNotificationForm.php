<?php
require_once '../models/CommunitynotificationsModel.php';

// Validate if ID is set
if (!isset($_GET['id'])) {
    header('Location: CommmunityAdmin_notifications.php?status=invalid');
    exit();
}

$notificationId = $_GET['id'];
$model = new Notification();

// ❗ FIX: Correct method name
$notification = $model->getNotificationById($notificationId);

if (!$notification) {
    header('Location: CommmunityAdmin_notifications.php?status=notfound');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Notification</title>
    <link rel="stylesheet" href="../../assets/css/Edit_community.css">
    <link rel="stylesheet" href="../../assets/css/edit_post.css">
</head>
<body>
    <main class="create-whole-container">
    <div class="header">
        <div class="header-left">
            <img src="../../assets/images/editpost.png" alt="Header Image" class="header-image">
        </div>
        <div class="header-right">
            <button class="add-post-btn" onclick="window.location.href='CommmunityAdmin_notifications.php';">Back to Notifications </button>
            <br><br><h1>Update Notification</h1>
            <hr>
        </div>
    </div>

        <form action="../controller/CommunitynotificationsController.php?action=update" method="POST" class="notification-form">
            <!-- Hidden field to pass notification_id -->
            <input type="hidden" name="notification_id" value="<?= htmlspecialchars($notification['notification_id']) ?>">
            
            <input type="hidden" id="title" name="title" value="<?= htmlspecialchars($notification['title']) ?>" required>
            <label for="reason">Reason:</label>
            <textarea id="reason" name="reason" rows="4" required><?= htmlspecialchars($notification['reason']) ?></textarea>

            <input type="submit" value="Update Notification" class="add-post-btn">
        </form>
    </main>
</body>
</html>
