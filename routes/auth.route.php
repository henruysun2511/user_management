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

if ($_SERVER['REQUEST_URI'] === '/api/auth/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $userController->login();
}

if ($path === '/api/auth/register' && $method === 'POST') {
    $userController->register();
    exit;
}

if ($path === '/api/auth/forgot-password' && $method === 'POST') {
    $userController->forgotPassword();
    exit;
}

if ($path === '/api/auth/otp' && $method === 'POST') {
    $userController->verifyOtp();
    exit;
}

if ($path === '/api/auth/reset-password' && $method === 'POST') {
        $userController->resetPassword();
        exit;
}

if ($path === '/api/auth/logout' && $method ==='POST'){
    return authMiddleware($request, function($req) {
        $controller = new UserController();
            return $controller->logout();
    });
}

if ($path === '/api/auth/refresh-token' && $method === 'GET'){
    $controller = new UserController();
        return $controller->refresh();
}






