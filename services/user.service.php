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

    public function updateUser($id, $data) {
    $user = $this->userModel->getById($id);
    if (!$user) {
        throw new Exception("Không tìm thấy người dùng");
    }

    // Gộp dữ liệu: nếu không gửi thì giữ nguyên
    $updateData = [
        'fullName' => $data['fullName'] ?? $user['fullName'],
        'gender' => $data['gender'] ?? $user['gender'],
        'phoneNumber' => $data['phoneNumber'] ?? $user['phoneNumber'],
        'role_id' => $data['role_id'] ?? $user['role_id']
    ];

    $updated = $this->userModel->update($id, $updateData);
    if (!$updated) {
        throw new Exception("Không thể cập nhật thông tin người dùng");
    }

    $newUser = $this->userModel->getById($id);
    unset($newUser['password']);
    return $newUser;
}
}