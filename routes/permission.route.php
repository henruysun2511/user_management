<?php
require_once __DIR__ . '/../controllers/permission.controller.php';
require_once __DIR__ . '/../helpers/responseHelper.php';
require_once __DIR__ . '/../middlewares/role.middleware.php';
require_once __DIR__ . '/../middlewares/auth.middleware.php';

$permissionController = new PermissionController();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// GET /api/permission
if ($path === '/api/permissions' && $method === 'GET') {
    return authMiddleware([], function($req) use ($permissionController) {
        RoleMiddleware::authorize($req['user'], 'GET', '/api/permissions');
        $query = $_GET ?? [];
        $permissionController->getAll($query);
    });
    exit;
}


// GET /api/permission/{id}
if (preg_match('#^/api/permissions/(\d+)$#', $path, $matches) && $method === 'GET') {
    return authMiddleware([], function($req) use ($permissionController, $matches) {
        RoleMiddleware::authorize($req['user'], 'GET', '/api/permissions');
        $permissionController->getById($matches[1]);
    });
    exit;
}


// POST /api/permission
if ($path === '/api/permissions' && $method === 'POST') {
    return authMiddleware([], function($req) use ($permissionController) {
        RoleMiddleware::authorize($req['user'], 'POST', '/api/permissions');
        $data = json_decode(file_get_contents("php://input"), true);
        $permissionController->create($data);
    });
    exit;
}

if ($path === '/api/permissions' && $method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $permissionController->create($data);
    exit;
}

// PATCH /api/permission/{id}
if (preg_match('#^/api/permissions/(\d+)$#', $path, $matches) && $method === 'PATCH') {
    return authMiddleware([], function($req) use ($permissionController, $matches) {
        RoleMiddleware::authorize($req['user'], 'PATCH', '/api/permissions/:id');
        $data = json_decode(file_get_contents("php://input"), true);
        $permissionController->update($matches[1], $data);
    });
    exit;
}

// DELETE /api/permission/{id}
if (preg_match('#^/api/permissions/(\d+)$#', $path, $matches) && $method === 'DELETE') {
    return authMiddleware([], function($req) use ($permissionController, $matches) {
        RoleMiddleware::authorize($req['user'], 'DELETE', '/api/permissions/:id');
        $permissionController->delete($matches[1]);
    });
    exit;
}
?>
