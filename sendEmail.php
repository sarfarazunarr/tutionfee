<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files
require '../packages/PHPMailer-master/src/Exception.php';
require '../packages/PHPMailer-master/src/PHPMailer.php';
require '../packages/PHPMailer-master/src/SMTP.php';

function sendEmail($to, $name, $body, $subject) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                           // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';            // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                    // Enable SMTP authentication
        $mail->Username = 'myemail';  // SMTP username
        $mail->Password = 'mypass';         // SMTP password
        $mail->SMTPSecure = 'tls';                 // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                         // TCP port to connect to

        // Recipients
        $mail->setFrom('myemail', 'name');
        $mail->addAddress($to, $name);             // Add a recipient

        // Content
        $mail->isHTML(true);                       // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = "Dear $name,<br><br>$body";
        $mail->AltBody = "Dear $name,\n\n$body";

        $mail->send();
        return 'Email has been sent';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
