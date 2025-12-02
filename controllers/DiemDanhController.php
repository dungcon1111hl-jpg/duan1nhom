<?php
// controllers/DiemDanhController.php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../models/DiemDanhModel.php";
require_once __DIR__ . "/../models/BookingModel.php";

class DiemDanhController extends BaseController
{
    protected $model;
    protected $bookingModel;

    public function __construct()
    {
        $this->model = new DiemDanhModel();
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        $bookings = $this->bookingModel->getAllFull();
        $this->render("admin/diem_danh_khach", ['bookings' => $bookings]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?act=diem-danh");
            exit;
        }

        $booking_id = $_POST['booking_id'] ?? null;

        // ❗ KHÔNG ép int nữa – dùng nguyên chuỗi từ form
        $trang_thai = $_POST['trang_thai'] ?? "VANG_MAT";

        if (empty($booking_id)) {
            $_SESSION['flash_error'] = "Vui lòng chọn booking.";
            header("Location: " . BASE_URL . "?act=diem-danh");
            exit;
        }

        $booking = $this->bookingModel->findById($booking_id);
        if (!$booking) {
            $_SESSION['flash_error'] = "Booking không tồn tại.";
            header("Location: " . BASE_URL . "?act=diem-danh");
            exit;
        }

        $lich_id = $booking['lich_id'] ?? null;
        $khach_id = $booking['khach_hang_id'] ?? null;
        $ten_khach = $booking['khach_ho_ten'] ?? ($booking['snapshot_kh_ho_ten'] ?? null);
        $ten_tour = $booking['ten_tour'] ?? null;

        // Upload ảnh
        $hinh_anh = null;
        if (!empty($_FILES['hinh_anh']['name'])) {
            $uploadDir = PATH_ROOT . "/uploads/diem_danh/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $filename = time() . "_" . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $_FILES['hinh_anh']['name']);
            $target = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target)) {
                $hinh_anh = $filename;
            }
        }

        // Lưu
        $ok = $this->model->addCheckin($lich_id, $khach_id, $trang_thai, $hinh_anh, $ten_khach, $ten_tour);

        if ($ok) {
            $_SESSION['flash_success'] = "Điểm danh thành công.";
        } else {
            $_SESSION['flash_error'] = "Lưu điểm danh thất bại.";
        }

        header("Location: " . BASE_URL . "?act=diem-danh-list");
        exit;
    }

    public function list()
    {
        $diemDanh = $this->model->getAllDetailed();
        $this->render("admin/diem_danh_list", ['diemDanh' => $diemDanh]);
    }
}
