<?php

require_once '../models/ScheduleEvent.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure proper JSON header
    header('Content-Type: application/json');
    error_reporting(E_ERROR | E_PARSE); // Suppress PHP warnings

    // Fetch input values
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';
    $startTime = $_POST['startTime'] ?? '';
    $endTime = $_POST['endTime'] ?? '';
    $eventId = $_POST['id'] ?? null;

    // Validate inputs
    $errors = [];

    if (empty($title)) $errors[] = 'Title is required.';
    if (empty($date)) $errors[] = 'Date is required.';
    if (empty($startTime)) $errors[] = 'Start time is required.';
    if (empty($endTime)) $errors[] = 'End time is required.';
    if (!empty($startTime) && !empty($endTime) && $startTime >= $endTime) {
        $errors[] = 'Start time must be less than end time.';
    }

    if (!empty($date) && strtotime($date) < time()) {
        $errors[] = 'The event date must be in the future.';
    }
    
    $event = new ScheduleEvent();
    if ($event->checkEventOverlap($date, $startTime, $endTime, $title, $eventId)) {
        echo json_encode(['errors' => ['An event with the same title already exists at this date and time.']]);
        exit();
    }
    
    
    // Handle validation errors
    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit();
    }

    $event = new ScheduleEvent();

    // Save or update event
    if ($eventId) {
        $success = $event->updateEvent($eventId, $title, $description, $date, $startTime, $endTime);
        $message = $success ? 'Event updated successfully!' : 'Failed to update the event.';
    } else {
        $success = $event->saveEvent($title, $description, $date, $startTime, $endTime);
        $message = $success ? 'Event saved successfully!' : 'Failed to save the event.';
    }

    echo json_encode(['message' => $message]);
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
