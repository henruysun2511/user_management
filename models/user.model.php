<?php
require_once __DIR__ . '/../database/database.php';

class UserModel {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    //Lấy danh sách người dùng (pagination, query)
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

    //Register

    //Chỉnh sửa thông tin người dùng
    public function update($id, $data) {
        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //Xóa người dùng
    
    //Cập nhật mật khẩu
    public function updatePassword($userId, $passwordHash) {
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$passwordHash, $userId]);
    }

    //Đếm số bản ghi
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

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]); 
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>