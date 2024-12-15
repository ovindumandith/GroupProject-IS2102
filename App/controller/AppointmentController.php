<?php
require_once '../models/AppointmentModel.php';

class AppointmentController {
    private $model;

    public function __construct() {
        $this->model = new AppointmentModel();
    }

    public function scheduleAppointment() {
        session_start();
        $studentId = $_SESSION['user_id']; // Retrieve student ID from session
        $counselorId = $_POST['counselor_id']; // From hidden input in form
        $appointmentDate = $_POST['appointment_date'];
        $topic = $_POST['topic'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        if ($this->model->createAppointment($studentId, $counselorId, $appointmentDate, $topic, $email, $phone)) {
            header('Location: appointment_success.php'); // Redirect on success
        } else {
            header('Location: appointment_error.php'); // Redirect on error
        }
    }
}

// Check if an action is set in the query string
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new AppointmentController();

    if ($action == 'scheduleAppointment') {
        $controller->scheduleAppointment(); // Call the function to handle the form submission
    }
}