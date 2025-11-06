 <?php
 require_once __DIR__ . '/../database/database.php';

class OtpModel {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function saveOtp($userId, $otp, $expiresAt) {
        $stmt = $this->conn->prepare("INSERT INTO otps (user_id, otp, expires_at) VALUES (:user_id, :otp, :expires_at)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':otp', $otp);
        $stmt->bindParam(':expires_at', $expiresAt);
        return $stmt->execute();
    }

    public function verifyOtp($userId, $otp) {
        $stmt = $this->conn->prepare("SELECT * FROM otps WHERE user_id = :user_id AND otp = :otp AND expires_at > NOW() LIMIT 1");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteOtp($email) {
        $stmt = $this->conn->prepare("DELETE FROM otps WHERE email = :email");
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

}
?>
