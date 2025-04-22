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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $notiId = $_POST['id'] ?? null;

    if ($notiId) {
        $notification = new Notification();
        $deleted = $notification->deleteNoti($notiId);

        if ($deleted) {
            header("Location: ../views/CommmunityAdmin_notifications.php?status=deleted");
        } else {
            header("Location: ../views/CommmunityAdmin_notifications.php?status=deletefail");
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update') {
    $notificationId = $_POST['notification_id'] ?? null;
    $title = $_POST['title'] ?? null;
    $reason = $_POST['reason'] ?? null;

    if ($notificationId && $title && $reason) {
        $notification = new Notification();
        $updated = $notification->updateNotification($notificationId, $title, $reason);

        if ($updated) {
            header("Location: ../views/CommmunityAdmin_notifications.php?status=updated");
        } else {
            header("Location: ../views/CommmunityAdmin_notifications.php?status=updatefail");
        }
        exit;
    }
}

?>
