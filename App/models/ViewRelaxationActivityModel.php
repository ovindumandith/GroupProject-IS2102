<?php
require_once '../../config/config.php';

class ViewRelaxationActivityModel {
    private $db;

    public function __construct() {
        $db       = new Database();                         
        $this->db = $db->connect();                         
        
        if ($this->db === null) {                           
            error_log("Database connection failed in ViewRelaxationActivityModel");
            throw new Exception("Database connection failed");
        }
    }

    public function getAllActivities() {
        try {
            $sql  = "SELECT * FROM relaxation_activities";  
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);       
        } catch (PDOException $e) {
            error_log("Error fetching activities: " . $e->getMessage());  
            return [];
        }
    }

    public function updateActivity($activityId, $activityName, $description, $imageUrl, $playlistUrl, $stressLevel) {
        try {
            $sql = "UPDATE relaxation_activities 
                   SET activity_name = :activity_name,
                       description   = :description,
                       image_url     = :image_url,
                       playlist_url  = :playlist_url,
                       stress_level  = :stress_level 
                   WHERE id = :id";                        

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':activity_name', $activityName);    
            $stmt->bindParam(':description',   $description);
            $stmt->bindParam(':image_url',     $imageUrl);         
            $stmt->bindParam(':playlist_url',  $playlistUrl);      
            $stmt->bindParam(':stress_level',  $stressLevel);      
            $stmt->bindParam(':id',            $activityId);

            error_log("Executing SQL: $sql");                       
            error_log("With Params: ID=$activityId...");            
            if ($stmt->execute()) {                                 
                error_log("Activity updated successfully for ID: $activityId");
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Update Error: " . $e->getMessage());         
        }
    }

    public function deleteActivity($activityId) {
        try {
            // File Cleanup: Physical file deletion before DB removal
            $activity = $this->getActivityById($activityId);
            if ($activity && !empty($activity['image_url'])) {
                $filePath = "./uploads/" . $activity['image_url'];
                if (file_exists($filePath)) {
                    unlink($filePath);                            
                }
            }

            $sql  = "DELETE FROM relaxation_activities WHERE id = :id";  
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $activityId, PDO::PARAM_INT);  
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Deletion Error: " . $e->getMessage());
            return false;
        }
    }
     
    public function getActivityById($activityId) {
        try {
            $sql  = "SELECT * FROM relaxation_activities WHERE id = :id";  
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $activityId, PDO::PARAM_INT);  
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ID Lookup Error: " . $e->getMessage());
            return null;
        }
    }

    public function getActivitiesByStressLevel($stressLevel) {
        try {
            $sql  = "SELECT * FROM relaxation_activities          
                    WHERE stress_level = :stress_level";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':stress_level', $stressLevel, PDO::PARAM_STR);  
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Stress Level Filter Error: " . $e->getMessage());
            return [];
        }
    }
}