<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class MailHelper {
    public static function sendOTP($email, $otp) {
        $mail = new PHPMailer(true);
        try {
            //Cấu hình smtp
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'huysun2511@gmail.com'; 
            $mail->Password = 'crpv ztez forp zbow'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Hệ thống gửi
            $mail->setFrom('huysun2511@gmail.com', 'Hệ thống');
            //Email nhận
            $mail->addAddress($email);

            //Nội dung
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