<?php

require_once '../models/ScheduleEvent.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch input values
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';
    $startTime = $_POST['startTime'] ?? '';
    $endTime = $_POST['endTime'] ?? '';
    $eventId = $_POST['id'] ?? null;  // Check if we are updating an existing event

    // Validate inputs
    $errors = [];

    // Check if title, date, startTime, and endTime are not empty
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }

    if (empty($date)) {
        $errors[] = 'Date is required.';
    }

    if (empty($startTime)) {
        $errors[] = 'Start time is required.';
    }

    if (empty($endTime)) {
        $errors[] = 'End time is required.';
    }

    // Validate start time is less than end time
    if (!empty($startTime) && !empty($endTime)) {
        if ($startTime >= $endTime) {
            $errors[] = 'Start time must be less than end time.';
        }
    }

    // If there are validation errors, set an error message and return
    if (!empty($errors)) {
        // Join errors into a single string, or you could keep them in an array
        $_SESSION['error_message'] = implode('<br>', $errors);
        // header('Location: ../views/schedule_event.php');
        exit();
    }

    // If there are no validation errors, proceed with saving or updating the event
    $event = new ScheduleEvent();

    if ($eventId) {
        // If the eventId is present, it's an update
        if ($event->updateEvent($eventId, $title, $description, $date, $startTime, $endTime)) {
            $_SESSION['success_message'] = 'Event updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to update the event.';
        }
    } else {
        // Otherwise, it's a new event
        if ($event->saveEvent($title, $description, $date, $startTime, $endTime)) {
            $_SESSION['success_message'] = 'Event saved successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to save the event.';
        }
    }

    // Redirect back to the events page
    header('Location: ../views/schedule_event.php');
    exit();
}

// In ScheduleEventController.php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $eventId = $_GET['id'] ?? null;

    if ($eventId) {
        $event = new ScheduleEvent();

        // Fetch the event by ID
        $eventData = $event->getEventById($eventId);

        if ($eventData) {
            // Send JSON response with event data
            header('Content-Type: application/json');
            echo json_encode($eventData);
        } else {
            // Send an error response if event not found
            http_response_code(404);
            echo json_encode(['error' => 'Event not found']);
        }
    } else {
        // Send error response if ID is not provided
        http_response_code(400);
        echo json_encode(['error' => 'Event ID is required']);
    }

    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $event = new ScheduleEvent();

    // Check if there's a search query in the URL
    if (isset($_GET['search'])) {
        $searchQuery = $_GET['search'];
        // Get filtered events based on the search query
        $events = $event->getEventsBySearch($searchQuery);
    } elseif (isset($_GET['date'])) {
        $date = $_GET['date'];
        // Get events filtered by date
        $events = $event->getEventsByDate($date);
    } else {
        // If no search query or date is provided, fetch all events
        $events = $event->getAllEvents();
    }

    // Return the events as JSON
    header('Content-Type: application/json');
    echo json_encode($events);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Decode the raw JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $eventId = $input['id'] ?? null;

    if ($eventId) {
        $event = new ScheduleEvent();
        if ($event->deleteEvent($eventId)) {
            echo json_encode(['status' => 'success', 'message' => 'Event deleted successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the event.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid request: event ID is required.']);
    }
    exit();
}
