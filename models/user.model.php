<?php
require_once __DIR__ . '/../database/database.php';

class UserModel {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll($limit, $offset, $search){
        $sql = "SELECT *
                FROM users 
                WHERE username LIKE :search OR email LIKE :search 
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

     public function countAll($search) {
        $sql = "SELECT COUNT(*) as total FROM users 
                WHERE username LIKE :search OR email LIKE :search";
        $stmt = $this->conn->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }
}

?>