<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';
require_once __DIR__ . '/../lib/PHPMailer/Exception.php';

function sendResultEmail($toEmail, $studentName) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pranjalibodke10@gmail.com';
        $mail->Password   = 'rvvwzdsjkkzgpzuj'; // ✅ NO SPACES
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // 🔎 Debug (remove later)
        $mail->SMTPDebug = 0;

        $mail->setFrom('pranjalibodke10@gmail.com', 'Student Result System');
        $mail->addAddress($toEmail, $studentName);

        $mail->isHTML(true);
        $mail->Subject = 'Result Published';
        $mail->Body = "
            <h3>Hello $studentName,</h3>
            <p>Your result has been published.</p>
            <p>Please login to the portal to view your result.</p>
            <br>
            <b>Student Result Management System</b>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo; // show exact error
    }
}
