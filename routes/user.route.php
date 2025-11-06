<?php
require_once __DIR__ . '/../controllers/user.controller.php';
require_once __DIR__ . '/../middlewares/role.middleware.php';

$controller = new UserController();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Lấy danh sách user
if ($uri === '/api/users' && $method === 'GET') {
    //thêm auth midlleware ở đây
    RoleMiddleware::authorize('GET', '/api/users');
    echo $controller->getAllUsers();
}

// Cập nhật user
if ($uri === '/api/users' && $method === 'PATCH') {
    //thêm auth midlleware ở đây
    RoleMiddleware::authorize('PATCH', '/api/users');
    $data = json_decode(file_get_contents("php://input"), true);
    echo $controller->updateUser($data);
}


if (preg_match('#^/api/users/(\d+)$#', $path, $matches) && $method === 'PATCH') {
    RoleMiddleware::authorize('PATCH', '/api/users');
    echo $roleController->updateUser($matches[1]);
}

if (preg_match('#^/api/users/(\d+)$#', $path, $matches) && $method === 'DELETE') {
    RoleMiddleware::authorize('DELETE', '/api/users');
    echo $roleController->deleteUser($matches[1]);
}

?>