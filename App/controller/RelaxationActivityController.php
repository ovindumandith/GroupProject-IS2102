<?php
require_once "../models/RelaxationActivityModel.php";

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
            $playlist_url = $_POST['playlist_url'];
            $stress_level = $_POST['stress_level'] ? $_POST['stress_level'] : [];

            // Validate the file
            $errors = $this->validateFile($file);

            if (empty($errors)) {
                // Move uploaded file
                if (move_uploaded_file($tempName, $folder)) {
                    // Add activity to the database
                    $isAdded = $this->model->addRelaxationActivity($name, $description, $fileName, $playlist_url, $stress_level);

                    if ($isAdded) {
                        // Redirect to relaxation activities page
                        header("Location: /GroupProject-IS2102/App/views/add_relaxation_activites.php");
                        exit;
                    } else {
                        echo "Failed to add activity. Please try again.";
                    }
                } else {
                    echo "Failed to upload file. Please try again.";
                }
            } else {
                // Display validation errors
                echo implode("<br>", $errors);
            }
        }
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
