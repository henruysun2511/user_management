# User Management System - Registration Module

## 📋 Tổng quan
Hệ thống quản lý người dùng với chức năng đăng ký, được xây dựng theo kiến trúc MVC với PHP và MySQL.

## 🚀 Quick Setup (2 phút!)

### 📖 Đọc file này trước: `QUICK_START.md`

### Tóm tắt:
1. ✅ Database `quanlynguoidung` đã được kết nối trong `database.php`
2. 📝 Chỉ cần tạo bảng `users` bằng cách chạy file `create_users_table.sql` trong phpMyAdmin
3. 🎉 Xong! Truy cập `register.php` để test

## 🚀 Cài đặt Chi Tiết

### 1. Tạo Bảng Users
**Bước 1:** Mở phpMyAdmin: `http://localhost/phpmyadmin`  
**Bước 2:** Chọn database `quanlynguoidung`  
**Bước 3:** Click tab "SQL"  
**Bước 4:** Copy nội dung từ file `create_users_table.sql` và paste vào  
**Bước 5:** Click "Go"

### 2. Database Connection
✅ Đã được cấu hình sẵn trong `database/database.php`:
```php
private $host = "127.0.0.1";
private $db_name = "quanlynguoidung";  // ✅ Database của bạn
private $username = "root";             // ✅ XAMPP default
private $password = "";                 // ✅ XAMPP default
```

### 3. Khởi động XAMPP
- Khởi động Apache
- Khởi động MySQL
- Xong!

## 📂 Cấu trúc thư mục

```
user_management/
├── database/
│   ├── database.php          # Database connection class
│   └── schema.sql            # Database schema
├── models/
│   └── user.model.php        # User model
├── services/
│   └── user.service.php      # Business logic
├── controllers/
│   └── user.controller.php   # Request handlers
├── routes/
│   └── user.route.php        # API routes
├── helpers/
│   └── responseHelper.php    # Response formatter
├── middlewares/
│   └── role.middleware.php   # Authorization middleware
├── register.php              # Registration form
├── login.php                 # Login form
└── index.php                 # Entry point
```

## 🔧 Sử dụng

### Đăng ký qua Web Form
1. Truy cập: `http://localhost/user_management/register.php`
2. Điền thông tin:
   - Tên đăng nhập (bắt buộc, ít nhất 3 ký tự)
   - Email (bắt buộc, phải hợp lệ)
   - Họ và tên (tùy chọn)
   - Số điện thoại (tùy chọn)
   - Mật khẩu (bắt buộc, ít nhất 6 ký tự)
   - Xác nhận mật khẩu (bắt buộc)
3. Click "Đăng ký"

### Đăng ký qua API
**Endpoint:** `POST /api/register`

**Request (JSON):**
```json
{
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "confirm_password": "password123",
  "full_name": "John Doe",
  "phone": "0123456789"
}
```

**Response thành công (201):**
```json
{
  "success": true,
  "message": "Đăng ký thành công!",
  "data": {
    "id": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "full_name": "John Doe",
    "phone": "0123456789",
    "role": "user",
    "status": "active",
    "created_at": "2024-01-01 10:00:00"
  }
}
```

**Response lỗi (400):**
```json
{
  "success": false,
  "message": "Email đã được sử dụng"
}
```

## ✅ Validation Rules

### Username:
- Không được để trống
- Ít nhất 3 ký tự
- Chỉ chứa chữ cái, số và dấu gạch dưới
- Không được trùng trong database

### Email:
- Không được để trống
- Phải đúng định dạng email
- Không được trùng trong database

### Password:
- Không được để trống
- Ít nhất 6 ký tự
- Được mã hóa bằng bcrypt trước khi lưu

## 🔒 Security Features

1. **Password Hashing:** Sử dụng `password_hash()` với BCRYPT
2. **SQL Injection Prevention:** Sử dụng PDO Prepared Statements
3. **Input Validation:** Kiểm tra và làm sạch dữ liệu đầu vào
4. **Email Validation:** Sử dụng `filter_var()` với FILTER_VALIDATE_EMAIL
5. **Duplicate Prevention:** Kiểm tra email và username trùng lặp

## 🧪 Testing

### Test với cURL:
```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "testuser",
    "email": "test@example.com",
    "password": "test123",
    "confirm_password": "test123",
    "full_name": "Test User"
  }'
```

### Test với Postman:
1. Method: POST
2. URL: `http://localhost/api/register`
3. Headers: `Content-Type: application/json`
4. Body (raw JSON): Như ví dụ trên

## 📊 Database Schema

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🎯 Next Steps

- [ ] Implement login functionality
- [ ] Add email verification
- [ ] Add forgot password feature
- [ ] Add user profile management
- [ ] Add JWT authentication
- [ ] Add rate limiting
- [ ] Add CSRF protection

## 🐛 Troubleshooting

### Lỗi: "Kết nối thất bại"
- Kiểm tra MySQL server đã chạy chưa
- Kiểm tra thông tin kết nối trong `database/database.php`
- Đảm bảo database đã được tạo

### Lỗi: "Email đã được sử dụng"
- Email đã tồn tại trong database
- Thử với email khác hoặc xóa user cũ

### Lỗi 404 khi gọi API
- Kiểm tra URL có đúng không
- Đảm bảo Apache rewrite module đã bật
- Kiểm tra file `index.php` có load routes đúng không

## 📝 License
MIT License

## 👨‍💻 Author
Your Name

