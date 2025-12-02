<?php
require_once 'models/DatDichVuModel.php';

class DatDichVuController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new DatDichVuModel($db);
    }

    // [CẬP NHẬT] Hiển thị danh sách (Hỗ trợ cả 2 chế độ)
    public function index() {
        $booking_id = $_GET['booking_id'] ?? 0;

        if ($booking_id > 0) {
            // Chế độ 1: Xem dịch vụ của 1 Booking cụ thể
            $dichvus = $this->model->getByBookingId($booking_id);
        } else {
            // Chế độ 2: Xem tất cả dịch vụ (khi bấm từ menu)
            $dichvus = $this->model->getAll();
        }
        
        // Tính tổng tiền hiển thị
        $tong_tien = 0;
        foreach ($dichvus as $dv) {
            $tong_tien += $dv['thanh_tien'];
        }

        require ROOT . "/views/admin/dichvu/list.php";
    }

    public function create() {
        $booking_id = $_GET['booking_id'] ?? 0;
        // Nếu không có booking_id, yêu cầu nhập hoặc chọn (ở đây ta chặn lại cho đơn giản)
        if ($booking_id == 0) {
            echo "<script>alert('Vui lòng chọn một Booking trước khi thêm dịch vụ!'); window.history.back();</script>";
            return;
        }
        require ROOT . "/views/admin/dichvu/create.php";
    }

    public function store() {
        $this->model->insert($_POST);
        header("Location: index.php?act=dichvu-list&booking_id=" . $_POST['booking_id']);
        exit;
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $dichvu = $this->model->getOne($id);
        require ROOT . "/views/admin/dichvu/edit.php";
    }

    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header("Location: index.php?act=dichvu-list&booking_id=" . $_POST['booking_id']);
        exit;
    }

    public function delete() {
        $id = $_GET['id'];
        $booking_id = $_GET['booking_id'] ?? 0;
        
        $this->model->delete($id);
        
        // Quay lại đúng chỗ
        if ($booking_id > 0) {
            header("Location: index.php?act=dichvu-list&booking_id=" . $booking_id);
        } else {
            header("Location: index.php?act=dichvu-list");
        }
        exit;
    }
}
?>