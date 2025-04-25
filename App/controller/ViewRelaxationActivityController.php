<?php
require_once '../models/ViewRelaxationActivityModel.php';

class ViewRelaxationActivityController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function handleRequest() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); 
        }

        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['role'] ?? null;

        if (!$userId) {
            header("Location: login.php");
            exit;
        }

        // Handle deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $this->handleDelete($_POST['delete_id'], $role);
            return;
        }

        $lowStressActivities = $this->model->getActivitiesByStressLevel('low');
        $moderateStressActivities = $this->model->getActivitiesByStressLevel('moderate');
        $highStressActivities = $this->model->getActivitiesByStressLevel('high');

        return [
            'activities' => $this->model->getAllActivities(),
            'lowStressActivities' => $lowStressActivities,
            'moderateStressActivities' => $moderateStressActivities,
            'highStressActivities' => $highStressActivities,
            'role' => $role
         ];

    }

    public function updateActivity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['activity_name'];
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $file = $_FILES['image_url'];
            $fileName = $file['name'];
            $tempName = $file['tmp_name'];
            $folder = './uploads/' . $fileName;
            $playlist_url = $_POST['playlist_url'];
            $stress_level = $_POST['stress_level'];

            // Handle file upload
            if(move_uploaded_file($tempName, $folder)) {
                $isUpdated = $this->model->updateActivity($id, $name, $description, $fileName, $playlist_url, $stress_level);
            } 

            $update = $this->model->updateActivity($id, $name, $description, $fileName, $playlist_url, $stress_level);

            $stress_level = $_POST['stress_level'];
        
        // Determine the correct redirect page based on stress level
        $redirectPage = match(strtolower($stress_level)) {
            'low' => 'low_level_relaxation_activities.php',
            'moderate' => 'moderate_level_relaxation_activities.php',
            'high' => 'high_level_relaxation_activities.php',
            // default => 'relaxation_activities.php' // fallback
        };

            if ($update) {
                header("Location: /GroupProject-IS2102/App/views/$redirectPage");
                exit;
            } else {
                echo "Failed to update activity.";
            }
        }
    }

    private function handleDelete($activityId, $userRole) {
        // Get the redirect page from POST or default to general page
        $redirectPage = $_POST['redirect_page'] ?? 'relaxation_activities.php';
        
        // Verify user has permission to delete
        if ($userRole !== 'admin' && $userRole !== 'superadmin') {
            $_SESSION['error'] = "You don't have permission to delete activities";
            header("Location: $redirectPage");
            exit;
        }
    
        // Validate ID
        if (!is_numeric($activityId)) {
            $_SESSION['error'] = "Invalid activity ID";
            header("Location: $redirectPage");
            exit;
        }
    
        if ($this->model->deleteActivity($activityId)) {
            $_SESSION['success'] = "Activity deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete activity";
        }
    
        header("Location: $redirectPage");
        exit;
    }
}