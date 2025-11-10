<?php
require_once __DIR__ . '/../models/role.model.php';
require_once __DIR__ . '/../helpers/responseHelper.php';

class RoleService {
    private $roleModel;

    public function __construct() {
        $this->roleModel = new RoleModel();
    }

    public function getAllRoles() {
        $result = $this->roleModel->getAll();
        return ["data" =>  $result];
    }

    public function createRole($name, $desc = null) {
        return $this->roleModel->create($name, $desc);
    }

   public function updateRole($id, $name, $desc = null) {
    $role = $this->roleModel->findById($id);
    if (!$role) {
        return ResponseHelper::error("Vai trò không tồn tại", null, 404);
    }

    $newName = $name; // sửa từ $desc -> $name
    $newDesc = $desc ?? $role['description']; // nếu null thì giữ nguyên description

    return $this->roleModel->update($id, $newName, $newDesc);
}

    public function deleteRole($id) {
        $role = $this->roleModel->findById($id);
        if (!$role) {
            return ResponseHelper::error("Vai trò không tồn tại", null, 404);
        }

        $userCount = $this->roleModel->countUsersByRoleId($id);
        if ($userCount > 0) {
            return ResponseHelper::error("Không thể xóa vai trò này vì đang được sử dụng bởi $userCount người dùng.", null, 400);
        }

        $permCount = $this->roleModel->countPermissionsByRoleId($id);
        if ($permCount > 0) {
             return ResponseHelper::error("Không thể xóa vai trò này vì vẫn còn quyền liên kết.", null, 400);
        }

        return $this->roleModel->delete($id);
    }

     public function getPermissionsByRole($roleId) {
        if (!is_numeric($roleId) || $roleId <= 0) {
            throw new Exception("ID vai trò không hợp lệ");
        }

        // Kiểm tra role có tồn tại không
        $role = $this->roleModel->findById($roleId);
        if (!$role) {
            throw new Exception("Không tìm thấy vai trò với ID = $roleId");
        }

        // Gọi model lấy quyền
        return $this->roleModel->getPermissions($roleId);
    }

    public function attachPermissionToRole($roleId, $permissionId) {
        return $this->roleModel->attachPermission($roleId, $permissionId);
    }

    public function detachPermissionFromRole($roleId, $permissionId) {
        return $this->roleModel->detachPermission($roleId, $permissionId);
    }

    public function getRolePermissions($roleId) {
        return $this->roleModel->getPermissions($roleId);
    }
}