<?php
require_once __DIR__ . '/../database/database.php';

class RoleModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Lấy tất cả vai trò
    public function getAll() {
        $sql = "SELECT * FROM roles ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 role theo id
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($role) {
            $role['permissions'] = $this->getPermissions($id);
        }

        return $role;
    }

    // Thêm role mới + trả lại data vừa thêm
    public function create($name, $desc = null) {
        $stmt = $this->conn->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $desc]);

        $id = $this->conn->lastInsertId();
        return $this->findById($id);
    }

    // Cập nhật role + trả lại data mới
    public function update($id, $name, $desc = null) {
        $stmt = $this->conn->prepare("UPDATE roles SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $desc, $id]);

        return $this->findById($id);
    }

    // Xóa role + trả lại thông tin vừa xóa
    public function delete($id) {
        $role = $this->findById($id); 
        $stmt = $this->conn->prepare("DELETE FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $role;
    }

    // Gán quyền
    public function attachPermission($roleId, $permissionIdArray) {
    $stmt = $this->conn->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
    foreach ($permissionIdArray as $pid) {
        $stmt->execute([$roleId, $pid]);
    }
    return $this->getPermissions($roleId);
    }

    // Gỡ quyền
    public function detachPermission($roleId, $permissionId) {
        $stmt = $this->conn->prepare("DELETE FROM role_permissions WHERE role_id = ? AND permission_id = ?");
        $stmt->execute([$roleId, $permissionId]);
        return $this->getPermissions($roleId);
    }

    // Lấy danh sách quyền theo role_id
    public function getPermissions($roleId) {
    $sql = "SELECT p.* FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$roleId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function countUsersByRoleId($roleId) {
    $stmt = $this->conn->prepare("SELECT COUNT(*) as cnt FROM users WHERE role_id = ?");
    $stmt->execute([$roleId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$row['cnt'];
}

public function countPermissionsByRoleId($roleId) {
    $stmt = $this->conn->prepare("SELECT COUNT(*) as cnt FROM role_permissions WHERE role_id = ?");
    $stmt->execute([$roleId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$row['cnt'];
}
}
