<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    public static function sendOrderConfirmation($toEmail, $order, $items, $isBankConfirmed = false) {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình Server SMTP lấy từ file .env
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'] ?? getenv('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME');
            $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['MAIL_PORT'] ?? getenv('MAIL_PORT') ?? 587;
            $mail->CharSet    = 'UTF-8';

            // Người gửi & Người nhận
            $mail->setFrom($mail->Username, 'EcoStore - Sống Xanh');
            $mail->addAddress($toEmail, $order['ship_name'] ?? 'Khách hàng');

            ob_start();
            require __DIR__ . '/../Views/emails/order_success.php'; 
            $body = ob_get_clean();

            $mail->isHTML(true);
            if ($isBankConfirmed) {
                $mail->Subject = "Thanh toán thành công đơn hàng #" . $order['order_number'];
            } else {
                $mail->Subject = "Xác nhận đặt hàng thành công #" . $order['order_number'];
            }
            
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Lỗi gửi mail PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }
}