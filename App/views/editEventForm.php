<?php
require_once '../models/CommunityEventsModel.php';

$event = new Event();

if (!isset($_GET['id'])) {
    header("Location: CommunityAdminEvents.php");
    exit();
}

$eventId = $_GET['id'];
$events = $event->fetchAllEvents();
$selectedEvent = null;

foreach ($events as $e) {
    if ($e['event_id'] == $eventId) {
        $selectedEvent = $e;
        break;
    }
}

if (!$selectedEvent) {
    echo "Event not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Event</title>
  <link rel="stylesheet" href="../../assets/css/Edit_community.css" />
  <link rel="stylesheet" href="../../assets/css/edit_post.css">
</head>
<body>
<main class="create-whole-container">
    <div class="header">
        <div class="header-right">
            <button class="add-post-btn" onclick="window.location.href='CommunityAdminEvents.php';">Back to Community Events </button>
            <br><br><h1>Update Community Event</h1>
            <hr>
        </div>
    </div>

  <form action="../controller/CommunityEventsController.php?action=updateEvent" method="POST" class="notification-form">
    <input type="hidden" name="event_id" value="<?= htmlspecialchars($selectedEvent['event_id']) ?>">

    <label for="title">Event Title:</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($selectedEvent['title']) ?>" required>

    <label for="date">Event Date & Time:</label>
    <input type="datetime-local" id="date" name="date" min="<?= date('Y-m-d\TH:i', strtotime($selectedEvent['date'])) ?>" required>


    <label for="link">Platform Link:</label>
    <input type="url" id="link" name="link" value="<?= htmlspecialchars($selectedEvent['link']) ?>" required>

    <label for="description">Event Description:</label>
    <textarea id="description" name="description" required><?= htmlspecialchars($selectedEvent['description']) ?></textarea>

    <label for="category">Category:</label>
    <input type="text" id="category" name="category" value="<?= htmlspecialchars($selectedEvent['category']) ?>" required>

    <input type="submit" value="Update Event" class="add-post-btn">
  </form>
</main>
</body>
</html>
