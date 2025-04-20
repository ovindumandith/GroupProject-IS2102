<?php
require_once '../models/CommunitynotificationsModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'sendNotification') {
    $userId = $_POST['user_id'] ?? null;
    $postId = $_POST['post_id'] ?? null;
    $title = $_POST['title'] ?? null;
    $reason = $_POST['reason'] ?? null;

    // Send notification and delete post
    if ($userId && $postId && $title && $reason) {
        $notification = new Notification();
        $notificationSent = $notification->sendNotification($userId, $postId, $title, $reason);

        if ($notificationSent) {
            // Redirect with success message
            header("Location: ../views/CommmunityAdmin_notifications.php?status=success");
        } else {
            header("Location: ../views/CommmunityAdmin_notifications.php?status=fail");
        }
        exit;
    } else {
        header("Location: ../views/CommmunityAdmin_notifications.php?status=invalid");
        exit;
    }
}
?>
