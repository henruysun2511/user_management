<?php
require_once __DIR__ . '/../models/permission.model.php';

class PermissionService {
    private $model;

    public function __construct() {
        $this->model = new PermissionModel();
    }

    //Lấy danh sách quyền (có filter + phân trang)
    public function getAll($query = []) {
        $page = isset($query['page']) ? max(1, (int)$query['page']) : 1;
        $limit = isset($query['limit']) ? max(1, (int)$query['limit']) : 10;
        $offset = ($page - 1) * $limit;

        $filters = [];
        $params = [];

    // Filter theo từng cột
        if (!empty($query['name'])) {
            $filters[] = "name LIKE :name";
            $params[':name'] = "%{$query['name']}%";
        }
        if (!empty($query['apiPath'])) {
            $filters[] = "apiPath LIKE :apiPath";
            $params[':apiPath'] = "%{$query['apiPath']}%";
        }
        if (!empty($query['module'])) {
            $filters[] = "module LIKE :module";
            $params[':module'] = "%{$query['module']}%";
        }

        $where = count($filters) ? "WHERE " . implode(" AND ", $filters) : "";

        $data = $this->model->getAllCustom($where, $params, $limit, $offset);
        $total = $this->model->countAllCustom($where, $params);

        return [
            "data" => $data,
            "pagination" => [
                "total" => $total,
                "page" => $page,
                "limit" => $limit,
                "totalPages" => ceil($total / $limit)
            ]
        ];
    }

    //Lấy chi tiết quyền theo ID
    public function getById($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID không hợp lệ");
        }

        $permission = $this->model->getById($id);
        if (!$permission) {
            throw new Exception("Không tìm thấy quyền với ID = $id");
        }

        return $permission;
    }
//Tạo quyền mới (Validate dữ liệu đầu vào)
    public function create($body) {
        // Validate dữ liệu
        if (empty($body['name'])) {
            throw new Exception("Thiếu tên quyền (name)");
        }
        if (empty($body['apiPath'])) {
            throw new Exception("Thiếu đường dẫn API (apiPath)");
        }
        if (empty($body['method'])) {
            throw new Exception("Thiếu phương thức HTTP (method)");
        }

        // Giới hạn method hợp lệ
        $validMethods = ['GET', 'POST', 'PATCH', 'DELETE'];
        if (!in_array(strtoupper($body['method']), $validMethods)) {
            throw new Exception("Phương thức HTTP không hợp lệ (chỉ chấp nhận GET, POST, PATCH, DELETE)");
        }

        return $this->model->create(
            $body['name'],
            $body['apiPath'],
            strtoupper($body['method']),
            $body['module'] ?? null
        );
    }

  //Cập nhật quyền
    public function update($id, $body) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID không hợp lệ");
        }

        // Kiểm tra quyền có tồn tại không
        if (!$this->model->getById($id)) {
            throw new Exception("Không tìm thấy quyền với ID = $id");
        }

        // Validate dữ liệu
        if (empty($body['name']) || empty($body['apiPath']) || empty($body['method'])) {
            throw new Exception("Thiếu dữ liệu bắt buộc (name, apiPath, method)");
        }

        return $this->model->update(
            $id,
            $body['name'],
            $body['apiPath'],
            strtoupper($body['method']),
            $body['module'] ?? null
        );
    }

    //Xóa quyền
    public function delete($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID không hợp lệ");
        }

        // Kiểm tra quyền có tồn tại
        if (!$this->model->getById($id)) {
            throw new Exception("Không tìm thấy quyền với ID = $id");
        }

        return $this->model->delete($id);
    }
}
?>
