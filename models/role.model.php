<?php
require_once __DIR__ . '/../database/database.php';

class RoleModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll() {
        $sql = "SELECT * FROM roles ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $desc = null) {
        $stmt = $this->conn->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $desc]);
    }

    public function update($id, $name, $desc = null) {
        $stmt = $this->conn->prepare("UPDATE roles SET name = ?, description = ? WHERE id = ?");
        return $stmt->execute([$name, $desc, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM roles WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function attachPermission($roleId, $permissionId) {
        $stmt = $this->conn->prepare("INSERT IGNORE INTO role_permission (role_id, permission_id) VALUES (?, ?)");
        return $stmt->execute([$roleId, $permissionId]);
    }

    public function detachPermission($roleId, $permissionId) {
        $stmt = $this->conn->prepare("DELETE FROM role_permission WHERE role_id = ? AND permission_id = ?");
        return $stmt->execute([$roleId, $permissionId]);
    }

    public function getPermissions($roleId) {
        $sql = "SELECT p.* FROM permissions p
                JOIN role_permission rp ON p.id = rp.permission_id
                WHERE rp.role_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUsersByRoleId($id) {
    $sql = "SELECT COUNT(*) FROM users WHERE role_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}

public function countPermissionsByRoleId($id) {
    $sql = "SELECT COUNT(*) FROM role_permissions WHERE role_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}
}