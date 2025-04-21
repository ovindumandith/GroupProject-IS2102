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
?>
