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

    // Tìm user theo email
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm user theo username
    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo user mới
    public function create($data) {
        // Check if table uses fullName or full_name column
        $fullNameColumn = $this->columnExists('full_name') ? 'full_name' : 'fullName';
        $phoneColumn = $this->columnExists('phone') ? 'phone' : 'phoneNumber';
        
        $sql = "INSERT INTO users (username, email, password, $fullNameColumn, $phoneColumn, birth, gender, role, status) 
                VALUES (:username, :email, :password, :full_name, :phone, :birth, :gender, :role, :status)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        $stmt->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
        
        // Handle birth - can be NULL
        if (empty($data['birth'])) {
            $stmt->bindValue(':birth', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':birth', $data['birth'], PDO::PARAM_STR);
        }
        
        // Handle gender - can be NULL
        if (empty($data['gender'])) {
            $stmt->bindValue(':gender', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':gender', $data['gender'], PDO::PARAM_STR);
        }
        
        $stmt->bindParam(':role', $data['role'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Helper: Check if column exists in table
    private function columnExists($columnName) {
        try {
            $sql = "SHOW COLUMNS FROM users LIKE :column";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':column', $columnName, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Lấy user theo ID
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>