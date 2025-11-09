<?php
require_once __DIR__ . '/../database/database.php';

class TokenModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }



    public function find($refreshToken) {
        $stmt = $this->conn->prepare("SELECT * FROM tokens WHERE refreshToken = ?");
        $stmt->execute([$refreshToken]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function create($user_id, $refreshToken) {
        $stmt = $this->conn->prepare("INSERT INTO tokens (user_id, refreshToken) VALUES (?, ?)");
        return $stmt->execute([$user_id, $refreshToken]);
    }



    public function delete($refreshToken) {
        $stmt = $this->conn->prepare("DELETE FROM tokens WHERE refreshToken = ?");
        return $stmt->execute([$refreshToken]);
    }


}