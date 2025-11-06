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
}