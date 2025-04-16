<?php
require_once '../models/ScheduleEvent.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure proper JSON header
    header('Content-Type: application/json');
    error_reporting(E_ERROR | E_PARSE); // Suppress PHP warnings
    $eventId = $_POST['id'] ?? ''; // Get the event ID
    $title = $_POST['title']; // Get the event title
    $start = $_POST['start']; // Get the event start date and time
    $end = $_POST['end']; // Get the event end date and time

    // Validate inputs
    $errors = [];

    if (empty($title)) $errors[] = 'Title is required.';
    if (empty($start)) $errors[] = 'Start time is required.';
    if (empty($end)) $errors[] = 'End time is required.';
    if (!empty($start) && !empty($end) && $start >= $end) {
        $errors[] = 'Start time must be less than end time.';
    }

    
    $event = new ScheduleEvent();
    // if ($event->checkEventOverlap($title, $start, $endTime, $title, $eventId)) {
    //     echo json_encode(['errors' => ['An event with the same title already exists at this date and time.']]);
    //     exit();
    // }
    
    
    // Handle validation errors
    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit();
    }

    $event = new ScheduleEvent();

    // Save or update event
    if ($eventId) {
        $success = $event->updateEvent($eventId, $title, $start, $end);
        $message = $success ? 'Event updated successfully!' : 'Failed to update the event.';
    } else {
        $success = $event->saveEvent($title, $start, $end);
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
    $events = $event->getAllEvents();
    $eventHistories=$event-> getAllWeeklyEvent();
    foreach ($events as $event) {
        $data[] = [
            'id' => $event['id'], // Event ID
            'title' => $event['title'], // Event title
            'start' => $event['start_time'], // Event start date and time
            'end' => $event['end_time'] // Event end date and time
        ];
    }
    // // Check if there's a search query in the URL
    // if (isset($_GET['search'])) {
    //     $searchQuery = $_GET['search'];
    //     // Get filtered events based on the search query
    //     $events = $event->getEventsBySearch($searchQuery);
    // } elseif (isset($_GET['date'])) {
    //     $date = $_GET['date'];
    //     // Get events filtered by date
    //     $events = $event->getEventsByDate($date);
    // } else {
    //     // If no search query or date is provided, fetch all events
       
    // }

    // Return the events as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'data' => $data,
        'eventHistories' => $eventHistories
    ]);
    // echo json_encode($data);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Decode the raw JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $eventId = $input['id'] ?? null; // Retrieve ID from JSON

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
