<?php
require_once __DIR__ . '/../helpers/responseHelper.php';
require_once __DIR__ . '/../services/permission.service.php';

class PermissionController {
    private $service;

    public function __construct() {
        $this->service = new PermissionService();
    }

    //Lấy danh sách quyền (có filter + phân trang)
    public function getAll($query) {
        try {
            $data = $this->service->getAll($query);
            ResponseHelper::success("Lấy danh sách quyền thành công", $data);
        } catch (Exception $e) {
            ResponseHelper::error($e->getMessage(), 400);
        }
    }

    //Lấy chi tiết quyền theo ID
    public function getById($id) {
        try {
            $permission = $this->service->getById($id);
            ResponseHelper::success("Lấy quyền thành công", $permission);
        } catch (Exception $e) {
            ResponseHelper::error($e->getMessage(), 404);
        }
    }

    //Tạo quyền mới
    public function create($body) {
    try {
        $permission = $this->service->create($body);
        ResponseHelper::success("Tạo quyền mới thành công", $permission);
    } catch (Exception $e) {
        ResponseHelper::error($e->getMessage(), 400);
    }
}

    // Cập nhật quyền
    public function update($id, $body) {
        try {
            $success = $this->service->update($id, $body);
            if ($success) {
                ResponseHelper::success("Cập nhật quyền thành công", $success);
            } else {
                ResponseHelper::error("Không tìm thấy quyền để cập nhật", 404);
            }
        } catch (Exception $e) {
            ResponseHelper::error($e->getMessage(), 400);
        }
    }
// Xóa quyền
    public function delete($id) {
    try {
        $deletedPermission = $this->service->delete($id);
        ResponseHelper::success("Xóa quyền thành công", $deletedPermission);
    } catch (Exception $e) {
        ResponseHelper::error($e->getMessage(), 400);
    }
}
}
?>
