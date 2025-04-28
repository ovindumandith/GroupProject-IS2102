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
            $query = "SELECT * FROM schedule_events_2 ORDER BY date, start_time";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getEventsByDate($date)
    {
        $stmt = $this->db->prepare("SELECT * FROM schedule_events_2 WHERE date = :date");
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function saveEvent($title, $description, $date, $startTime, $endTime)
    {
        try {
            $query = 'INSERT INTO schedule_events_2 (title, description, date, start_time, end_time) 
                      VALUES (:title, :description, :date, :start_time, :end_time)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
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
        $stmt = $this->db->prepare("DELETE FROM schedule_events_2 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getEventById($eventId)
    {
        $stmt = $this->db->prepare("SELECT * FROM schedule_events_2 WHERE id = :eventId");
        $stmt->bindParam(':eventId', $eventId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Assuming you're using PDO
    }
    public function updateEvent($id, $title, $description, $date, $startTime, $endTime)
    {
        // Prepare the SQL query
        $query = "UPDATE schedule_events_2 SET title = ?, description = ?, date = ?, start_time = ?, end_time = ? WHERE id = ?";

        // Prepare the statement
        $stmt = $this->db->prepare($query);

        // Bind the parameters individually
        $stmt->bindValue(1, $title, PDO::PARAM_STR);
        $stmt->bindValue(2, $description, PDO::PARAM_STR);
        $stmt->bindValue(3, $date, PDO::PARAM_STR);
        $stmt->bindValue(4, $startTime, PDO::PARAM_STR);
        $stmt->bindValue(5, $endTime, PDO::PARAM_STR);
        $stmt->bindValue(6, $id, PDO::PARAM_INT);  // id should be an integer

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
        $query = "SELECT * FROM schedule_events_2 WHERE title LIKE :searchQuery OR description LIKE :searchQuery";

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
        $query = "SELECT * FROM schedule_events_2 WHERE title = ? AND date = ? AND start_time = ? AND end_time = ?";
    
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
    
}    
