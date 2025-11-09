<?php
require_once __DIR__ . '/../helpers/responseHelper.php';
require_once __DIR__ . '/../database/database.php';

class RoleMiddleware {
    public static function authorize($user, $method, $endpoint) {
        if (!$user || !isset($user['id'])) {
            echo ResponseHelper::error("Chưa đăng nhập.", null, 401);
            exit;
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            SELECT p.id
            FROM users u
            JOIN roles r ON u.role_id = r.id
            JOIN role_permissions rp ON rp.role_id = r.id
            JOIN permissions p ON p.id = rp.permission_id
            WHERE u.id = :user_id
              AND p.apiPath = :apiPath
              AND p.method = :method
            LIMIT 1
        ");

        $stmt->execute([
            ':user_id' => $user['id'],
            ':apiPath' => $endpoint,
            ':method' => $method
        ]);

        $permission = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$permission) {
            echo ResponseHelper::error("Không có quyền truy cập endpoint này.", null, 403);
            exit;
        }
    }
}
?>