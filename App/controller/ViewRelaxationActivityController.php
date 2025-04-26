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
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'];
                $name = $_POST['activity_name'];
                $description = $_POST['description'] ?? '';
                $file = $_FILES['image_url'];
                $playlist_url = $_POST['playlist_url'];
                $stress_level = $_POST['stress_level'];
                $existingImage = $_POST['existing_image_url'] ?? '';
    
                // Validate inputs
                if (empty($name) || empty($playlist_url) || empty($stress_level)) {
                    throw new Exception("All required fields must be filled");
                }
    
                // Handle file upload
                $fileName = $existingImage;
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $allowedExtensions = ["jpg", "jpeg", "png", "avif", "jfif"];
                    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        throw new Exception("Invalid file type. Allowed formats: JPG, JPEG, PNG, AVIF, JFIF");
                    }
                    
                    if ($file['size'] > 5000000) {
                        throw new Exception("File size exceeds 5MB limit");
                    }
    
                    $fileName = uniqid() . '_' . basename($file['name']);
                    $targetPath = "./uploads/" . $fileName;
                    
                    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                        throw new Exception("Failed to upload image file");
                    }
    
                    // Delete old image if it exists
                    if (!empty($existingImage)) {
                        $oldPath = "./uploads/" . $existingImage;
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                }
    
                // Update the activity
                $isUpdated = $this->model->updateActivity(
                    $id, 
                    $name, 
                    $description, 
                    $fileName, 
                    $playlist_url, 
                    $stress_level
                );
    
                if (!$isUpdated) {
                    throw new Exception("Failed to update activity in database");
                }
    
                // Determine redirect page
                $redirectPage = match(strtolower($stress_level)) {
                    'low' => 'low_level_relaxation_activities.php',
                    'moderate' => 'moderate_level_relaxation_activities.php',
                    'high' => 'high_level_relaxation_activities.php',
                    default => 'relaxation_activities.php'
                };
    
                $_SESSION['success'] = "Activity updated successfully!";
                header("Location: $redirectPage");
                exit;
    
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: update_relaxation_activities.php?id=" . $_POST['id']);
                exit;
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