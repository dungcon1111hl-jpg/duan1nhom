<?php
require_once 'models/ThanhToanModel.php';
require_once 'models/BookingModel.php'; // [QUAN TRỌNG] Gọi thêm model này

class ThanhToanController {
    private $model;
    private $bookingModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new ThanhToanModel($db);
        $this->bookingModel = new BookingModel($db); // Khởi tạo
    }

    // 1. Danh sách lịch sử thanh toán của 1 Booking
    public function index() {
        $booking_id = $_GET['booking_id'] ?? 0;
        if (!$booking_id) die("Thiếu mã Booking");

        // Lấy thông tin Booking để hiện thị (Mã, Khách, Tiền...)
        $booking = $this->bookingModel->getOne($booking_id);
        if (!$booking) die("Booking không tồn tại");

        // Lấy lịch sử giao dịch
        $lich_su = $this->model->getByBooking($booking_id);

        require ROOT . "/views/admin/thanhtoan/list.php";
    }

    // 2. Form tạo phiếu thu
    public function create() {
        $booking_id = $_GET['booking_id'] ?? 0;
        
        // Lấy thông tin booking để hiện ở form (cho người dùng biết đang thu tiền đơn nào)
        $booking = $this->bookingModel->getOne($booking_id);
        
        require ROOT . "/views/admin/thanhtoan/create.php";
    }

    // 3. Lưu phiếu thu
    public function store() {
        $this->model->insert($_POST);
        header("Location: index.php?act=thanhtoan-list&booking_id=" . $_POST['booking_id']);
        exit;
    }

    // 4. Form sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $payment = $this->model->getOne($id);
        if (!$payment) die("Phiếu thu không tồn tại");
        
        // Lấy thông tin booking
        $booking = $this->bookingModel->getOne($payment['booking_id']);

        require ROOT . "/views/admin/thanhtoan/edit.php";
    }

    // 5. Cập nhật
    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        // Cần lấy lại booking_id để redirect đúng
        $payment = $this->model->getOne($id); 
        header("Location: index.php?act=thanhtoan-list&booking_id=" . $payment['booking_id']);
        exit;
    }

    // 6. Xóa
    public function delete() {
        $id = $_GET['id'];
        $booking_id = $_GET['booking_id'];
        $this->model->delete($id);
        header("Location: index.php?act=thanhtoan-list&booking_id=" . $booking_id);
        exit;
    }
}
?>