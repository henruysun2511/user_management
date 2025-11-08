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

    //Lấy người dùng theo ID
     public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Tạo người dùng mới
   public function create($data) {
    $sql = "INSERT INTO users (email, password, fullName, phoneNumber, birth, gender, role_id) 
            VALUES (:email, :password, :full_name, :phone, :birth, :gender, :role_id)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
    $stmt->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
    $stmt->bindParam(':birth', $data['birth'], PDO::PARAM_STR);
    $stmt->bindParam(':gender', $data['gender'], PDO::PARAM_STR);
    $stmt->bindParam(':role_id', $data['role_id'], PDO::PARAM_INT);

    return $stmt->execute();
}


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

    public function findByUsername($fullName) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE fullName = ?");
        $stmt->execute([$fullName]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($userId, $passwordHash) {
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$passwordHash, $userId]);
    }
}

?>