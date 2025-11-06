<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once __DIR__ . '/../models/user.model.php';
class AuthService {

    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    private $secret = 'my_super_secret_key';

    public function generateToken($payload) {
        $payload['iat'] = time();
        $payload['exp'] = time() + 3600; // hết hạn sau 1h
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function verifyToken($token) {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }

    public function login($email, $password) {
      $user = $this->userModel->findByEmail($email);
      if (!$user) {
          return ResponseHelper::error("Email không tồn tại", null, 401);
      }

      if (!password_verify($password, $user['password'])) {
          return ResponseHelper::error("Sai mật khẩu", null, 401);
      }

           // Tạo payload
        $payload = [
            "id" => $user['id'],
            "email" => $user['email'],
            "role" => $user['role'],
            "exp" => time() + (60 * 60 * 24) // token sống 24h
        ];

        // Ký token
        $token = JWT::encode($payload, $this->secret, 'HS256');

        return ResponseHelper::success("Đăng nhập thành công", [
            "token" => $token,
            "user" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ], 200);
  }
}
