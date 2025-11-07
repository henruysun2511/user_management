<?php
require_once __DIR__ . '/../controllers/permission.controller.php';
require_once __DIR__ . '/../helpers/responseHelper.php';
require_once __DIR__ . '/../middlewares/role.middleware.php';

$permissionController = new PermissionController();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// GET /api/permission
if ($path === '/api/permission' && $method === 'GET') {
    RoleMiddleware::authorize('GET', '/api/permission');
    $query = $_GET ?? [];
    $permissionController->getAll($query);
    exit;
}

// GET /api/permission/{id}
if (preg_match('#^/api/permission/(\d+)$#', $path, $matches) && $method === 'GET') {
    RoleMiddleware::authorize('GET', '/api/permission');
    $permissionController->getById($matches[1]);
    exit;
}

// POST /api/permission
if ($path === '/api/permission' && $method === 'POST') {
    RoleMiddleware::authorize('POST', '/api/permission');
    $data = json_decode(file_get_contents("php://input"), true);
    $permissionController->create($data);
    exit;
}

// PATCH /api/permission/{id}
if (preg_match('#^/api/permission/(\d+)$#', $path, $matches) && $method === 'PATCH') {
    RoleMiddleware::authorize('PATCH', '/api/permission');
    $data = json_decode(file_get_contents("php://input"), true);
    $permissionController->update($matches[1], $data);
    exit;
}

// DELETE /api/permission/{id}
if (preg_match('#^/api/permission/(\d+)$#', $path, $matches) && $method === 'DELETE') {
    RoleMiddleware::authorize('DELETE', '/api/permission');
    $permissionController->delete($matches[1]);
    exit;
}
?>
