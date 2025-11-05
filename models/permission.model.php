<?php
require_once __DIR__ . '/../database/database.php';

class PermissionModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    //Lấy tất cả quyền
    public function getAll($limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT * FROM permissions 
                WHERE name LIKE :search OR apiPath LIKE :search OR module LIKE :search
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Đếm tổng số bản ghi
    public function countAll($search = '') {
        $sql = "SELECT COUNT(*) as total FROM permissions 
                WHERE name LIKE :search OR apiPath LIKE :search OR module LIKE :search";
        $stmt = $this->conn->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    //Lấy chi tiết quyền theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM permissions WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Thêm mới quyền
    public function create($name, $apiPath, $method, $module = null) {
        $stmt = $this->conn->prepare("INSERT INTO permissions (name, apiPath, method, module)
                                      VALUES (:name, :apiPath, :method, :module)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':apiPath', $apiPath, PDO::PARAM_STR);
        $stmt->bindParam(':method', $method, PDO::PARAM_STR);
        $stmt->bindParam(':module', $module, PDO::PARAM_STR);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    //Cập nhật quyền
    public function update($id, $name, $apiPath, $method, $module = null) {
        $stmt = $this->conn->prepare("UPDATE permissions 
                                      SET name = :name, apiPath = :apiPath, method = :method, module = :module 
                                      WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':apiPath', $apiPath, PDO::PARAM_STR);
        $stmt->bindParam(':method', $method, PDO::PARAM_STR);
        $stmt->bindParam(':module', $module, PDO::PARAM_STR);
        return $stmt->execute();
    }

    //Xóa quyền
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM permissions WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    //Thêm phương thức hỗ trợ filter
    public function getAllCustom($where, $params, $limit, $offset) {
        $sql = "SELECT * FROM permissions $where ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllCustom($where, $params) {
        $sql = "SELECT COUNT(*) as total FROM permissions $where";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }
}
?>
