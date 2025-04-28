<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer Autoloader
require_once __DIR__ . '/../../vendor/autoload.php'; // Adjust based on file location

class Mailer
{
    public static function sendConfirmationEmail($customerEmail, $title, $start, $end)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            // TODO: Load these securely from env/config
            $mail->Username = 'theepankarthee224@gmail.com';
            $mail->Password = 'qwgx rmrp rsls bmjg';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('mydynamica@gmail.com', 'Flex To Flow');
            $mail->addAddress($customerEmail, $title);

            $mail->Subject = "Appointment Confirmation";
            $mail->isHTML(true);
            $mail->Body = "
            <div style=\"font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; padding: 20px;\">
                <div style=\"background-color: #f3f9ff; border-left: 6px solid #3498db; padding: 15px; border-radius: 5px;\">
                    <h2 style=\"color: #3498db; margin-top: 0;\">🎉 Your Event Has Been Created!</h2>
                    <p style=\"font-size: 16px;\">Hi <strong>$title</strong>,</p>
                    <p style=\"font-size: 15px;\">We're excited to confirm that your event has been successfully scheduled. Here are the details:</p>
                    
                    <ul style=\"list-style: none; padding: 0; font-size: 15px;\">
                        <li><strong>📌 Title:</strong> $title</li>
                        <li><strong>🕒 Start:</strong> $start</li>
                        <li><strong>⏰ End:</strong> $end</li>
                    </ul>

                    <p style=\"margin-top: 20px; font-size: 15px;\">
                        You’ll receive a reminder before your event. If you need to make changes, just reach out to us!
                    </p>
                </div>

                <p style=\"font-size: 14px; margin-top: 30px;\">Thanks for using <strong>RelaxU</strong>!<br>
                <em>We’re here to make your schedule flow smoothly.</em></p>
            </div>
";

            $mail->send();
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $mail->ErrorInfo];
        }
    }
}
