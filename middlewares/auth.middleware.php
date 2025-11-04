<?php
require_once __DIR__ . '/../helpers/responseHelper.php';
require_once __DIR__ . '/../services/auth.service.php';

function authMiddleware($request, $next) {
  // Lấy header Authorization
  $headers = getallheaders();
  if (!isset($headers['Authorization'])) {
      return ResponseHelper::error('Thiếu token xác thực', null, 401);
  }

  // Tách token (Bearer <token>)
  $authHeader = $headers['Authorization'];
  if (strpos($authHeader, 'Bearer ') !== 0) {
      return ResponseHelper::error('Token không hợp lệ', null ,401);
  }

  $token = substr($authHeader, 7);

  // Gọi service xác thực JWT
  $authService = new AuthService();
  $decoded = $authService->verifyToken($token);

  if (!$decoded) {
      return ResponseHelper::error('Token không hợp lệ hoặc đã hết hạn', null ,401);
  }

  // Gắn user info vào request
  $request['user'] = $decoded;

  // Cho đi tiếp
  return $next($request);
}
