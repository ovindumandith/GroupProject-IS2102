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

        // Fetch activities and role
        $activities = $this->model->getAllActivities();
        $role = $this->model->getUserRole($userId);

        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_id'])) {
                $this->model->deleteActivity($_POST['delete_id']);
                header("Location: /GroupProject-IS2102/App/views/relaxation_activities.php");
                exit;
            } else if (isset($_POST['update_id']) && isset($_POST['activity_name']) && isset($_POST['description']) && isset($_POST['image_url']) && isset($_POST['playlist_url'])) {
                $this->model->updateActivity($_POST['update_id'], $_POST['activity_name'], $_POST['description'], $_POST['image_url'], $_POST['playlist_url']);
                header("Location: /GroupProject-IS2102/App/views/relaxation_activities.php");
                exit;
            }
        }

        // Display activities
        echo '<div class="content">';

        if (!empty($activities)) {
            foreach ($activities as $row) {
                $activityId = isset($row['id']) ? htmlspecialchars($row['id']) : '';
                $name = isset($row['activity_name']) ? htmlspecialchars($row['activity_name']) : '';
                $description = isset($row['description']) ? htmlspecialchars($row['description']) : '';
                $file_name = isset($row['image_url']) ? htmlspecialchars($row['image_url']) : '';
                $playlist_url = isset($row['playlist_url']) ? htmlspecialchars($row['playlist_url']) : '';

                // Check if the user has admin privileges
                if ($role === 'admin' || $role === 'superadmin') {
                    echo '
                    <div class="card">
                        <div class="image-content">
                            <span class="overlay">
                                <!-- Delete Form -->
                                <form method="POST">
                                    <input type="hidden" name="delete_id" value="' . $activityId . '">
                                    <button type="submit" class="delete-update-button">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                <!-- Update Form -->
                                <form method="POST">
                                    <input type="hidden" name="update_id" value="' . $activityId . '">
                                    <input type="hidden" name="activity_name" value="' . $name . '">
                                    <input type="hidden" name="description" value="' . $description . '">
                                    <input type="hidden" name="image_url" value="' . $file_name . '">
                                    <input type="hidden" name="playlist_url" value="' . $playlist_url . '">
                                    <button type="submit" class="delete-update-button">
                                        <i class="fas fa-edit">
                                            <a href="../views/update_relaxation_activities.php"></a>
                                        </i>
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
                    echo '
                    <div class="card">
                        <div class="image-content">
                            <span class="overlay"></span>
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
}

?>
