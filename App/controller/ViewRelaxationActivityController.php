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
        $role   = $_SESSION['role'] ?? null;

        if (!$userId) {
            header("Location: login.php");  
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $this->handleDelete($_POST['delete_id'], $role);  
            return;
        }

        $lowStressActivities      = $this->model->getActivitiesByStressLevel('low');
        $moderateStressActivities = $this->model->getActivitiesByStressLevel('moderate');
        $highStressActivities     = $this->model->getActivitiesByStressLevel('high');

        return [
            'activities'               => $this->model->getAllActivities(),
            'lowStressActivities'      => $lowStressActivities,
            'moderateStressActivities' => $moderateStressActivities,
            'highStressActivities'     => $highStressActivities,
            'role'                     => $role
        ];
    }

    public function updateActivity() {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id           = $_POST['id'];
                $name         = $_POST['activity_name'];
                $description = $_POST['description'] ?? '';
                $file        = $_FILES['image_url'];
                $playlist_url= $_POST['playlist_url'];
                $stress_level = $_POST['stress_level'];
                $existingImage= $_POST['existing_image_url'] ?? '';
    
                if (empty($name) || empty($playlist_url) || empty($stress_level)) {
                    throw new Exception("All required fields must be filled");  // Input Validation: Essential field check
                }
    
                // File Update Logic: Maintains existing image unless new file uploaded
                $fileName = $existingImage;
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $allowedExtensions = ["jpg", "jpeg", "png", "avif", "jfif"];  // File Validation: Allowed formats
                    $fileExtension    = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        throw new Exception("Invalid file type. Allowed formats: JPG, JPEG, PNG, AVIF, JFIF");
                    }
                    
                    if ($file['size'] > 5000000) {
                        throw new Exception("File size exceeds 5MB limit");  // File Validation: Size restriction
                    }
    
                    $fileName    = uniqid() . '_' . basename($file['name']);  // Security: Unique filename generation
                    $targetPath  = "./uploads/" . $fileName;
                    
                    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {  // Security: Safe file upload method
                        throw new Exception("Failed to upload image file");
                    }
    
                    if (!empty($existingImage)) {
                        $oldPath = "./uploads/" . $existingImage;
                        if (file_exists($oldPath)) {
                            unlink($oldPath);  // Resource Management: Cleans up old files
                        }
                    }
                }
    
                $isUpdated = $this->model->updateActivity(  // Database Integration: Delegates update to model
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
    
                $redirectPage = match(strtolower($stress_level)) {  // Dynamic Redirection: Stress-level based routing
                    'low'      => 'low_level_relaxation_activities.php',
                    'moderate' => 'moderate_level_relaxation_activities.php',
                    'high'     => 'high_level_relaxation_activities.php',
                    default    => 'relaxation_activities.php'
                };
    
                $_SESSION['success'] = "Activity updated successfully!";  // User Feedback: Success notification
                header("Location: $redirectPage");
                exit;
    
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();  // Error Handling: Stores message in session
                header("Location: update_relaxation_activities.php?id=" . $_POST['id']);
                exit;
            }
        }
    }

    private function handleDelete($activityId, $userRole) {
        $redirectPage = $_POST['redirect_page'] ?? 'relaxation_activities.php';  // Redirect Strategy: Maintains context
        
        if ($userRole !== 'admin' && $userRole !== 'superadmin') {  // Role-Based Access Control: Admin restriction
            $_SESSION['error'] = "You don't have permission to delete activities";
            header("Location: $redirectPage");
            exit;
        }
    
        if (!is_numeric($activityId)) {  // Input Sanitization: ID validation
            $_SESSION['error'] = "Invalid activity ID";
            header("Location: $redirectPage");
            exit;
        }
    
        if ($this->model->deleteActivity($activityId)) {  // Database Integration: Model handles deletion
            $_SESSION['success'] = "Activity deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete activity";
        }
    
        header("Location: $redirectPage");
        exit;
    }
}