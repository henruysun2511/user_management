<?php
require_once __DIR__ . '/../services/user.service.php';
require_once __DIR__ . '/../helpers/responseHelper.php';

class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
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

    // Đăng ký user mới
    public function register() {
        try {
            // Lấy dữ liệu từ request
            $data = [];
            
            // Kiểm tra content-type để xử lý dữ liệu phù hợp
            $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            
            if (strpos($contentType, 'application/json') !== false) {
                // Nếu là JSON
                $json = file_get_contents("php://input");
                $data = json_decode($json, true);
            } else {
                // Nếu là form data
                $data = $_POST;
            }

            // Gọi service để đăng ký
            $user = $this->userService->register($data);
            
            echo ResponseHelper::success("Đăng ký thành công!", $user, 201);
        } catch (Exception $e) {
            echo ResponseHelper::error($e->getMessage(), 400);
        }
    }
}