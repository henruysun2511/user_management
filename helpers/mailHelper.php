<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class MailHelper {
    public static function sendOTP($email, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; 
            $mail->Password = 'your_app_password'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your_email@gmail.com', 'Hệ thống');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Mã OTP khôi phục mật khẩu';
            $mail->Body = "Mã OTP của bạn là: <b>$otp</b>. Mã này sẽ hết hạn sau 5 phút.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}