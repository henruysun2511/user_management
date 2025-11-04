<?php
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../middlewares/RoleMiddleware.php';

$controller = new UserController();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Đăng ký user mới (Public route - không cần authentication)
if (($uri === '/api/register' || $uri === '/user_management/api/register') && $method === 'POST') {
    $controller->register();
    exit;
}

// Lấy danh sách user
if (($uri === '/api/users' || $uri === '/user_management/api/users') && $method === 'GET') {
    //thêm auth midlleware ở đây
    RoleMiddleware::authorize('GET', '/api/users');
    echo $controller->getAllUsers();
}

// Cập nhật user
if (($uri === '/api/users' || $uri === '/user_management/api/users') && $method === 'PUT') {
      //thêm auth midlleware ở đây
    RoleMiddleware::authorize('PUT', '/api/users');
    $data = json_decode(file_get_contents("php://input"), true);
    echo $controller->updateUser($data);
}
?>