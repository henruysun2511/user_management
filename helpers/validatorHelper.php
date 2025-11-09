<?php
class ValidatorHelper {
    public static function validateRegistration($data) {
        $errors = [];

        // Kiểm tra username
        if (empty($data['fullName'])) {
            $errors[] = "Tên đăng nhập không được để trống";
        } elseif (strlen($data['fullName']) < 3) {
            $errors[] = "Tên đăng nhập phải có ít nhất 3 ký tự";
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

        // Kiểm tra số điện thoại 
        if (!empty($data['phone']) && !preg_match('/^[0-9]{9,11}$/', $data['phone'])) {
            $errors[] = "Số điện thoại không hợp lệ";
        }

        // Kiểm tra giới tính 
        if (isset($data['gender']) && !in_array($data['gender'], ['male', 'female', 'other', '', null])) {
            $errors[] = "Giới tính không hợp lệ";
        }

        return $errors;
    }

    public static function validateUserUpdate($data) {
    $errors = [];

    // Kiểm tra full name (nếu có)
    if (isset($data['fullName']) && strlen(trim($data['fullName'])) < 2) {
        $errors[] = "Họ tên phải có ít nhất 2 ký tự";
    }

    // Kiểm tra giới tính
    if (isset($data['gender']) && !in_array($data['gender'], ['male', 'female', 'other', null, ''])) {
        $errors[] = "Giới tính không hợp lệ";
    }

    // Kiểm tra số điện thoại
    if (isset($data['phoneNumber']) && !empty($data['phoneNumber']) && !preg_match('/^[0-9]{9,11}$/', $data['phoneNumber'])) {
        $errors[] = "Số điện thoại không hợp lệ";
    }

    // Kiểm tra role_id
    if (isset($data['role_id']) && !is_numeric($data['role_id'])) {
        $errors[] = "Role ID không hợp lệ";
    }

    return $errors;
}
}