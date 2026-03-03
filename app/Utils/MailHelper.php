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
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['MAIL_PORT'];
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