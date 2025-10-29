<?php
require_once __DIR__ . '/../models/user.model.php';

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function getAllUsers($page, $limit, $search) {
        // Tính offset
        $offset = ($page - 1) * $limit;

        // Lấy dữ liệu
        $users = $this->userModel->getAll($limit, $offset, $search);
        $totalItems = $this->userModel->countAll($search);
        $totalPages = ceil($totalItems / $limit);

        return [
            "data" => $users,
            "pagination" => [
                "current_page" => $page,
                "total_pages" => $totalPages,
                "total_items" => $totalItems,
                "limit" => $limit
            ]
        ];
    }
}