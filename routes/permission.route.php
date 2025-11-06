<?php
require_once __DIR__ . '/../controllers/permission.controller.php';
require_once __DIR__ . '/../helpers/responseHelper.php';

$permissionController = new PermissionController();

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$path = str_replace('/user_management/index.php/', '', $uri);

preg_match('/^api\/permission(?:\/(\d+))?$/', $path, $matches);
$id = $matches[1] ?? null;

try {
    switch ($method) {
        case 'GET':
            if ($id) {
                //Lấy chi tiết quyền
                $permissionController->getById($id);
            } else {
                $query = $_GET ?? [];
                $permissionController->getAll($query);
            }
            break;

        case 'POST':
            //Tạo quyền mới
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                ResponseHelper::error("Dữ liệu đầu vào không hợp lệ", 400);
            }
            $permissionController->create($data);
            break;

        case 'PATCH':
            //Cập nhật quyền
            if (!$id) {
                ResponseHelper::error("Thiếu ID trong yêu cầu", 400);
            }
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                ResponseHelper::error("Dữ liệu đầu vào không hợp lệ", 400);
            }
            $permissionController->update($id, $data);
            break;

        case 'DELETE':
            //Xóa quyền
            if (!$id) {
                ResponseHelper::error("Thiếu ID trong yêu cầu", 400);
            }
            $permissionController->delete($id);
            break;

        default:
            ResponseHelper::error("Phương thức không được hỗ trợ", 405);
    }
} catch (Exception $e) {
    ResponseHelper::error($e->getMessage(), 400);
}
?>
