<?php
require_once __DIR__ . '/../controllers/role.controller.php';
require_once __DIR__ . '/../middlewares/role.middleware.php';
require_once __DIR__ . '/../middlewares/auth.middleware.php';

$roleController = new RoleController();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//GET /api/roles
if ($path === '/api/roles' && $method === 'GET') {
    return authMiddleware([], function($req) use ($roleController) {
        RoleMiddleware::authorize($req['user'], 'GET', '/api/roles');
        $roleController->getAllRoles();
    });
}


// POST /api/roles
if ($path === '/api/roles' && $method === 'POST') {
    return authMiddleware([], function($req) use ($roleController) {
        RoleMiddleware::authorize($req['user'], 'POST', '/api/roles');
        $roleController->createRole();
    });
    exit;
}

// PATCH /api/roles/{id}
if (preg_match('#^/api/roles/(\d+)$#', $path, $matches) && $method === 'PATCH') {
    return authMiddleware([], function($req) use ($roleController, $matches) {
        RoleMiddleware::authorize('PATCH', '/api/roles/:id');
        $roleController->updateRole($matches[1]);
    });
    exit;
}

// DELETE /api/roles/{id}
if (preg_match('#^/api/roles/(\d+)$#', $path, $matches) && $method === 'DELETE') {
    return authMiddleware([], function($req) use ($roleController, $matches) {
        RoleMiddleware::authorize('DELETE', '/api/roles/:id');
        $roleController->deleteRole($matches[1]);
    });
    exit;
}

// POST /api/roles/attach-permission
if ($path === '/api/roles/attach-permission' && $method === 'POST') {
    return authMiddleware([], function($req) use ($roleController) {
        RoleMiddleware::authorize('POST', '/api/roles/attach-permission');
        $roleController->attachPermissionToRole();
    });
    exit;
}

// POST /api/roles/detach-permission
if ($path === '/api/roles/detach-permission' && $method === 'POST') {
    return authMiddleware([], function($req) use ($roleController) {
        RoleMiddleware::authorize('POST', '/api/roles/detach-permission');
        $roleController->detachPermissionFromRole();
    });
    exit;
}