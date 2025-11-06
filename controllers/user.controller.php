<?php
require_once __DIR__ . '/../services/user.service.php';
require_once __DIR__ . '/../services/auth.service.php';


class UserController {
    private $userService;
    private $authService;
    
    public function __construct() {
        $this->userService = new UserService();
        $this->authService = new AuthService();
    }

    public function getAllUsers() {
        // Lấy query params
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        try {
            $result = $this->userService->getAllUsers($page, $limit, $search);
            echo ResponseMessage::success("Lấy danh sách người dùng thành công", $result);
         } catch (Exception $e) {
            echo ResponseMessage::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function getProfile($userInfo) {
        return ResponseHelper::success("Thông tin người dùng", $userInfo);
    }

    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        if (empty($email) || empty($password)) {
            return ResponseHelper::error("Thiếu email hoặc mật khẩu", null, 400);
        }

        return $this->authService->login($email, $password);
    }

    public function forgotPassword() {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        if (empty($email)) {
            return ResponseHelper::error("Email không được để trống", null, 400);
        }
        try{
            $result = $this->authService->sendOtp($email);
            echo ResponseMessage::success("Đã gửi otp đến email của bạn", $result);
        } catch (Exception $e) {
            echo ResponseMessage::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function resetPassword() {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $otp = $input['otp'] ?? '';
        $newPassword = $input['password'] ?? '';

        if (empty($otp)) {
            return ResponseHelper::error("Vui lòng nhập mã otp", null, 400);
        }
        
        if (empty($newPassword)) {
            return ResponseHelper::error("Vui lòng nhập mật khẩu", null, 400);
        }
        try{
            $result = $this->authService->resetPassword($email, $otp, $newPassword);
            echo ResponseMessage::success("Cập nhật mật khẩu thành công", $result);
        } catch (Exception $e) {
            echo ResponseMessage::error("Lỗi: " . $e->getMessage(), 500);
        }
    }
}