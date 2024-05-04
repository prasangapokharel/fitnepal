<?php
include 'db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

// Function to send email
function send_email($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings (adjust to match your email provider's settings)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Example using Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'prasangaramanpokharel@gmail.com'; // Your email
        $mail->Password = 'In@ruwa1'; // Email password or app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // 'tls' or 'ssl'
        $mail->Port = 465; // SMTP port for 'tls' (use 465 for 'ssl')

        // Email details
        $mail->setFrom('prasangaramanpokharel@gmail.com', 'Prasanga Raman Pokharel'); // Adjust as needed
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send the email
        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Return false if email sending fails
    }
}
