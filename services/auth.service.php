<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../models/user.model.php';
require_once __DIR__ . '/../models/otp.model.php';
require_once __DIR__ . '/../helpers/mailHelper.php';
require_once __DIR__ . '/../helpers/responseHelper.php';
require_once __DIR__ . '/../helpers/validatorHelper.php';
require_once __DIR__ . '/../models/token.model.php';

class AuthService {
    private $userModel;
    private $tokenModel;
    private $otpModel;
    private $secret = 'my_super_secret_key';

    public function __construct() {
        $this->userModel = new UserModel();
        $this->otpModel = new OtpModel();
        $this->tokenModel=new TokenModel();
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

        // Access Token (sống ngắn)
        $accessPayload = [
            "id" => $user['id'],
            "email" => $user['email'],
            "role" => $user['role_id'],
            "exp" => time() + (60 * 15) // 15 phút
        ];
        $accessToken = JWT::encode($accessPayload, $this->secret, 'HS256');

        // Refresh Token (sống dài)
        $refreshPayload = [
            "id" => $user['id'],
            "type" => "refresh",
            "exp" => time() + (60 * 60 * 24 * 7) // 7 ngày
        ];
        $refreshToken = JWT::encode($refreshPayload, $this->secret, 'HS256');

        setcookie('refreshToken', $refreshToken, time() + 60*60*24*7, '/', '', false, true);


        // Lưu refreshToken vào DB
        $this->tokenModel->create($user['id'], $refreshToken);

        return ResponseHelper::success("Đăng nhập thành công", [
            "accessToken" => $accessToken,
            "refreshToken" => $refreshToken,
            "user" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "role" => $user['role_id']
            ]
        ]);
}

    public function register($data) {
        // Kiểm tra email đã tồn tại
        if ($this->userModel->findByEmail($data['email'])) {
            return ResponseHelper::error("Email đã được sử dụng", null, 401);
        }

        // Kiểm tra username đã tồn tại
        if ($this->userModel->findByUsername($data['fullName'])) {
            return ResponseHelper::error("Tên đăng nhập đã được sử dụng", null, 401);
        }

        // Mã hóa mật khẩu
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Chuẩn bị dữ liệu
        $userData = [
            'email' => $data['email'],
            'password' => $hashedPassword,
            'fullName' => $data['fullName'] ?? '',
            'phoneNumber' => $data['phoneNumber'] ?? '',
            'birth' => $data['birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'role_id' => 1, 
        ];

        // Tạo user
        $userId = $this->userModel->create($userData);
        
        if ($userId) {
            // Lấy thông tin user vừa tạo (không bao gồm password)
            $user = $this->userModel->getById($userId);
            unset($user['password']);
            return $user;
        }
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

    public function verifyOtp($email, $otp){
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
        return null;
    }

    // Đặt lại mật khẩu
    public function resetPassword($email, $newPassword) {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            return ResponseHelper::error("Email không tồn tại", null, 404);
        }

        $user_id = $user['id'];
        
        $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->userModel->updatePassword($user_id, $hashPassword);
    }

    public function refresh($refreshToken) {
        try {
            $decoded = JWT::decode($refreshToken, new Key($this->secret, 'HS256'));
            
            $tokenRecord = $this->tokenModel->find($refreshToken);
            if (!$tokenRecord) return ResponseHelper::error("refreshToken không hợp lệ", null, 401);

            // Kiểm tra refresh token hết hạn
            if ($decoded->exp < time()) {
                return ResponseHelper::error("Refresh token đã hết hạn", null, 401);
            }

            $user= $this->userModel->getById($decoded->id);

            // Tạo access token mới
            $newAccessPayload = [
                "id" => $decoded->id,
                "email" => $user['email'],
                "role" => $user['role_id'],
                "exp" => time() + (60 * 15)
            ];
            $newAccessToken = JWT::encode($newAccessPayload, $this->secret, 'HS256');

            return ResponseHelper::success("Làm mới token thành công", [
                "accessToken" => $newAccessToken
            ]);

        } catch (Exception $e) {
            return ResponseHelper::error("Refresh token sai hoặc hết hạn", null, 401);
        }
    }
    
    
    public function logout($refreshToken) {
        $this->tokenModel->delete($refreshToken);
         // Xóa cookie
        setcookie(
            'refreshToken',
            '',
            time() - 3600,
            '/',
            '',
            false,
            true // HttpOnly
        );
        return ResponseHelper::success("Đăng xuất thành công");
    }


}
