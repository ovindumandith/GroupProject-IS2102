<?php

    require_once "../models/RelaxationActivityModel.php";

    class ViewRelaxationActivityController {
        private $model;

        public function __construct() {
            $this->model = new RelaxationActivityModel();
        }

        public function handleRequest() {
            require_once '../../config/config.php';
      $db = new Database();
      $conn = $db->connect();
      
      $sql = "SELECT * FROM relaxation_activities";
      $result = $conn->query($sql);

      if (!$result) {
        $errorInfo = $conn->errorInfo();
        die('Error executing query: ' . $errorInfo[2]);
      }

        echo '
        <div class="content">';

      if($result->rowCount() > 0) {
        
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $name = htmlspecialchars( $row['activity_name']);
          $description = htmlspecialchars($row['description']);
          $file_name = htmlspecialchars($row['image_url']);

          echo '
          <div class="container">
            <div class="card-div">
              <div class="card">
                <div class="image-content">
                  <span class="overlay"></span>
                  <div class="card-image">
                    <img src="./uploads/'.$file_name.'" alt="'.$name.'" class="card-img">
                  </div>
                </div>
                <div class="card-content">
                  <h2 class="activity">'.$name.'</h2>
                  <p class="description">
                    '.$description.'
                  </p>
                  <button class="button">View More</button>
                </div>
              </div>
            </div>
          </div>
        ';
      }
        
      } else {
        echo "<p>No relaxation activities found.</p>";
      }
    

      echo '
          </div>
        </div>';
        }
    }
?>