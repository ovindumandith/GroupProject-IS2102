<?php
require_once '../models/CommunityEventsModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'addEvent') {
    $title = $_POST['title'] ?? null;
    $date = $_POST['date'] ?? null;
    $link = $_POST['link'] ?? null;
    $description = $_POST['description'] ?? null;
    $category = $_POST['category'] ?? null;

    if ($title && $date && $description && $category) {
        $event = new Event();
        $eventAdded = $event->addEvent($title, $date, $link, $description, $category);

        if ($eventAdded) {
            header("Location: ../views/CommunityAdminEvents.php?status=success");
        } else {
            header("Location: ../views/CommunityAdminEvents.php?status=fail");
        }
        exit;
    } else {
        header("Location: ../views/CommunityAdminEvents.php?status=invalid");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $eventId = $_POST['id'] ?? null;

    if ($eventId) {
        $event = new Event();
        $deleted = $event->deleteEvent($eventId);

        if ($deleted) {
            header("Location: ../views/CommunityAdminEvents.php?status=deleted");
        } else {
            header("Location: ../views/CommunityAdminEvents.php?status=deletefail");
        }
        exit;
    }
}

// Update Event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'updateEvent') {
    $eventId = $_POST['event_id'] ?? null;
    $title = $_POST['title'] ?? null;
    $date = $_POST['date'] ?? null;
    $link = $_POST['link'] ?? null;
    $description = $_POST['description'] ?? null;
    $category = $_POST['category'] ?? null;

    if ($eventId && $title && $date && $description && $category) {
        $event = new Event();
        $updated = $event->updateEvent($eventId, $title, $date, $link, $description, $category);

        if ($updated) {
            header("Location: ../views/CommunityAdminEvents.php?status=updated");
        } else {
            header("Location: ../views/CommunityAdminEvents.php?status=updatefail");
        }
    } else {
        header("Location: ../views/CommunityAdminEvents.php?status=invalid");
    }
}

?>
