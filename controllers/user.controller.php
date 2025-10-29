<?php
require_once __DIR__ . '/../services/UserService.php';

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
            echo ResponseMessage::success("Lấy danh sách người dùng thành công", $result);
         } catch (Exception $e) {
            echo ResponseMessage::error("Lỗi: " . $e->getMessage(), 500);
        }
    }
}