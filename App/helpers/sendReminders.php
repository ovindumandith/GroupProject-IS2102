<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // Adjust path if needed
require_once __DIR__.'/../models/ScheduleEvent.php';
require_once __DIR__.'/Mailer.php';



$event = new ScheduleEvent();
$upcomingEvents = $event->getAllEvents(); // Fetch all upcoming events

$now = new DateTime();
$targetDate = $now->modify('+2 days')->format('Y-m-d');

foreach ($upcomingEvents as $e) {
    $startDate = date('Y-m-d', strtotime($e['date']));
    if ($startDate === $targetDate) {
        $title = $e['title'];
        $start = $e['start_time'];
        $end = $e['end_time'];
        $email = "08nijaanyt@gmail.com"; //Replace with dynamic user email from DB

        // Build reminder message
        $subject = "⏰ Upcoming Event Reminder - $title";
        $body = "
            <div style=\"font-family: Arial, sans-serif;\">
                <h3 style=\"color:#e67e22;\">📅 Just a quick reminder!</h3>
                <p>Your event <strong>$title</strong> is happening soon!</p>
                <p><strong>Start:</strong> $start<br>
                <strong>End:</strong> $end</p>
                <p>We're looking forward to it!</p>
                <p><strong>– Flex To Flow</strong></p>
            </div>
        ";

        // Send the email
        Mailer::sendConfirmationEmail($email, $title, $subject, $body);
    }
}
