<?php
class ResponseHelper {
    public static function success($message, $data = null, $status = 200) {
        http_response_code($status);
        return json_encode([
            "success" => true,
            "message" => $message,
            "data" => $data
        ]);
    }

    public static function error($message, $status = 400) {
        http_response_code($status);
        return json_encode([
            "success" => false,
            "message" => $message
        ]);
    }
}
?>