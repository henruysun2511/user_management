<?php
require_once __DIR__ . '/../services/role.service.php';
require_once __DIR__ . '/../helpers/responseHelper.php'; 

class RoleController {
    private $roleService;

    public function __construct() {
        $this->roleService = new RoleService();
    }

    public function getAllRoles() {
        try {
            $roles = $this->roleService->getAllRoles();
            echo ResponseHelper::success("Lấy danh sách vai trò thành công", $roles);
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function createRole() {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? '';
        $desc = $input['description'] ?? null;

        if (empty($name)) {
            echo ResponseHelper::error("Tên vai trò không được để trống", 400);
            return;
        }

        try {
            $result = $this->roleService->createRole($name, $desc);
            echo ResponseHelper::success("Tạo vai trò thành công", $result);
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function updateRole($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? '';
        $desc = $input['description'] ?? null;

        if (empty($name)) {
            echo ResponseHelper::error("Tên vai trò không được để trống", 400);
            return;
        }

        try {
            $result = $this->roleService->updateRole($id, $name, $desc);
            echo ResponseHelper::success("Cập nhật vai trò thành công", $result);
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function deleteRole($id) {
        try {
            $id = $this->roleService->deleteRole($id);
            echo ResponseHelper::success("Xóa vai trò thành công", $id);
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function attachPermissionToRole() {
        $input = json_decode(file_get_contents('php://input'), true);
        $roleId = $input['role_id'] ?? null;
        $permissionId = $input['permission_id'] ?? null;

        if (!$roleId || !$permissionId) {
            echo ResponseHelper::error("role_id và permission_id là bắt buộc", 400);
            return;
        }

        if (!$roleId || !is_array($permissionId) || empty($permissionId)) {
    echo ResponseHelper::error("role_id và permission_id là bắt buộc và permission_id phải là mảng", 400);
    return;
       }

        try {
            $this->roleService->attachPermissionToRole($roleId, $permissionId);
            echo ResponseHelper::success("Thêm quyền vào vai trò thành công");
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function detachPermissionFromRole() {
        $input = json_decode(file_get_contents('php://input'), true);
        $roleId = $input['role_id'] ?? null;
        $permissionId = $input['permission_id'] ?? null;

        if (!$roleId || !$permissionId) {
            echo ResponseHelper::error("role_id và permission_id là bắt buộc", 400);
            return;
        }

        try {
            $this->roleService->detachPermissionFromRole($roleId, $permissionId);
            echo ResponseHelper::success("Gỡ quyền khỏi vai trò thành công");
        } catch (Exception $e) {
            echo ResponseHelper::error("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function getPermissionsByRole($roleId) {
    try {
        $permissions = $this->roleService->getPermissionsByRole($roleId);
        ResponseHelper::success("Lấy danh sách quyền theo vai trò thành công", $permissions);
    } catch (Exception $e) {
        ResponseHelper::error($e->getMessage(), 400);
    }
}
}
?>