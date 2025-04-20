<?php
require_once '../models/CommunitynotificationsModel.php';

if (isset($_GET['action']) && $_GET['action'] == 'sendNotification') {
    // Get data from form
    $userId = $_POST['user_id'];
    $postId = $_POST['post_id'];
    $title = $_POST['title'];
    $reason = $_POST['reason'];

    $notification = new Notification();

    // Send notification and delete post
    $notificationSent = $notification->sendNotification($userId, $postId, $title, $reason);

    if ($notificationSent) {
        // Redirect with success message
        header("Location: ../views/CommmunityAdmin_notifications.php?status=success");
    } else {
        header("Location: ../views/CommmunityAdmin_notifications.php?status=fail");
    }
    exit;
}
?>
