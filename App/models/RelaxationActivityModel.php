<?php
require_once '../../config/config.php';

class RelaxationActivityModel {
    private $db;

    public function __construct() {
        $this->db = new Database();          
        $this->db = $this->db->connect();     
    }

    public function addRelaxationActivity(
        $name, 
        $description, 
        $file_name, 
        $playlist_url, 
        $stress_level
    ) {
        try {
            $query = 'INSERT INTO relaxation_activities 
                     (activity_name, description, image_url, playlist_url, stress_level)
                     VALUES (?, ?, ?, ?, ?)';  
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $name);        
            $stmt->bindParam(2, $description);  
            $stmt->bindParam(3, $file_name);    
            $stmt->bindParam(4, $playlist_url);  
            $stmt->bindParam(5, $stress_level); 

            return $stmt->execute();           

        } catch (PDOException $e) {
            $this->logError($e->getMessage());  
            return false;
        }
    }

    private function logError($message) {
        error_log($message, 3, '../../logs/error.log');  
    }
}
?>