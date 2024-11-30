<?php

require_once "../models/ViewRelaxationActivityModel.php";

class ViewRelaxationActivityController {

    private $model;

    public function __construct() {
        $this->model = new ViewRelaxationActivityModel();
    }

    public function handleRequest() {
        session_start();

        // Ensure the session contains the user ID
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if (!$userId) {
            echo "You are not logged in!";
            return;
        }

        // Handle delete request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $this->deleteActivity($_POST['delete_id']);
        }

        // Fetch activities and role
        $activities = $this->model->getAllActivities();
        $role = $this->model->getUserRole($userId);

        // Display activities
        echo '<div class="content">';

        if (!empty($activities)) {
            foreach ($activities as $row) {
                $activityId = htmlspecialchars($row['id'] ?? '');
                $name = htmlspecialchars($row['activity_name'] ?? '');
                $description = htmlspecialchars($row['description'] ?? '');
                $file_name = htmlspecialchars($row['image_url'] ?? '');
                $playlist_url = htmlspecialchars($row['playlist_url'] ?? '');

                // Check if the user has admin privileges
                if ($role === 'admin' || $role === 'superadmin') {
                    echo '
                    <div class="card">
                        <div class="image-content">
                            <span class="overlay">
                                <form action="../views/update_relaxation_activities.php" method="GET">
                                    <input type="hidden" name="id" value="' . $activityId . '">
                                    <input type="hidden" name="activity_name" value="' . $name . '">
                                    <input type="hidden" name="description" value="' . $description . '">
                                    <input type="hidden" name="image_url" value="' . $file_name . '">
                                    <input type="hidden" name="playlist_url" value="' . $playlist_url . '">
                                    <button type="submit" class="delete-update-button">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="delete_id" value="' . $activityId . '">
                                    <button type="submit" class="delete-update-button">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </span>
                            <div class="card-image">
                                <img src="./uploads/' . $file_name . '" alt="' . $name . '" class="card-img">
                            </div>
                        </div>
                        <div class="card-content">
                            <h2 class="activity">' . $name . '</h2>
                            <p class="description">' . $description . '</p>
                            <button class="button"><a href="' . $playlist_url . '" target="_blank">View More</a></button>
                        </div>
                    </div>';
                } else {
                    echo 
                        '<div class="card">
                        <div class="image-content">
                            <span class="overlay">
                            </span>
                            <div class="card-image">
                                <img src="./uploads/' . $file_name . '" alt="' . $name . '" class="card-img">
                            </div>
                        </div>
                        <div class="card-content">
                            <h2 class="activity">' . $name . '</h2>
                            <p class="description">' . $description . '</p>
                            <button class="button"><a href="' . $playlist_url . '" target="_blank">View More</a></button>
                        </div>
                    </div>';
                }
            }
        } else {
            echo "<p>No relaxation activities found.</p>";
        }
        echo '</div>';
    }

    public function deleteActivity($activityId) {
        if ($this->model->deleteActivity($activityId)) {
            header("Location: /GroupProject-IS2102/App/views/relaxation_activities.php");
        } else {
            echo "Failed to delete activity.";
        }
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

            // Handle file upload
            if(move_uploaded_file($tempName, $folder)) {
                $isUpdated = $this->model->updateActivity($id, $name, $description, $fileName, $playlist_url);
            } 

            $update = $this->model->updateActivity($id, $name, $description, $fileName, $playlist_url);

            if ($update) {
                header("Location: /GroupProject-IS2102/App/views/relaxation_activities.php");
                exit;
            } else {
                echo "Failed to update activity.";
            }
        }
    }
}
