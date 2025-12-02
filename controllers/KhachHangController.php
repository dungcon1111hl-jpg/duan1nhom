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
        // SỬA: Đổi tên biến $data thành $customers để khớp với View
        $customers = $this->model->getAll(); 
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
        try {
            $this->model->create($_POST);
            $_SESSION['success'] = "Thêm khách hàng thành công!";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        // SỬA: Redirect về đúng act=khach-hang
        header("Location: index.php?act=khach-hang");
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
        try {
            $this->model->update($id, $_POST);
            $_SESSION['success'] = "Cập nhật thành công!";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        // SỬA: Redirect về đúng act=khach-hang
        header("Location: index.php?act=khach-hang");
        exit;
    }

    // ===========================
    // XÓA KHÁCH HÀNG
    // ===========================
    public function delete() {
        $id = $_GET['id'] ?? 0;
        $this->model->delete($id);
        $_SESSION['success'] = "Đã xóa khách hàng!";
        // SỬA: Redirect về đúng act=khach-hang
        header("Location: index.php?act=khach-hang");
        exit;
    }
}
?>