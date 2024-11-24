<?php

require_once "../models/ViewRelaxationActivityModel.php";

class ViewRelaxationActivityController {

    private $model;
    private $db;

    public function __construct() {
        $this->model = new ViewRelaxationActivityModel();
        $this->db = new ViewRelaxationActivityModel();
    }

    public function handleRequest() {

        session_start();

        $userId = $_SESSION['user_id'];


        if(!$userId){
            echo "You are not logged in!";
            return;
        }

        $activities = $this->model->getAllActivities(); // Fetch all activities
        $role = $this->model->getUserRole($userId); // Fetch the user role
    
        echo '<div class="content">';
    
        if (!empty($activities)) {
             // Counter to track the number of cards in a row
    
            foreach ($activities as $row) {
                $name = htmlspecialchars($row['activity_name']);
                $description = htmlspecialchars($row['description']);
                $file_name = htmlspecialchars($row['image_url']);
                $playlist_url = htmlspecialchars($row['playlist_url']);
    

                //1.added if statement to check if the user is an admin or superadmin
                if($role == 'admin' || $role == 'superadmin'){
                echo '
                    <div class="card">
                        <div class="image-content">
                            <span class="overlay"></span>
                            <h2 class="activity">' . $name . '</h2>
                            </span>
                            <div class="card-image">
                                <img src="./uploads/' . $file_name . '" alt="' . $name . '" class="card-img">
                            </div>
                        </div>
                        <div class="card-content">
                            <h2 class="activity">' . $name . '</h2>
                            <p class="description">' . $description . '</p>
                            <button class="button"><a href="'.$playlist_url.'" target="_blank">View More</a></button>
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
                        <button class="button"><a href="'.$playlist_url.'" target="_blank">View More</a></button>
                    </div>
                </div>';
                } // Close the if statement
            } // Close the foreach loop
        
                echo '</div>'; // Close the last row
        } else {
            echo "<p>No relaxation activities found.</p>";
        }
        
        echo '</div>';
    }

}

?>
