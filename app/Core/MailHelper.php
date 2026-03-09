<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    public static function sendOrderConfirmation($toEmail, $order, $items, $isBankConfirmed = false) {
        $mail = new PHPMailer(true);

        try {
            // 1. CẤU HÌNH CƠ BẢN
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'] ?? getenv('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME');
            $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['MAIL_PORT'] ?? getenv('MAIL_PORT') ?? 587;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom($mail->Username, 'EcoStore - Sống Xanh');
            $mail->isHTML(true);

            // ==========================================
            // LUỒNG 1: GỬI BIÊN LAI CHO KHÁCH HÀNG
            // ==========================================
            $mail->addAddress($toEmail, $order['ship_name'] ?? 'Khách hàng');
            
            ob_start();
            require __DIR__ . '/../Views/emails/order_success.php'; 
            $bodyClient = ob_get_clean();

            if ($isBankConfirmed) {
                $mail->Subject = "Thanh toán thành công đơn hàng #" . $order['order_number'];
            } else {
                $mail->Subject = "Xác nhận đặt hàng thành công #" . $order['order_number'];
            }
            $mail->Body = $bodyClient;
            
            // Thực hiện gửi cho khách
            $mail->send();

            // ==========================================
            // LUỒNG 2: GỬI THÔNG BÁO CHO ADMIN
            // ==========================================
            // Xóa toàn bộ người nhận cũ (Khách hàng) để không bị gửi lặp
            $mail->clearAddresses();
            
            // Add email của cửa hàng vào để nhận
            $mail->addAddress($mail->Username, 'Admin EcoStore');
            
            ob_start();
            require __DIR__ . '/../Views/emails/admin_new_order.php'; 
            $bodyAdmin = ob_get_clean();

            $mail->Subject = "[EcoStore] CÓ ĐƠN HÀNG MỚI #" . $order['order_number'];
            $mail->Body = $bodyAdmin;
            
            // Thực hiện gửi cho Admin
            $mail->send();

            return true;
        } catch (Exception $e) {
            error_log("Lỗi gửi mail PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }
}