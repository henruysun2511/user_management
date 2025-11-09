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
                WHERE email LIKE :search or fullName LIKE :search
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
            VALUES (:email, :password, :fullName, :phoneNumber, :birth, :gender, :role_id)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);
    $stmt->bindParam(':fullName', $data['fullName']);
    $stmt->bindParam(':phoneNumber', $data['phoneNumber']);
    $stmt->bindParam(':birth', $data['birth']);
    $stmt->bindParam(':gender', $data['gender']);
    $stmt->bindParam(':role_id', $data['role_id']);
    $stmt->execute();

    return $this->conn->lastInsertId();
}


    //Chỉnh sửa thông tin người dùng
   public function update($id, $data) {
    // Chuẩn bị các trường có trong $data
    $fields = [];
    $params = [];

    if (isset($data['fullName'])) {
        $fields[] = 'fullName = :fullName';
        $params[':fullName'] = $data['fullName'];
    }
    if (isset($data['gender'])) {
        $fields[] = 'gender = :gender';
        $params[':gender'] = $data['gender'];
    }
    if (isset($data['phoneNumber'])) {
        $fields[] = 'phoneNumber = :phoneNumber';
        $params[':phoneNumber'] = $data['phoneNumber'];
    }
    if (isset($data['role_id'])) {
        $fields[] = 'role_id = :role_id';
        $params[':role_id'] = $data['role_id'];
    }
    if (isset($data['email'])) {
        $fields[] = 'email = :email';
        $params[':email'] = $data['email'];
    }

    if (empty($fields)) {
        return false; 
    }

    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $this->conn->prepare($sql);

    // Gán giá trị
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    return $stmt->execute();
}

    //Đếm số bản ghi
    public function countAll($search) {
        $sql = "SELECT COUNT(*) as total FROM users 
                WHERE email LIKE :search or fullName LIKE :search";
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