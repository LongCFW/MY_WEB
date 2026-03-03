<?php
namespace App\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    public static function sendMail($toEmail, $toName, $subject, $body) {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình Server (Nên dùng Gmail SMTP)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'imlongmanhme@gmail.com';
            $mail->Password   = 'ghbvxyzenpguuumq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Người gửi, Người nhận
            $mail->setFrom('imlongmanhme@gmail.com', 'EcoStore Support');
            $mail->addAddress($toEmail, $toName);

            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}