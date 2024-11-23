<?php

require_once "../models/ViewRelaxationActivityModel.php";

class ViewRelaxationActivityController {

    private $model;

    public function __construct() {
        $this->model = new ViewRelaxationActivityModel();
    }

    public function handleRequest() {
        $activities = $this->model->getAllActivities(); // Fetch all activities
    
        echo '<div class="content">';
    
        if (!empty($activities)) {
            $counter = 0; // Counter to track the number of cards in a row
            echo '<div class="row">'; // Start a new row
    
            foreach ($activities as $row) {
                $name = htmlspecialchars($row['activity_name']);
                $description = htmlspecialchars($row['description']);
                $file_name = htmlspecialchars($row['image_url']);
    
                echo '
                <div class="card-div">
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
                            <button class="button">View More</button>
                        </div>
                    </div>
                </div>';
    
                $counter++;
    
                // Check if 4 cards are displayed; if so, close the row and start a new one
                /*if ($counter % 4 == 0 && $counter != count($activities)) {
                    echo '</div><div class="row">'; // Close the current row and open a new one
                }*/

                if ($counter % 4 == 0 && $counter < count($activities)) {
                    echo '</div><div class="row">'; // Close the current row and open a new one
                }
            } // Close the foreach loop
        
            echo '</div>'; // Close the last row
    
        } else {
            echo "<p>No relaxation activities found.</p>";
        }
        
        echo '</div>';
    }
    

}

?>
