<?php
require_once '../../../GroupProject-IS2102/App/models/CounselorModel.php';

class CounselingController {
    private $counselorModel;

    public function __construct() {
        $this->counselorModel = new CounselorModel();
    }

    public function listCounselors() {
        // Fetch counselors from the database
        $counselors = $this->counselorModel->getAllCounselors();

        // Pass the counselors array to the view
        require_once '../../App/views/counselling/counsellor_index.php';    
    }
}

// Instantiate the controller and call the appropriate method
$controller = new CounselingController();
$controller->listCounselors();
?>
