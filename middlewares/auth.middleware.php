<?php
require_once __DIR__ . '/../helpers/responseHelper.php';
require_once __DIR__ . '/../services/auth.service.php';

function authMiddleware($request, $next) {
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        echo ResponseHelper::error('Thiếu token xác thực', null, 401);
        exit;
    }

    $authHeader = $headers['Authorization'];
    if (strpos($authHeader, 'Bearer ') !== 0) {
        echo ResponseHelper::error('Token không hợp lệ', null, 401);
        exit;
    }

    $token = substr($authHeader, 7);

    $authService = new AuthService();
    $decoded = $authService->verifyToken($token);

    if (!$decoded) {
        echo ResponseHelper::error('Token không hợp lệ hoặc đã hết hạn', null, 401);
        exit;
    }

    // Gắn user info vào request
    $request['user'] = (array) $decoded;

    // Cho đi tiếp
    return $next($request);
}
