<?php
require_once '../models/AppointmentModel.php';

class AppointmentController {
    private $model;

    public function __construct() {
        $this->model = new AppointmentModel();
    }

    // Method to schedule an appointment
    public function scheduleAppointment() {
        session_start();
        $studentId = $_SESSION['user_id']; // Retrieve student ID from session
        $counselorId = $_POST['counselor_id']; // From hidden input in form
        $appointmentDate = $_POST['appointment_date'];
        $topic = $_POST['topic'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        if ($this->model->createAppointment($studentId, $counselorId, $appointmentDate, $topic, $email, $phone)) {
            header('Location: ../views/counselling/appointment_success.php?success=1'); // Redirect on success with success flag
            exit();
        } else {
            header('Location: ../views/counselling/appointment_error.php'); // Redirect on error
            exit();
        }
    }
    public function showStudentAppointments() {
    session_start();

    // Ensure the student is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php'); // Redirect to login page if not logged in
        exit();
    }

    $studentId = $_SESSION['user_id'];
    $appointments = $this->model->getByStudentId($studentId); // Fetch the student's appointments

    // Include the view to display the appointments
    include '../views/showStudentAppointments.php'; // Pass the appointments data to the view
}

    // Method to fetch pending appointments for a counselor
    public function showPendingAppointments() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }

        // Ensure the user is logged in as a counselor
        if (!isset($_SESSION['counselor']['id'])) {
            header('Location: ../views/counselling/counselor_login.php');
            exit();
        }

        $counselorId = $_SESSION['counselor']['id'];
        $appointments = $this->model->getPendingAppointmentsByCounselorId($counselorId);

        include '../views/counselling/counselor_view_appointments.php'; // Pass data to the view
    }

    // Method to fetch approved appointments for a counselor
        public function showApprovedAppointments() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }

        // Ensure the user is logged in as a counselor
        if (!isset($_SESSION['counselor']['id'])) {
            header('Location: ../views/counselling/counselor_login.php');
            exit();
        }

        $counselorId = $_SESSION['counselor']['id'];
        $appointments = $this->model->getApprovedAppointmentsByCounselorId($counselorId);

        include '../views/counselling/counselor_view_appointments_approved.php'; // Pass data to the view
    }

    // Method to fetch denied appointments for a counselor
        public function showDeniedAppointments() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }

        // Ensure the user is logged in as a counselor
        if (!isset($_SESSION['counselor']['id'])) {
            header('Location: ../views/counselling/counselor_login.php');
            exit();
        }

        $counselorId = $_SESSION['counselor']['id'];
        $appointments = $this->model->getDeniedAppointmentsByCounselorId($counselorId);

        include '../views/counselling/counselor_view_appointments_rejected.php'; // Pass data to the view
    }

    // Method to update appointment status
    public function updateAppointmentStatus() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['status'])) {
            $appointmentId = $_POST['appointment_id'];
            $status = $_POST['status'];

            if ($this->model->updateAppointmentStatus($appointmentId, $status)) {
                $_SESSION['status_update_success'] = 'Appointment status updated successfully.';
            } else {
                $_SESSION['status_update_error'] = 'Failed to update appointment status.';
            }
            header('Location: AppointmentController.php?action=showPendingAppointments');
            exit();
        }
    }
    public function deleteAppointment() {
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
        $appointmentId = $_POST['appointment_id'];
        
        // Fetch the appointment to check its status
        $appointment = $this->model->getByStudentId($appointmentId);

        if ($appointment['status'] === 'Accepted') {
            // Optional: Log the cancellation or notify the counselor
            $_SESSION['appointment_message'] = 'The appointment was scheduled. You have canceled it.';
        } else {
            $_SESSION['appointment_message'] = 'The appointment has been successfully deleted.';
        }

        // Perform the deletion
        if ($this->model->deleteAppointment($appointmentId)) {
            header('Location: AppointmentController.php?action=showStudentAppointments');
            exit();
        } else {
            $_SESSION['appointment_message'] = 'Failed to delete the appointment.';
            header('Location: ../views/student_appointments.php?error=1');
            exit();
        }
    }
}



}

// Check if an action is set in the query string
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new AppointmentController();

    switch ($action) {
        case 'scheduleAppointment':
            $controller->scheduleAppointment();
            break;
        case 'showPendingAppointments':
            $controller->showPendingAppointments();
            break;
        case 'updateAppointmentStatus':
            $controller->updateAppointmentStatus();
            break;
        case 'showApprovedAppointments':
            $controller->showApprovedAppointments();
            break;
        case 'showDeniedAppointments':
            $controller->showDeniedAppointments();
            break;        
        case 'showStudentAppointments':
            $controller->showStudentAppointments();
            break;
        case 'deleteAppointment':
            $controller->deleteAppointment();
            break;        
        default:
            echo 'Invalid action';
    }
}
