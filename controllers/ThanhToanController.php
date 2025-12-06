<?php
require_once 'models/ThanhToanModel.php';
require_once 'models/BookingModel.php';

class ThanhToanController {
    private $model;
    private $bookingModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new ThanhToanModel($db);
        $this->bookingModel = new BookingModel($db);
    }

    // 1. Danh sách thanh toán (Tổng hợp hoặc Chi tiết)
    public function index() {
        $booking_id = $_GET['booking_id'] ?? 0;

        if ($booking_id > 0) {
            // Xem lịch sử của 1 booking cụ thể
            $booking = $this->bookingModel->getOne($booking_id);
            if (!$booking) {
                echo "<script>alert('Booking không tồn tại!'); window.history.back();</script>";
                return;
            }
            $lich_su = $this->model->getByBooking($booking_id);
            require ROOT . "/views/admin/thanhtoan/list.php";
        } else {
            // Xem danh sách toàn bộ phiếu thu
            $list_payment = $this->model->getAllTransactions();
            require ROOT . "/views/admin/thanhtoan/index.php";
        }
    }

    // 2. [FIX FULL] Form tạo phiếu thu (Xử lý cả 2 trường hợp)
    public function create() {
        $booking_id = $_GET['booking_id'] ?? 0;
        $booking = null;
        $dsBooking = [];

        if ($booking_id > 0) {
            // Trường hợp 1: Đã biết booking nào (Từ trang chi tiết)
            $booking = $this->bookingModel->getOne($booking_id);
        } 
        
        // Trường hợp 2: Chưa chọn booking (Bấm từ menu trái) -> Lấy danh sách để chọn
        if (!$booking) {
            $dsBooking = $this->bookingModel->getAll(); 
        }
        
        require ROOT . "/views/admin/thanhtoan/create.php";
    }

    // 3. Lưu phiếu thu
    public function store() {
        // Validate
        if (empty($_POST['booking_id']) || empty($_POST['so_tien'])) {
            echo "<script>alert('Vui lòng chọn Booking và nhập số tiền!'); window.history.back();</script>";
            return;
        }

        $this->model->insert($_POST);
        
        // Redirect về danh sách lịch sử của booking đó
        header("Location: index.php?act=thanhtoan-list&booking_id=" . $_POST['booking_id']);
        exit;
    }

    // 4. Form sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $payment = $this->model->getOne($id);
        if (!$payment) die("Phiếu thu không tồn tại");
        
        $booking = $this->bookingModel->getOne($payment['booking_id']);
        require ROOT . "/views/admin/thanhtoan/edit.php";
    }

    // 5. Cập nhật
    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        
        // Lấy thông tin phiếu thu để biết booking_id mà quay về
        $payment = $this->model->getOne($id); 
        header("Location: index.php?act=thanhtoan-list&booking_id=" . $payment['booking_id']);
        exit;
    }

    // 6. Xóa
    public function delete() {
        $id = $_GET['id'];
        // Lấy thông tin trước khi xóa để redirect
        $payment = $this->model->getOne($id);
        
        if ($payment) {
            $this->model->delete($id);
            header("Location: index.php?act=thanhtoan-list&booking_id=" . $payment['booking_id']);
        } else {
            header("Location: index.php?act=thanhtoan-list");
        }
        exit;
    }
}
?>