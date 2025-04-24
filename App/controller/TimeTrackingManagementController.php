<?php

require_once '../models/TimeTrackingManagementModel.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure proper JSON header
    header('Content-Type: application/json');
    error_reporting(E_ERROR | E_PARSE); // Suppress PHP warnings
    // Fetch input values

    $id = $_POST['id'] ?? null;
    $taskName = $_POST['taskName'] ?? '';
    $description = $_POST['description'] ?? '';
    $timeGoal = $_POST['timeGoal'] ?? '';
    $timeUnit = $_POST['timeUnit'] ?? '';
    $timeSpent = $_POST['timeSpent'] ?? null;
    $completed = $_POST['completed'] ?? null;
    $createdAt = $_SESSION['createdAt'];

    // $data = json_decode(file_get_contents("php://input"), true);
    // if (isset($data['id']) && isset($data['status'])) {
    //     $taskId = $data['id'];
    //     $status = $data['status'];

    // }    

    // Validate inputs
    $errors = [];
    if (is_null($status)) {

        if (empty($taskName)) $errors[] = 'TaskName is required.';
        if (empty($description)) $errors[] = 'Description is required.';
        if (empty($timeGoal)) $errors[] = 'Time Goal is required.';
        if (empty($description)) $errors[] = 'Description is required.';

        if (!$id) {
            if (!empty($createdAt) && strtotime($createdAt) < time()) {
                $errors[] = 'The event date must be in the future.';
            }
        }

        $timeManagement = new TimeTrackingManagement();

        if ($timeManagement->checkTaskOverlap($date, $time, $title, $id)) {

            echo json_encode(['errors' => ['An Task with the same title already exists at this date and time.']]);
            exit();
        }
    }


    // Handle validation errors
    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit();
    }
    
    if(!is_null($id)){
        echo json_encode($id);
    }
    else{
        $success = $timeManagement->saveTaskManagement($taskName, $description, $timeGoal, $timeUnit, $timeSpent, $completed, $createdAt);
        $message = $success ? 'Task saved successfully!' : 'Failed to save the Task.';
    }
    


    // if (!is_null($timeSpent)){
    //     $success = $task->updateTaskStatus($id, $status);
    //     $message = $success ? 'Task status updated successfully!' : 'Failed to update the Task status.';
    // } else if(!empty($id)) {
    //     $success = $task->updateTask($id, $title, $date, $time, $description);
    //     if($success){
    //         $isSave = $task->saveTaskHistories('Update Task',$username,date('Y-m-d H:i:s'));
    //         $message = $isSave ? 'Task saved successfully!' : 'Failed to save the Task.'; 
    //     }
    //     $message = $success ? 'Task updated successfully!' : 'Failed to update the Task.';
    // } else {
    //     $success = $timeManagement->saveTaskManagement($taskName, $description, $timeGoal, $timeUnit,$timeSpent,$completed,$createdAt);
    //     if($success){
    //         $isSave = $task->saveTaskHistories('Add Task',$username,date('Y-m-d H:i:s'));
    //         $message = $isSave ? 'Task saved successfully!' : 'Failed to save the Task.';
    //     }


    // }

    echo json_encode(['message' => $message]);
    exit();
}


// In ScheduleEventController.php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $taskId = $_GET['id'] ?? null;

    if ($taskId) {
        $task = new ToDoList();

        // Fetch the event by ID
        $taskData = $task->getEventById($taskId);

        if ($taskData) {
            // Send JSON response with event data
            header('Content-Type: application/json');
            echo json_encode($taskData);
        } else {
            // Send an error response if event not found
            http_response_code(404);
            echo json_encode(['error' => 'Task not found']);
        }
    } else {
        // Send error response if ID is not provided
        http_response_code(400);
        echo json_encode(['error' => 'Task ID is required']);
    }

    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $timeTracking = new TimeTrackingManagement();

    // Check if there's a search query in the URL
    if (isset($_GET['search'])) {
        $searchQuery = $_GET['search'];
        // Get filtered events based on the search query
        $timeTracking = $timeTracking->getTaskBySearch($searchQuery);
    } elseif (isset($_GET['date'])) {
        $date = $_GET['date'];
        // Get events filtered by date
        $timeTracking = $timeTracking->getTasksByDate($date);
    } else {
        // If no search query or date is provided, fetch all events
        $tasks = $timeTracking->getAllTasks();
        
    }

    // Return the events as JSON
    header('Content-Type: application/json');
    echo json_encode($tasks);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Decode the raw JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $taskId = $input['id'] ?? null;

    if ($taskId) {
        $task = new ToDoList();
        if ($task->deleteTask($taskId)) {
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
