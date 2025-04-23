<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../models/ToDoList.php';
session_start(); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure proper JSON header
    header('Content-Type: application/json');
    error_reporting(E_ERROR | E_PARSE); // Suppress PHP warnings
    // Fetch input values

    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    $description = $_POST['description'] ?? '';
    $username= $_SESSION['user_name']?? '';
     
    // $data = json_decode(file_get_contents("php://input"), true);
    // if (isset($data['id']) && isset($data['status'])) {
    //     $taskId = $data['id'];
    //     $status = $data['status'];

    // }    

    // Validate inputs
    $errors = [];
    if (is_null($status)) {
       
        if (empty($title)) $errors[] = 'Title is required.';
        if (empty($date)) $errors[] = 'Date is required.';
        
       

        if (!$id) {
            if (!empty($date) && strtotime($date) < time()) {
                $errors[] = 'The event date must be in the future.';
            }
        }
        
        $task = new ToDoList();
        
        if ($task->checkTaskOverlap($date, $title, $id)) {
           
            echo json_encode(['errors' => ['An Task with the same title already exists at this date and time.']]);
            exit();
        }
    }


    // Handle validation errors
    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit();
    }

    $task = new ToDoList();
   
    if (!is_null($status)){
        $success = $task->updateTaskStatus($id, $status);
        $message = $success ? 'Task status updated successfully!' : 'Failed to update the Task status.';
    } else if(!empty($id)) {
        $success = $task->updateTask($id, $title, $date, $description);
        if($success){
            $isSave = $task->saveTaskHistories('Update Task',$username,date('Y-m-d H:i:s'));
            $message = $isSave ? 'Task saved successfully!' : 'Failed to save the Task.'; 
        }
        $message = $success ? 'Task updated successfully!' : 'Failed to update the Task.';
    } else {
        $success = $task->saveTask($title, $date, $description ,$username);
        if($success){
            $isSave = $task->saveTaskHistories('Add Task',$username,date('Y-m-d H:i:s'));
            $message = $isSave ? 'Task saved successfully!' : 'Failed to save the Task.';
        }
       
        
    }

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
    $task = new ToDoList();
    $taskHistories=[];

    // Check if there's a search query in the URL
    if (isset($_GET['search'])) {
        $searchQuery = $_GET['search'];
        // Get filtered events based on the search query
        $tasks = $task->getTaskBySearch($searchQuery);
    } elseif (isset($_GET['date'])) {
        $date = $_GET['date'];
        // Get events filtered by date
        $tasks = $task->getTasksByDate($date);
    } else {
        // If no search query or date is provided, fetch all events
        $tasks = $task->getAllTasks();
        $taskHistories=$task-> getAllWeeklyTask();
    }
    

    // Return the events as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'tasks' => $tasks,
        'taskHistories' => $taskHistories
    ]);
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
