<?php
require_once 'models/NhaCungCapModel.php';

class NhaCungCapController {
    private $model;

    public function __construct($db) {
        $this->model = new NhaCungCapModel($db);
    }

    // Danh sách
    public function index() {
        $nccs = $this->model->getAll();
        require ROOT . "/views/admin/nha_cung_cap/list.php";
    }

    // Form thêm mới
    public function create() {
        require ROOT . "/views/admin/nha_cung_cap/create.php";
    }

    // Xử lý thêm
    public function store() {
        $this->model->insert($_POST);
        header("Location: index.php?act=nha-cung-cap");
        exit;
    }

    // Form sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $ncc = $this->model->getOne($id);
        if (!$ncc) die("Không tìm thấy nhà cung cấp");
        require ROOT . "/views/admin/nha_cung_cap/edit.php";
    }

    // Xử lý sửa
    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header("Location: index.php?act=nha-cung-cap");
        exit;
    }

    // Xóa
    public function delete() {
        $id = $_GET['id'];
        $this->model->delete($id);
        header("Location: index.php?act=nha-cung-cap");
        exit;
    }
}
?>