<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../models/user.model.php';
require_once __DIR__ . '/../models/otp.model.php';
require_once __DIR__ . '/../helpers/mailHelper.php';
require_once __DIR__ . '/../helpers/responseHelper.php';

class AuthService {
    private $userModel;
    private $otpModel;
    private $secret = 'my_super_secret_key';

    public function __construct() {
        $this->userModel = new UserModel();
        $this->otpModel = new OtpModel();
    }

    // Tạo token JWT
    public function generateToken($payload) {
        $payload['iat'] = time();
        $payload['exp'] = time() + 3600;
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    // Kiểm tra token
    public function verifyToken($token) {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }

    // Đăng nhập
    public function login($email, $password) {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            return ResponseHelper::error("Email không tồn tại", null, 401);
        }

        if (!password_verify($password, $user['password'])) {
            return ResponseHelper::error("Sai mật khẩu", null, 401);
        }

        $payload = [
            "id" => $user['id'],
            "email" => $user['email'],
            "role" => $user['role'],
            "exp" => time() + (60 * 60 * 24)
        ];

        $token = JWT::encode($payload, $this->secret, 'HS256');

        return ResponseHelper::success("Đăng nhập thành công", [
            "token" => $token,
            "user" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ]);
    }

    // Gửi OTP
    public function sendOtp($email) {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            return ResponseHelper::error("Email không tồn tại", null, 404);
        }

        $user_id = $user['id'];
        $otp = rand(100000, 999999);
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $saveOtp = $this->otpModel->saveOtp($user_id, $otp, $expires_at);

        if ($saveOtp && MailHelper::sendOTP($email, $otp)) {
            return ResponseHelper::success("Đã gửi mã OTP tới email của bạn");
        }

        return null;
    }

    // Đặt lại mật khẩu
    public function resetPassword($email, $otp, $newPassword) {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            return ResponseHelper::error("Email không tồn tại", null, 404);
        }

        $user_id = $user['id'];
        $verify = $this->otpModel->verifyOtp($user_id, $otp);

        if (!$verify) {
            return ResponseHelper::error("Mã OTP không đúng", null, 400);
        }

        if (strtotime($verify['expires_at']) < time()) {
            return ResponseHelper::error("Mã OTP đã hết hạn", null, 400);
        }

        $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        return $this->userModel->updatePassword($user_id, $hashPassword);
    }
}
