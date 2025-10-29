<?php
class Database {
    private $host = "127.0.0.1";
    private $db_name = "quanlynguoidung";
    private $username = "root";
    private $password = "";
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Kết nối thất bại: " . $e->getMessage();
            exit;
        }
        return $this->conn;
    }

    public static function getConnection() {
        $db = new Database();
        return $db->connect();
    }
}
?>