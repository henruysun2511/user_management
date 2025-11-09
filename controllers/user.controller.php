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
            echo ResponseHelper::success("Lấy danh sách người dùng thành công", $result);
         } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
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

    public function updateUser($id){
        $input = json_decode(file_get_contents('php://input'), true);
        $fullName = $input['fullName'] ?? null;
        $gender = $input['gender'] ?? null;
        $phoneNumber = $input['phoneNumber'] ?? null;
        $role_id = $input['role_id'] ?? '';

        $data = [
            'fullName' => $fullName,
            'gender' => $gender,
            'phoneNumber' => $phoneNumber,
            'role_id' => $role_id
        ];

        $errors = ValidatorHelper::validateUserUpdate($data);
        if (!empty($errors)) {
            throw new Exception(implode(", ", $errors));
        }

        try {
            $user = $this->userService->updateUser($id, $data);
            echo ResponseHelper::success("Cập nhật thông tin người dùng thành công", $user);
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi" . $e->getMessage(), 400);
        }
    }


    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        $fullName = $input['fullName'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        $gender = $input['gender'] ?? null;
        $phoneNumber = $input['phoneNumber'] ?? null;
        $role_id = $input['role_id'] ?? '';;
        $birth = $input['birth'] ?? null;

        if (empty($fullName) || empty($email) || empty($password)) {
            echo ResponseHelper::error("Vui lòng nhập đầy đủ thông tin", null, 400);
            return;
        }

        if (empty($role_id)) {
            echo ResponseHelper::error("Chưa gán vai trò cho người dùng", null, 400);
            return;
        }

         $data = [
            'fullName' => $fullName,
            'email' => $email,
            'password' => $password,
            'gender' => $gender,
            'phoneNumber' => $phoneNumber,
            'role_id' => $role_id,
            'birth' => $birth
        ];

        $errors = ValidatorHelper::validateRegistration($data);
        if (!empty($errors)) {
            throw new Exception(implode(", ", $errors));
        }

        try {
            $user = $this->authService->register($data);
            echo ResponseHelper::success("Đăng ký thành công", $user);
        } catch (Exception $e) {
            echo ResponseHelper::error("Đăng ký thất bại" . $e->getMessage(), 400);
        }
    }

    public function forgotPassword() {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        if (empty($email)) {
            return ResponseHelper::error("Email không được để trống", null, 400);
        }
        try{
            $result = $this->authService->sendOtp($email);
            echo ResponseHelper::success("Đã gửi otp đến email của bạn", $result);
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function verifyOtp() {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $otp = $input['otp'] ?? '';

        if (empty($otp)) {
            return ResponseHelper::error("Vui lòng nhập mã otp", null, 400);
        }

        try{
            $result = $this->authService->verifyToken($email, $otp);
            echo ResponseHelper::success("Xác thực otp thành công", $result);
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function resetPassword() {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $newPassword = $input['newPassword'] ?? '';
        
        if (empty($newPassword)) {
            return ResponseHelper::error("Vui lòng nhập mật khẩu", null, 400);
        }
        try{
            $result = $this->authService->resetPassword($email, $newPassword);
            echo ResponseHelper::success("Cập nhật mật khẩu thành công", $result);
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }
}