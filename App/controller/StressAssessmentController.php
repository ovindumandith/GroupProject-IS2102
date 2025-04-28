<?php
session_start(); // Start the session to access user data
require_once '../models/StressAssessmentModel.php';
require_once '../models/User.php'; // Include the User class for user details
require_once '../../lib/fpdf/fpdf.php'; // Include FPDF library

// Create a custom PDF class
class StressTrendPDF extends FPDF {
    // Page header
    function Header() {
        // Logo
        $this->Image('../../assets/images/logo.jpg', 10, 10, 30);
        // Title
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(30); // Move to the right
        $this->Cell(130, 10, 'RelaxU - Stress Trend Report', 0, 0, 'C');
        // Date
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(30, 10, 'Generated: ' . date('Y-m-d'), 0, 0, 'R');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

class StressAssessmentController {
    private $model;

    public function __construct() {
        $this->model = new StressAssessmentModel();
    }

    /**
     * Handle all incoming requests
     */
    public function handleRequest() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php');
            exit();
        }

        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'submit_assessment':
                $this->submitAssessment();
                break;
            case 'view_records':
                $this->viewRecords();
                break;
            case 'view_details':
                $this->viewAssessmentDetails();
                break;
            case 'view_trend':
                $this->viewStressTrend();
                break;
            case 'view_all_assessments':
                $this->viewAllAssessments();
                break;
            case 'download_report':
                $this->downloadStressTrendReport();
                break;
                
            default:
                // If no action specified, show the assessment form
                header('Location: ../views/stress_management/stress_management_form.php');
                break;
        }
    }

    /**
     * Handle assessment form submission
     */
    private function submitAssessment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];

            // Validate and collect all responses
            $responses = [
                'section1_q1' => $this->validateInput($_POST['section1_q1'] ?? null, 0, 4),
                'section1_q2' => $this->validateInput($_POST['section1_q2'] ?? null, 0, 4),
                'section1_q3' => $this->validateInput($_POST['section1_q3'] ?? null, 0, 4),
                'section1_q4' => $this->validateInput($_POST['section1_q4'] ?? null, 0, 4),
                'section1_q5' => $this->validateInput($_POST['section1_q5'] ?? null, 0, 4),
                'section2_q1' => $this->validateInput($_POST['section2_q1'] ?? null, 0, 4),
                'section2_q2' => $this->validateInput($_POST['section2_q2'] ?? null, 0, 4),
                'section2_q3' => $this->validateInput($_POST['section2_q3'] ?? null, 0, 4),
                'section2_q4' => $this->validateInput($_POST['section2_q4'] ?? null, 0, 4),
                'section2_q5' => $this->validateInput($_POST['section2_q5'] ?? null, 0, 4)
            ];

            // Check if all responses are valid
            foreach ($responses as $response) {
                if ($response === false) {
                    $_SESSION['error_message'] = "Please provide valid responses to all questions.";
                    header('Location: ../views/stress_management/stress_management_form.php');
                    exit();
                }
            }

            // Save the assessment
            $result = $this->model->saveStressAssessment($userId, $responses);

            if ($result) {
                $_SESSION['success_message'] = "Your stress assessment has been submitted successfully.";
                header('Location: ../views/stress_management/assessment_results.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Failed to save your assessment. Please try again.";
                header('Location: ../views/stress_management/stress_management_form.php');
                exit();
            }
        } else {
            header('Location: ../views/stress_management/stress_management_form.php');
            exit();
        }
    }

    /**
     * Validate input to ensure it's within the specified range
     * 
     * @param mixed $input The input to validate
     * @param int $min Minimum valid value
     * @param int $max Maximum valid value
     * @return int|bool The validated input or false if invalid
     */
    private function validateInput($input, $min, $max) {
        if ($input === null || $input === '') {
            return false;
        }

        $value = (int) $input;
        if ($value < $min || $value > $max) {
            return false;
        }

        return $value;
    }

    /**
     * Retrieve and display user's assessment records
     */
    private function viewRecords() {
        $userId = $_SESSION['user_id'];
        $records = $this->model->getStressAssessmentRecords($userId);

        if ($records) {
            $_SESSION['assessment_records'] = $records;
        } else {
            $_SESSION['info_message'] = "No assessment records found.";
        }

        header('Location: ../views/stress_management/assessment_history.php');
        exit();
    }

    /**
     * View details of a specific assessment
     */
    private function viewAssessmentDetails() {
        if (isset($_GET['id'])) {
            $assessmentId = (int) $_GET['id'];
            $assessment = $this->model->getAssessmentById($assessmentId);

            if ($assessment && $assessment['user_id'] == $_SESSION['user_id']) {
                $_SESSION['assessment_details'] = $assessment;

                // Get recommended techniques based on stress level
                $_SESSION['recommended_techniques'] = $this->model->getRecommendedTechniques($assessment['stress_level']);

                header('Location: ../views/stress_management/assessment_details.php');
                exit();
            }
        }

        $_SESSION['error_message'] = "Assessment not found or access denied.";
        header('Location: ../views/stress_management/assessment_history.php');
        exit();
    }

    /**
     * View stress trend over time
     */
    private function viewStressTrend() {
        $userId = $_SESSION['user_id'];
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $trend = $this->model->getStressTrend($userId, $limit);

        if ($trend) {
            $_SESSION['stress_trend'] = $trend;
        } else {
            $_SESSION['info_message'] = "Not enough data to show stress trend.";
        }

        header('Location: ../views/stress_management/stress_trend.php');
        exit();
    }

    /**
     * View all stress assessments (Admin function)
     */
    public function viewAllAssessments() {
        session_start();

        // Check if user is logged in as admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/admin/admin_login.php');
            exit();
        }

        // Get all stress assessment records
        $allAssessments = $this->model->getAllStressAssessments();

        // Store in session for use in view
        $_SESSION['all_assessments'] = $allAssessments;

        // Redirect to admin view
        include '../views/admin/admin_stress_monitoring.php';
    }

    /**
     * Generate and download stress trend report
     */
    private function downloadStressTrendReport() {
        $userId = $_SESSION['user_id'];
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $trend = $this->model->getStressTrend($userId, $limit);

        if (!$trend || count($trend) < 2) {
            $_SESSION['info_message'] = "Not enough data to generate a report.";
            header('Location: ../views/stress_management/assessment_history.php');
            exit();
        }

        // Get user details
        $userModel = new User();
        $user = $userModel->getUserById($userId);

        // Generate and download the PDF report
        $this->generatePDFReport($trend, $user);
    }

    /**
     * Generate and download PDF report
     * 
     * @param array $trendData Stress trend data
     * @param array $userData User data
     */
    private function generatePDFReport($trendData, $userData) {
        // Initialize PDF
        $pdf = new StressTrendPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // User Information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'User Information', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'Name: ' . $userData['username'], 0, 1);
        $pdf->Cell(0, 6, 'Email: ' . $userData['email'], 0, 1);
        if (isset($userData['year'])) {
            $pdf->Cell(0, 6, 'Year: ' . $userData['year'], 0, 1);
        }
        $pdf->Ln(5);

        // Stress Trend Summary
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Stress Trend Summary', 0, 1);

        // Calculate trend insights
        $totalAssessments = count($trendData);
        $firstAssessment = $trendData[0];
        $latestAssessment = $trendData[$totalAssessments - 1];

        $section1Change = $latestAssessment['section1_score'] - $firstAssessment['section1_score'];
        $section2Change = $latestAssessment['section2_score'] - $firstAssessment['section2_score'];

        $stressIncreasing = $section1Change > 0 || $section2Change < 0;
        $stressDecreasing = $section1Change < 0 || $section2Change > 0;

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'Number of Assessments: ' . $totalAssessments, 0, 1);
        $pdf->Cell(0, 6, 'First Assessment Date: ' . date('Y-m-d', strtotime($firstAssessment['assessment_date'])), 0, 1);
        $pdf->Cell(0, 6, 'Latest Assessment Date: ' . date('Y-m-d', strtotime($latestAssessment['assessment_date'])), 0, 1);
        $pdf->Cell(0, 6, 'Current Stress Level: ' . $latestAssessment['stress_level'], 0, 1);
        $pdf->Ln(5);

        // Trend Analysis
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Trend Analysis', 0, 1);
        $pdf->SetFont('Arial', '', 10);

        if ($stressIncreasing) {
            $pdf->MultiCell(0, 6, 'Your stress levels appear to be ' .
                ($section1Change > 2 || $section2Change < -2 ? 'significantly' : 'slightly') .
                ' increasing over time. Consider implementing the recommended stress management techniques.');
        } elseif ($stressDecreasing) {
            $pdf->MultiCell(0, 6, 'Your stress levels appear to be ' .
                ($section1Change < -2 || $section2Change > 2 ? 'significantly' : 'slightly') .
                ' decreasing over time. Keep up the good work with your stress management techniques!');
        } else {
            $pdf->MultiCell(0, 6, 'Your stress levels have remained relatively stable over time.');
        }

        if ($latestAssessment['section1_score'] > 15 || $latestAssessment['section2_score'] < 5) {
            $pdf->Ln(3);
            $pdf->MultiCell(0, 6, 'Your current stress level is high. Consider consulting with a counselor for additional support.');
        }

        $pdf->Ln(5);

        // Assessment Data Table
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Assessment History', 0, 1);

        // Table header
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 7, 'Date', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Stress Perception', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Coping & Resilience', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Stress Level', 1, 1, 'C', true);

        // Table data
        $pdf->SetFont('Arial', '', 10);
        foreach ($trendData as $assessment) {
            $pdf->Cell(40, 6, date('Y-m-d', strtotime($assessment['assessment_date'])), 1, 0, 'C');
            $pdf->Cell(40, 6, $assessment['section1_score'], 1, 0, 'C');
            $pdf->Cell(40, 6, $assessment['section2_score'], 1, 0, 'C');
            $pdf->Cell(40, 6, $assessment['stress_level'], 1, 1, 'C');
        }

        $pdf->Ln(5);

        // Recommendations
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Recommendations', 0, 1);
        $pdf->SetFont('Arial', '', 10);

        // Get recommendations based on latest stress level
        $recommendations = $this->model->getRecommendedTechniques($latestAssessment['stress_level']);

        foreach ($recommendations as $index => $technique) {
            $pdf->Cell(0, 6, ($index + 1) . '. ' . $technique, 0, 1);
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->MultiCell(0, 6, 'Note: This report is generated based on your self-assessment data. For professional advice on stress management, please consult with a counselor or healthcare provider.');

        // Output the PDF
        $filename = 'StressTrendReport_' . date('Ymd') . '.pdf';
        $pdf->Output('D', $filename);
        exit();
    }
}

// Instantiate and run the controller
$controller = new StressAssessmentController();
$controller->handleRequest();
?>