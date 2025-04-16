<?php
require_once '../../config/config.php';

class ScheduleEvent
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }
    public function getAllEvents()
    {
        try {
            $query = "SELECT * FROM schedule_events";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getEventsByDate($date)
    {
        $stmt = $this->db->prepare("SELECT * FROM schedule_events WHERE date = :date");
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function saveEvent($title,$startTime, $endTime)
    {
        try {
            $query = 'INSERT INTO schedule_events (title,start_time, end_time) 
                      VALUES (:title, :start_time, :end_time)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);

            if ($stmt->execute()) {
               
                return true;
            } else {
                
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    public function deleteEvent($id)
    {
        $stmt = $this->db->prepare("DELETE FROM schedule_events WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getEventById($eventId)
    {
        $stmt = $this->db->prepare("SELECT * FROM schedule_events WHERE id = :eventId");
        $stmt->bindParam(':eventId', $eventId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Assuming you're using PDO
    }
    public function updateEvent($id, $title, $startTime, $endTime)
    {
        // Prepare the SQL query
        $query = "UPDATE schedule_events SET title = ?, start_time = ?, end_time = ? WHERE id = ?";

        // Prepare the statement
        $stmt = $this->db->prepare($query);

        // Bind the parameters individually
        $stmt->bindValue(1, $title, PDO::PARAM_STR);
        $stmt->bindValue(2, $startTime, PDO::PARAM_STR);
        $stmt->bindValue(3, $endTime, PDO::PARAM_STR);
        $stmt->bindValue(4, $id, PDO::PARAM_INT);  // id should be an integer

        // Execute the query and return the result
        if ($stmt->execute()) {
            return true; // Update successful
        } else {
            return false; // Update failed
        }
    }

    public function getEventsBySearch($searchQuery)
    {
        // Sanitize input (for security)
        $searchQuery = "%" . $searchQuery . "%"; // Use wildcards for partial matching

        // SQL query to fetch events based on title or description
        $query = "SELECT * FROM schedule_events WHERE title LIKE :searchQuery OR description LIKE :searchQuery";

        // Prepare the statement
        $stmt = $this->db->prepare($query);

        // Bind the search query
        $stmt->bindParam(':searchQuery', $searchQuery, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Fetch all matching events
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function checkEventOverlap($date, $startTime, $endTime, $title, $eventId = null) {
        // Assuming you have a database connection in $this->db
        $query = "SELECT * FROM schedule_events WHERE title = ? AND date = ? AND start_time = ? AND end_time = ?";
    
        // If we're updating, exclude the current event from the check
        if ($eventId) {
            $query .= " AND id != ?";
        }
    
        // Prepare and execute the query
        $stmt = $this->db->prepare($query);
        
        if ($eventId) {
            // Binding parameters with proper types
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->bindParam(2, $date, PDO::PARAM_STR);
            $stmt->bindParam(3, $startTime, PDO::PARAM_STR);
            $stmt->bindParam(4, $endTime, PDO::PARAM_STR);
            $stmt->bindParam(5, $eventId, PDO::PARAM_INT); // eventId should be an integer
        } else {
            // Binding parameters with proper types
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->bindParam(2, $date, PDO::PARAM_STR);
            $stmt->bindParam(3, $startTime, PDO::PARAM_STR);
            $stmt->bindParam(4, $endTime, PDO::PARAM_STR);
        }
    
        $stmt->execute();
        
        // Check if any rows were returned, meaning the event already exists
        return $stmt->rowCount() > 0;
    }
    public function getAllWeeklyEvent()
    {
        try {
            $query = "SELECT * FROM schedule_events
                      WHERE YEARWEEK(start_time, 1) = YEARWEEK(NOW(), 1) 
                      ORDER BY start_time";
                      
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    
}    
