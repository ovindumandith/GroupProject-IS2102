<?php
require_once "../models/AddRelaxationActivityModel.php";

class RelaxationActivityController {
    private $model;

    public function __construct() {
        $this->model = new RelaxationActivityModel();
    }

    public function handleRequest() {
        if (isset($_POST['submit'])) {
            $name = $_POST['activity_name'];
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $file = $_FILES['image_url'];
            $fileName = $file['name'];
            $tempName = $file['tmp_name'];
            $folder = './uploads/' . $fileName;

            $errors = $this->validateFile($file);

            if (empty($errors)) {
                if (move_uploaded_file($tempName, $folder)) {
                    $isAdded = $this->model->addRelaxationActivity($name, $description, $fileName);
                    return $isAdded ? "Activity Added Successfully!" : "Failed to add activity. Please try again.";
                }
            }

            return implode("<br>", $errors); // Display errors
        }

        return null;
    }

    private function validateFile($file) {
        $errors = [];
        $allowedExtensions = ["jpg", "jpeg", "png", "avif", "jfif"];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = "Only JPG, JPEG, PNG, AVIF, JFIF files are allowed.";
        }

        if ($file['size'] > 5000000) {
            $errors[] = "File size should not exceed 5MB.";
        }

        return $errors;
    }
}
?>
