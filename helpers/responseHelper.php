<?php
class ResponseHelper {
    public static function success($message, $data = null, $status = 200) {
        http_response_code((int)$status);
        echo json_encode([
            "success" => true,
            "message" => $message,
            "data" => $data
        ], JSON_UNESCAPED_UNICODE); // 
        exit;
    }

    public static function error($message, $status = 400) {
        http_response_code((int)$status);
        echo json_encode([
            "success" => false,
            "message" => $message
        ], JSON_UNESCAPED_UNICODE); 
        exit;
    }
}
?>
