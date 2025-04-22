<?php
require_once '../models/CommunitynotificationsModel.php';

if (!isset($_GET['id'])) {
    header('Location: CommmunityAdmin_notifications.php?status=invalid');
    exit();
}

$notificationId = $_GET['id'];
$model = new Notification();
$notification = $model->fetchNotificationById($notificationId);

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
    <link rel="stylesheet" href="../../assets/css/CommunityAdmin_notifications.css">
</head>
<body>
    <main>
        <h2>Edit Notification</h2>
        <form action="../controller/CommunitynotificationsController.php?action=update" method="POST" class="notification-form">
            <input type="hidden" name="notification_id" value="<?= $notification['notification_id'] ?>">

            <label for="title">Post Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($notification['title']) ?>" required>

            <label for="reason">Reason:</label>
            <textarea id="reason" name="reason" rows="4" required><?= htmlspecialchars($notification['reason']) ?></textarea>

            <input type="submit" value="Update Notification" class="add-post-btn">
        </form>
    </main>
</body>
</html>
