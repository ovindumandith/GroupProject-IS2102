<?php
require_once "../models/RelaxationActivityModel.php";

class RelaxationActivityController {
    private $model;

    public function __construct() {
        $this->model = new RelaxationActivityModel();
    }

    public function handleRequest() {
        session_start();

        if (isset($_POST['submit'])) {
            try {
                $name          = $_POST['activity_name'];
                $description   = $_POST['description'] ?? '';
                $file          = $_FILES['image_url'];
                $playlist_url  = $_POST['playlist_url'];
                $stress_level  = $_POST['stress_level'] ?? '';

                $errors = $this->validateFile($file);

                if (!empty($errors)) {
                    throw new Exception(implode("\n", $errors));
                }

                $fileName = $this->handleFileUpload($file);

                $isAdded = $this->model->addRelaxationActivity(
                    $name,
                    $description,
                    $fileName,
                    $playlist_url,
                    $stress_level
                );

                if (!$isAdded) {
                    throw new Exception("Failed to add activity. Please try again.");
                }

                $redirectPage = $this->getRedirectPage($stress_level);
                $_SESSION['success'] = "🎉 Activity added successfully!";
                header("Location: $redirectPage");
                exit;

            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: add_relaxation_activites.php");
                exit;
            }
        }
    }

    private function validateFile($file) {
        $errors = [];
        $allowedExtensions = ["jpg", "jpeg", "png", "avif", "jfif"];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload error. Please try again.";
            return $errors;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            $allowedList = implode(", ", array_unique([
                "JPG", "JPEG", "PNG", "AVIF", "JFIF"
            ]));
            $errors[] = "Invalid file type (.$ext). Supported formats: $allowedList.";
        }

        if ($file['size'] > 5000000) {
            $errors[] = "File size exceeds limit. Maximum allowed: 5MB.";
        }

        return $errors;
    }

    private function handleFileUpload($file) {
        $uploadsDir = './uploads/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadsDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("🚫 Failed to upload file. Please try again.");
        }

        return $fileName;
    }

    private function getRedirectPage($stressLevel) {
        $redirectMap = [
            'low'      => 'low_level_relaxation_activities.php',
            'moderate' => 'moderate_level_relaxation_activities.php',
            'high'     => 'high_level_relaxation_activities.php'
        ];

        return $redirectMap[$stressLevel] ?? 'high_level_relaxation_activities.php';
    }
}