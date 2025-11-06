<?php
require_once __DIR__ . '/../middlewares/auth.middleware.php';
require_once __DIR__ . '/../controllers/user.controller.php';

$userController = new UserController();

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

if ($path === '/api/users/me' && $method === 'GET') {
    return authMiddleware($request, function($req) {
        $controller = new UserController();
        return $controller->getProfile($req['user']);
    });
}

if ($_SERVER['REQUEST_URI'] === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $userController->login();
}

if ($path === '/api/auth/register' && $method === 'POST') {
    $authController->register();
    exit;
}

if ($path === '/api/auth/forgot-password' && $method === 'POST') {
    RoleMiddleware::authorize('POST', '/api/auth/forgot-password');
    $userController->sendOtp();
    exit;
}

if ($path === '/api/auth/reset-password' && $method === 'POST') {
    RoleMiddleware::authorize('POST', '/api/auth/reset-password');
    $userController->resetPassword();
    exit;
}

  

