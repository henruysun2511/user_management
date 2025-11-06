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

  

