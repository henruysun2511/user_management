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

    // Đăng ký user mới
    public function register($data) {
        // Validation
        $errors = $this->validateRegistration($data);
        if (!empty($errors)) {
            throw new Exception(implode(", ", $errors));
        }

        // Kiểm tra email đã tồn tại
        if ($this->userModel->findByEmail($data['email'])) {
            throw new Exception("Email đã được sử dụng");
        }

        // Kiểm tra username đã tồn tại
        if ($this->userModel->findByUsername($data['username'])) {
            throw new Exception("Tên đăng nhập đã được sử dụng");
        }

        // Mã hóa mật khẩu
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Chuẩn bị dữ liệu
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'full_name' => $data['full_name'] ?? '',
            'phone' => $data['phone'] ?? '',
            'birth' => $data['birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'role' => 'user', // Mặc định là user
            'status' => 'active' // Mặc định là active, có thể đổi thành 'pending' nếu cần verify email
        ];

        // Tạo user
        $userId = $this->userModel->create($userData);
        
        if ($userId) {
            // Lấy thông tin user vừa tạo (không bao gồm password)
            $user = $this->userModel->getById($userId);
            unset($user['password']);
            return $user;
        }

        throw new Exception("Đăng ký thất bại. Vui lòng thử lại!");
    }

    // Validate dữ liệu đăng ký
    private function validateRegistration($data) {
        $errors = [];

        // Kiểm tra username
        if (empty($data['username'])) {
            $errors[] = "Tên đăng nhập không được để trống";
        } elseif (strlen($data['username']) < 3) {
            $errors[] = "Tên đăng nhập phải có ít nhất 3 ký tự";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            $errors[] = "Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới";
        }

        // Kiểm tra email
        if (empty($data['email'])) {
            $errors[] = "Email không được để trống";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ";
        }

        // Kiểm tra password
        if (empty($data['password'])) {
            $errors[] = "Mật khẩu không được để trống";
        } elseif (strlen($data['password']) < 6) {
            $errors[] = "Mật khẩu phải có ít nhất 6 ký tự";
        }

        // Kiểm tra confirm password
        if (isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
            $errors[] = "Mật khẩu xác nhận không khớp";
        }

        return $errors;
    }
}