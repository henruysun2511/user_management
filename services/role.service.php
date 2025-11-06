<?php
require_once __DIR__ . '/../models/role.model.php';
require_once __DIR__ . '/../helpers/responseHelper.php';

class RoleService {
    private $roleModel;

    public function __construct() {
        $this->roleModel = new RoleModel();
    }

    public function getAllRoles() {
        return $this->roleModel->getAll();;
    }

    public function createRole($name, $desc = null) {
        return $this->roleModel->create($name, $desc);
    }

    public function updateRole($id, $name, $desc = null) {
        $role = this.roleModel->findById($id);
        if (!$role) {
            return ResponseHelper::error("Vai trò không tồn tại", null, 404);
        }

        return $this->roleModel->update($id, $name, $desc);
    }

    public function deleteRole($id) {
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