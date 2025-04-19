<?php
// App/services/EmailService.php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;
    private $senderEmail = 'ovinduguna@gmail.com';
    private $senderName = 'RelaxU Support';
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        // Configure SMTP settings
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com'; // Change to your SMTP host
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'ovinduguna@gmail.com'; // Change to your email
        $this->mailer->Password = 'qzdl nkch iqjt xkdp'; // Change to your password/app password
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = 587;
        $this->mailer->setFrom($this->senderEmail, $this->senderName);
    }
    
    // Send appointment status notification
    public function sendAppointmentStatusNotification($recipientEmail, $recipientName, $appointmentData, $status, $newDate = null) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($recipientEmail, $recipientName);
            
            // Set email subject based on status
            $subject = "RelaxU - Your Appointment has been $status";
            $this->mailer->Subject = $subject;
            
            // Format date for display
            $appointmentDate = date('F j, Y \a\t g:i a', strtotime($appointmentData['appointment_date']));
            
            // Prepare email body based on status
            if ($status === 'Accepted') {
                $body = $this->getAcceptedEmailTemplate($recipientName, $appointmentData, $appointmentDate);
            } elseif ($status === 'Denied') {
                $body = $this->getDeniedEmailTemplate($recipientName, $appointmentData, $appointmentDate);
            } elseif ($status === 'Rescheduled') {
                $newDateFormatted = date('F j, Y \a\t g:i a', strtotime($newDate));
                $body = $this->getRescheduledEmailTemplate($recipientName, $appointmentData, $appointmentDate, $newDateFormatted);
            } else {
                throw new Exception("Invalid status provided");
            }
            
            $this->mailer->isHTML(true);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags(str_replace('<br>', "\n", $body));
            
            return $this->mailer->send();
        } catch (Exception $e) {
            // Log the error
            error_log("Failed to send email to $recipientEmail: " . $e->getMessage());
            return false;
        }
    }
    
    // Get template for accepted appointments
    private function getAcceptedEmailTemplate($recipientName, $appointmentData, $appointmentDate) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h2 style='color: #009f77;'>Appointment Confirmed</h2>
            </div>
            <p>Hello $recipientName,</p>
            <p>Great news! Your counseling appointment has been <span style='color: #4CAF50; font-weight: bold;'>CONFIRMED</span>.</p>
            <div style='background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                <p><strong>Topic:</strong> {$appointmentData['topic']}</p>
                <p><strong>Date & Time:</strong> $appointmentDate</p>
            </div>
            <p>Please arrive 5 minutes before your scheduled time. If you need to cancel or reschedule, please do so at least 24 hours in advance.</p>
            <p>We look forward to speaking with you!</p>
            <p>Warm regards,<br>RelaxU Counseling Team</p>
        </div>";
    }
    
    // Get template for denied appointments
    private function getDeniedEmailTemplate($recipientName, $appointmentData, $appointmentDate) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h2 style='color: #009f77;'>Appointment Update</h2>
            </div>
            <p>Hello $recipientName,</p>
            <p>We regret to inform you that your counseling appointment request has been <span style='color: #f44336; font-weight: bold;'>DECLINED</span>.</p>
            <div style='background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                <p><strong>Topic:</strong> {$appointmentData['topic']}</p>
                <p><strong>Requested Date & Time:</strong> $appointmentDate</p>
            </div>
            <p>This could be due to scheduling conflicts or the counselor's availability. We encourage you to book another appointment at a different time or with another counselor.</p>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <p>Warm regards,<br>RelaxU Counseling Team</p>
        </div>";
    }
    
    // Get template for rescheduled appointments
    private function getRescheduledEmailTemplate($recipientName, $appointmentData, $originalDate, $newDate) {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h2 style='color: #009f77;'>Appointment Rescheduled</h2>
            </div>
            <p>Hello $recipientName,</p>
            <p>Your counseling appointment has been <span style='color: #2196F3; font-weight: bold;'>RESCHEDULED</span>.</p>
            <div style='background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                <p><strong>Topic:</strong> {$appointmentData['topic']}</p>
                <p><strong>Original Date & Time:</strong> <span style='text-decoration: line-through;'>$originalDate</span></p>
                <p><strong>New Date & Time:</strong> <span style='color: #2196F3;'>$newDate</span></p>
            </div>
            <p>If this new time doesn't work for you, please contact us to arrange a different time or to cancel the appointment.</p>
            <p>We look forward to speaking with you!</p>
            <p>Warm regards,<br>RelaxU Counseling Team</p>
        </div>";
    }
}
?>