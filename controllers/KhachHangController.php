<?php

class KhachHangController {
    private $model;

    // Nhận $db (truyền từ index.php)
    public function __construct($db) {
        $this->model = new KhachHangModel($db);
    }

    // ===========================
    // HIỂN THỊ DANH SÁCH
    // ===========================
    public function index() {
        $data = $this->model->getAll();
        include "views/admin/khachhang/index.php";
    }

    // ===========================
    // FORM THÊM
    // ===========================
    public function create() {
        include "views/admin/khachhang/create.php";
    }

    // ===========================
    // LƯU KHÁCH HÀNG MỚI
    // ===========================
    public function store() {
        $this->model->create($_POST);
        header("Location: index.php?act=khachhang_list");
        exit;
    }

    // ===========================
    // FORM SỬA
    // ===========================
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $kh = $this->model->getById($id);

        if (!$kh) {
            die("Khách hàng không tồn tại");
        }

        include "views/admin/khachhang/edit.php";
    }

    // ===========================
    // CẬP NHẬT KHÁCH HÀNG
    // ===========================
    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header("Location: index.php?act=khachhang_list");
        exit;
    }

    // ===========================
    // XÓA KHÁCH HÀNG
    // ===========================
    public function delete() {
        $id = $_GET['id'] ?? 0;
        $this->model->delete($id);
        header("Location: index.php?act=khachhang_list");
        exit;
    }
}

?>
