<?php
require_once 'models/DatDichVuModel.php';
if (file_exists('models/BookingModel.php')) require_once 'models/BookingModel.php';

class DatDichVuController {
    private $model;
    private $bookingModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new DatDichVuModel($db);
        if (class_exists('BookingModel')) $this->bookingModel = new BookingModel();
    }

    public function index() {
        $booking_id = $_GET['booking_id'] ?? 0;
        $dichvus = ($booking_id > 0) ? $this->model->getByBooking($booking_id) : $this->model->getAll();
        
        $tong_tien = 0;
        foreach ($dichvus as $dv) $tong_tien += $dv['thanh_tien'];

        require ROOT . "/views/admin/dichvu/list.php";
    }

    public function create() {
        $selected_booking_id = $_GET['booking_id'] ?? 0;
        $bookings = ($this->bookingModel && method_exists($this->bookingModel, 'getAll')) ? $this->bookingModel->getAll() : [];
        require ROOT . "/views/admin/dichvu/create.php";
    }

    public function store() {
        if (empty($_POST['booking_id']) || empty($_POST['ten_dich_vu'])) {
             echo "<script>alert('Vui lòng chọn Booking và nhập tên dịch vụ!'); history.back();</script>";
             return;
        }

        if ($this->model->insert($_POST)) {
            header("Location: index.php?act=dichvu-list&booking_id=" . $_POST['booking_id']);
            exit;
        } else {
            // Hiển thị lỗi chi tiết từ Model
            $err = $_SESSION['system_error'] ?? 'Lỗi không xác định';
            echo "<script>alert('Lỗi Database: $err'); history.back();</script>";
            unset($_SESSION['system_error']);
        }
    }

    public function edit() {
        $dichvu = $this->model->getById($_GET['id'] ?? 0);
        if (!$dichvu) die("Dịch vụ không tồn tại");
        require ROOT . "/views/admin/dichvu/edit.php";
    }

    public function update() {
        if ($this->model->update($_POST['id'], $_POST)) {
            header("Location: index.php?act=dichvu-list&booking_id=" . $_POST['booking_id']);
            exit;
        } else {
            $err = $_SESSION['system_error'] ?? 'Lỗi cập nhật';
            echo "<script>alert('Lỗi Database: $err'); history.back();</script>";
            unset($_SESSION['system_error']);
        }
    }

    public function delete() {
        $this->model->delete($_GET['id']);
        header("Location: index.php?act=dichvu-list&booking_id=" . $_GET['booking_id']);
        exit;
    }
}
?>