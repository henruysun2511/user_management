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

      // Tạo session
      session_start();
      $_SESSION['user'] = [
          'id' => $user['id'],
          'email' => $user['email'],
          'role' => $user['role']
      ];

      return ResponseHelper::success("Đăng nhập thành công", $_SESSION['user'],200);
  }
}
