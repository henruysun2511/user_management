<?php
require_once __DIR__ . '/../controllers/user.controller.php';
require_once __DIR__ . '/../middlewares/role.middleware.php';
require_once __DIR__ . '/../middlewares/auth.middleware.php';

$userController = new UserController();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Lấy danh sách user
if ($uri === '/api/users' && $method === 'GET') {
    return authMiddleware([], function($req) use ($userController) {
        RoleMiddleware::authorize($req['user'], 'GET', '/api/users');
        echo $userController->getAllUsers();
    });
}

if (preg_match('#^/api/users/(\d+)$#', $path, $matches) && $method === 'PATCH') {
    return authMiddleware([], function($req) use ($userController, $matches) {
        RoleMiddleware::authorize($req['user'], 'PATCH', '/api/users/:id');
        echo $userController->updateUser($matches[1]);
    });
}

?>