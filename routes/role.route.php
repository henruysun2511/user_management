<?php
require_once __DIR__ . '/../controllers/role.controller.php';
require_once __DIR__ . '/../middlewares/role.middleware.php';

$roleController = new RoleController();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// GET /api/roles
if ($path === '/api/roles' && $method === 'GET') {
    RoleMiddleware::authorize('GET', '/api/roles');
    $roleController->getAllRoles();
    exit;
}

// POST /api/roles
if ($path === '/api/roles' && $method === 'POST') {
    // RoleMiddleware::authorize('POST', '/api/roles');
    $roleController->createRole();
    exit;
}

// PATCH /api/roles/{id}
if (preg_match('#^/api/roles/(\d+)$#', $path, $matches) && $method === 'PATCH') {
    RoleMiddleware::authorize('PATCH', '/api/roles');
    $roleController->updateRole($matches[1]);
    exit;
}

// DELETE /api/roles/{id}
if (preg_match('#^/api/roles/(\d+)$#', $path, $matches) && $method === 'DELETE') {
    RoleMiddleware::authorize('DELETE', '/api/roles');
    $roleController->deleteRole($matches[1]);
    exit;
}

// POST /api/roles/attach-permission
if ($path === '/api/roles/attach-permission' && $method === 'POST') {
    RoleMiddleware::authorize('POST', '/api/roles/attach-permission');
    $roleController->attachPermissionToRole();
    exit;
}

// POST /api/roles/detach-permission
if ($path === '/api/roles/detach-permission' && $method === 'POST') {
    RoleMiddleware::authorize('POST', '/api/roles/detach-permission');
    $roleController->detachPermissionFromRole();
    exit;
}