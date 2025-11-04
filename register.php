<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .required {
            color: red;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        select {
            cursor: pointer;
            background-color: white;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }

        .message.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .message.show {
            display: block;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-strength {
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .password-strength.weak {
            color: #dc3545;
            display: block;
        }

        .password-strength.medium {
            color: #ffc107;
            display: block;
        }

        .password-strength.strong {
            color: #28a745;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 Đăng ký tài khoản</h2>
        
        <div id="message" class="message"></div>

        <form id="registerForm">
            <div class="form-group">
                <label for="username">Tên đăng nhập <span class="required">*</span></label>
                <input type="text" id="username" name="username" required 
                       placeholder="Nhập tên đăng nhập (ít nhất 3 ký tự)">
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" required 
                       placeholder="example@email.com">
            </div>

            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" id="full_name" name="full_name" 
                       placeholder="Nguyễn Văn A">
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone" 
                       placeholder="0123456789">
            </div>

            <div class="form-group">
                <label for="birth">Ngày sinh</label>
                <input type="date" id="birth" name="birth">
            </div>

            <div class="form-group">
                <label for="gender">Giới tính</label>
                <select id="gender" name="gender">
                    <option value="">-- Chọn giới tính --</option>
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                    <option value="other">Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu <span class="required">*</span></label>
                <input type="password" id="password" name="password" required 
                       placeholder="Ít nhất 6 ký tự">
                <div id="passwordStrength" class="password-strength"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu <span class="required">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" required 
                       placeholder="Nhập lại mật khẩu">
            </div>

            <button type="submit" class="btn">Đăng ký</button>
        </form>

        <div class="login-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
        </div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const messageDiv = document.getElementById('message');
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');

        // Kiểm tra độ mạnh mật khẩu
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;

            passwordStrength.className = 'password-strength';
            
            if (password.length === 0) {
                passwordStrength.style.display = 'none';
            } else if (strength < 2) {
                passwordStrength.className += ' weak';
                passwordStrength.textContent = '⚠️ Mật khẩu yếu';
            } else if (strength < 4) {
                passwordStrength.className += ' medium';
                passwordStrength.textContent = '⚡ Mật khẩu trung bình';
            } else {
                passwordStrength.className += ' strong';
                passwordStrength.textContent = '✅ Mật khẩu mạnh';
            }
        });

        // Xử lý submit form
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Lấy dữ liệu từ form
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Validation cơ bản
            if (data.password !== data.confirm_password) {
                showMessage('Mật khẩu xác nhận không khớp!', 'error');
                return;
            }

            try {
                // Gửi request đến API
                const response = await fetch('api/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(result.message || 'Đăng ký thành công!', 'success');
                    form.reset();
                    
                    // Chuyển hướng sau 2 giây
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    showMessage(result.message || 'Đăng ký thất bại!', 'error');
                }
            } catch (error) {
                showMessage('Có lỗi xảy ra. Vui lòng thử lại!', 'error');
                console.error('Error:', error);
            }
        });

        function showMessage(message, type) {
            messageDiv.textContent = message;
            messageDiv.className = 'message ' + type + ' show';
            
            // Tự động ẩn sau 5 giây
            setTimeout(() => {
                messageDiv.className = 'message';
            }, 5000);
        }
    </script>
</body>
</html>

