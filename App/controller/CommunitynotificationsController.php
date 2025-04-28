<?php
require_once '../models/CommunitynotificationsModel.php';

session_start();

$notificationModel = new Notification();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'sendNotification':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = $_POST['post_id'] ?? null;
            $reason = $_POST['reason'] ?? null;
            
            if ($postId && $reason) {
                // Get post info to get the user_id
                $postInfo = $notificationModel->getPostInfo($postId);
                
                if ($postInfo) {
                    $success = $notificationModel->sendNotification($postInfo['user_id'], $postId, $reason);
                    
                    if ($success) {
                        header("Location: ../views/CommmunityAdmin_notifications.php?status=success");
                    } else {
                        header("Location: ../views/CommmunityAdmin_notifications.php?status=fail");
                    }
                } else {
                    header("Location: ../views/CommmunityAdmin_notifications.php?status=fail");
                }
            } else {
                header("Location: ../views/CommmunityAdmin_notifications.php?status=invalid");
            }
        }
        break;
        
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notificationId = $_POST['id'] ?? null;
            
            if ($notificationId) {
                $success = $notificationModel->deleteNotification($notificationId);
                
                if ($success) {
                    header("Location: ../views/CommmunityAdmin_notifications.php?status=deleted");
                } else {
                    header("Location: ../views/CommmunityAdmin_notifications.php?status=deletefail");
                }
            }
        }
        break;
        
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $notificationId = $_POST['notification_id'] ?? null;
                $reason = $_POST['reason'] ?? null;
                $title = $_POST['title'] ?? null;
        
                if ($notificationId && $reason && $title) {
                    $success = $notificationModel->updateNotification($notificationId, $reason, $title);
                    if ($success) {
                        header("Location: ../views/CommmunityAdmin_notifications.php?status=updated");
                    } else {
                        header("Location: ../views/CommmunityAdmin_notifications.php?status=updatefail");
                    }
                }
            }
            break;
        
        
    default:
        // No action or invalid action
        break;
}
?>