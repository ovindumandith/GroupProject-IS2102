<?php
require_once '../models/AppointmentModel.php';
require_once '../services/EmailService.php';

class AppointmentController {
    private $model;
    private $emailService;

    public function __construct() {
        $this->model = new AppointmentModel();
        $this->emailService = new EmailService();
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

            // Get appointment details before updating
            $appointment = $this->model->getAppointmentById($appointmentId);
            
            if (!$appointment) {
                $_SESSION['status_update_error'] = 'Appointment not found.';
                header('Location: /GroupProject-IS2102/App/controller/AppointmentController.php?action=showPendingAppointments');
                exit();
            }

            if ($this->model->updateAppointmentStatus($appointmentId, $status)) {
                $_SESSION['status_update_success'] = 'Appointment status updated successfully.';
                
                // Get student details to use their name in the email
                $studentDetails = $this->model->getStudentDetails($appointment['student_id']);
                $studentName = $studentDetails ? $studentDetails['username'] : 'Student';
                
                // Send email notification to student
                $this->emailService->sendAppointmentStatusNotification(
                    $appointment['email'], 
                    $studentName, 
                    $appointment, 
                    $status
                );
            } else {
                $_SESSION['status_update_error'] = 'Failed to update appointment status.';
            }
            
            header('Location: /GroupProject-IS2102/App/controller/AppointmentController.php?action=showPendingAppointments');
            exit();
        }
    }

/**
 * View a student's stress trend data
*/

public function viewStudentStressTrend() {
    session_start();
    
    // Check if counselor is logged in
    if (!isset($_SESSION['counselor']['id'])) {
        header('Location: ../views/counselling/counselor_login.php');
        exit();
    }
    
    // Validate the student ID parameter
    if (!isset($_GET['student_id']) || !is_numeric($_GET['student_id'])) {
        $_SESSION['error_message'] = "Invalid student ID.";
        header('Location: /GroupProject-IS2102/App/controller/AppointmentController.php?action=showPendingAppointments');
        exit();
    }
    
    $studentId = (int)$_GET['student_id'];
    $appointmentId = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : null;
    
    // Get comprehensive student details
    $studentDetails = $this->model->getStudentDetails($studentId);
    
    if (!$studentDetails) {
        $_SESSION['error_message'] = "Student not found.";
        header('Location: /GroupProject-IS2102/App/controller/AppointmentController.php?action=showPendingAppointments');
        exit();
    }
    
    // Include the StressAssessmentModel to get stress trend data
    require_once '../models/StressAssessmentModel.php';
    $stressModel = new StressAssessmentModel();
    
    // Get the student's stress trend data
    $trendData = $stressModel->getStressTrend($studentId, 10); // Get the last 10 records
    
    // If no trend data is available
    if (!$trendData || count($trendData) < 1) {
        $_SESSION['info_message'] = "No stress assessment data available for this student.";
        header('Location: /GroupProject-IS2102/App/controller/AppointmentController.php?action=showPendingAppointments');
        exit();
    }
    
    // Get the latest assessment for current status
    $latestAssessment = $stressModel->getLatestStressAssessment($studentId);
    
    // Get appointment details if appointment ID is provided
    $appointmentDetails = null;
    if ($appointmentId) {
        $appointmentDetails = $this->model->getAppointmentById($appointmentId);
    }
    
    // Store the data in session for use in the view
    $_SESSION['student_details'] = $studentDetails;
    $_SESSION['stress_trend'] = $trendData;
    $_SESSION['latest_assessment'] = $latestAssessment;
    $_SESSION['appointment_id'] = $appointmentId;
    $_SESSION['appointment_details'] = $appointmentDetails;
    
    // Redirect to the view
    include '../views/counselling/counselor_view_student_stress.php';
}

    // Method to delete an appointment
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
        /**
/**
 * View all appointments
 */
public function viewAppointments() {
    session_start(); // Start the session first
    
    // Check if user is logged in as admin
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../login.php?error=unauthorized');
        exit();
    }
    
    // Get status filter if provided
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    
    // Get appointments based on status filter
    if ($status && in_array($status, ['Pending', 'Accepted', 'Denied'])) {
        $appointments = $this->model->getAppointmentsByStatus($status);
    } else {
        $appointments = $this->model->getAllAppointments();
    }
    
    // Get appointment counts by status
    $counts = $this->model->getAppointmentCountsByStatus();
    
    // Store data in session for view to access
    $_SESSION['appointments'] = $appointments;
    $_SESSION['appointment_counts'] = $counts;
    $_SESSION['selected_status'] = $status;
    
    // Redirect to appointments view
    header('Location: ../views/admin/admin_appointments/appointments.php');
    exit();
}

/**
 * View appointment details
 */
public function viewAppointmentDetails() {
    session_start(); // Start the session first
    
    // Check if user is logged in as admin
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../login.php?error=unauthorized');
        exit();
    }
    
    // Check if appointment ID is provided
    if (!isset($_GET['id'])) {
        $_SESSION['error_message'] = "Missing appointment ID.";
        header('Location: ../controller/AppointmentController.php?action=viewAppointments');
        exit();
    }
    
    $appointmentId = (int)$_GET['id'];
    
    // Get appointment details
    $appointment = $this->model->getDetailedAppointmentById($appointmentId);
    
    if (!$appointment) {
        $_SESSION['error_message'] = "Appointment not found.";
        header('Location: ../controller/AppointmentController.php?action=viewAppointments');
        exit();
    }
    
    // Store appointment in session for view to access
    $_SESSION['appointment_details'] = $appointment;
    
    // Redirect to appointment details view
    header('Location: ../views/admin/admin_appointments/appointment_details.php');
    exit();
}

    /**
 * Reschedule an approved appointment
 * This method updates the appointment date for approved appointments
 */
    // Method to reschedule an appointment
    public function rescheduleAppointment() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure the user is logged in as a counselor
        if (!isset($_SESSION['counselor']['id'])) {
            header('Location: ../views/counselling/counselor_login.php');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id']) && isset($_POST['new_date'])) {
            $appointmentId = $_POST['appointment_id'];
            $newDate = $_POST['new_date'];
            
            // Get the appointment details
            $appointment = $this->model->getAppointmentById($appointmentId);
            
            if (!$appointment) {
                $_SESSION['reschedule_error'] = "Appointment not found.";
                header('Location: ../controller/AppointmentController.php?action=showApprovedAppointments');
                exit();
            }
            
            // Validate the appointment belongs to this counselor
            if ($appointment['counselor_id'] != $_SESSION['counselor']['id']) {
                $_SESSION['reschedule_error'] = "You don't have permission to reschedule this appointment.";
                header('Location: ../controller/AppointmentController.php?action=showApprovedAppointments');
                exit();
            }
            
            // Validate the appointment is approved
            if ($appointment['status'] !== 'Accepted') {
                $_SESSION['reschedule_error'] = "Only approved appointments can be rescheduled.";
                header('Location: ../controller/AppointmentController.php?action=showApprovedAppointments');
                exit();
            }
            
            // Validate the new date is in the future
            if (strtotime($newDate) <= time()) {
                $_SESSION['reschedule_error'] = "Please select a future date and time.";
                header('Location: ../controller/AppointmentController.php?action=showApprovedAppointments');
                exit();
            }
            
            // Update the appointment date
            if ($this->model->rescheduleAppointment($appointmentId, $newDate)) {
                $_SESSION['reschedule_success'] = "Appointment successfully rescheduled to " . date('M d, Y h:i A', strtotime($newDate));
                
                // Get student details to use their name in the email
                $studentDetails = $this->model->getStudentDetails($appointment['student_id']);
                $studentName = $studentDetails ? $studentDetails['username'] : 'Student';
                
                // Send email notification to student about the reschedule
                $this->emailService->sendAppointmentStatusNotification(
                    $appointment['email'], 
                    $studentName, 
                    $appointment, 
                    'Rescheduled', 
                    $newDate
                );
            } else {
                $_SESSION['reschedule_error'] = "Failed to reschedule appointment. Please try again.";
            }
            
            header('Location: ../controller/AppointmentController.php?action=showApprovedAppointments');
            exit();
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
        case 'viewStudentStressTrend':
            $controller->viewStudentStressTrend();
            break; 
        case 'rescheduleAppointment':
            $controller->rescheduleAppointment();
            break;
        case 'viewAppointments':    
            $controller->viewAppointments();
            break;  
        case 'viewAppointmentDetails':
            $controller->viewAppointmentDetails();
            break;      

        default:
            echo 'Invalid action';
    }
}
