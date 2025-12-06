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
        // Lấy danh sách khách đã đặt tour
        $bookings = $this->bookingModel->getAllFull();

        // Lấy danh sách đã điểm danh trước đó để hiển thị trạng thái cũ
        $history = $this->model->getAllDetailed();

        // Ghép trạng thái cũ và ĐƯỜNG DẪN ẢNH CŨ (minh_anh) vào danh sách booking
        foreach ($bookings as &$b) {
            $b['status_checkin'] = null;
            $b['history_image_url'] = null; // Tên biến tạm trong PHP

            foreach ($history as $h) {
                if ($b['lich_id'] == $h['lich_id'] && $b['khach_hang_id'] == $h['khach_id']) {
                    $b['status_checkin'] = $h['trang_thai'];
                    $b['history_image_url'] = $h['hinh_anh'] ?? null; // ⭐ LẤY DỮ LIỆU TỪ CỘT hinh_anh ⭐
                    break; 
                }
            }
        }
        unset($b);

        $this->render("admin/diem_danh_khach", ['bookings' => $bookings]);
    }

    // XỬ LÝ LƯU (STORE) - Dùng cột minh_anh
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?act=diem-danh");
            exit;
        }

        $attendances = $_POST['attendance'] ?? [];
        if (empty($attendances)) {
            $_SESSION['flash_error'] = "Chưa chọn trạng thái nào.";
            header("Location: " . BASE_URL . "?act=diem-danh");
            exit;
        }

        // BƯỚC 1: XỬ LÝ UPLOAD ẢNH RIÊNG CHO TỪNG KHÁCH
        $uploaded_images = [];
        $target_dir = "uploads/checkin_proofs/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $files = $_FILES['proof_photo'] ?? [];
        
        if (!empty($files['name']) && is_array($files['name'])) {
            foreach ($files['name'] as $booking_id => $name) {
                if (isset($files['error'][$booking_id]) && $files['error'][$booking_id] == UPLOAD_ERR_OK) {
                    $tmp_name = $files['tmp_name'][$booking_id];
                    
                    $filename = time() . "_" . $booking_id . "_" . basename($name);
                    $target_file = $target_dir . $filename;
                    
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $uploaded_images[$booking_id] = $target_file; 
                    }
                }
            }
        }
        
        // BƯỚC 2: DUYỆT VÀ LƯU DỮ LIỆU ĐIỂM DANH
        $count = 0;
        foreach ($attendances as $booking_id => $trang_thai) {
            $booking = $this->bookingModel->getOne($booking_id);
            
            if ($booking) {
                $lich_id = $booking['lich_id'];
                $khach_id = $booking['khach_hang_id'];
                
                $ten_khach = $booking['khach_ho_ten'] ?? ($booking['snapshot_kh_ho_ten'] ?? 'Khách (ID: ' . $khach_id . ')');
                $ten_tour = $booking['ten_tour'] ?? 'Tour ẩn';

                $hinh_anh_url = $uploaded_images[$booking_id] ?? null; // ⭐ Tên biến tạm trong Controller ⭐

                // GỌI MODEL, TRUYỀN MINH_ANH_URL
                $this->model->saveOrUpdateCheckin($lich_id, $khach_id, $trang_thai, $ten_khach, $ten_tour, $hinh_anh_url);
                $count++;
            }
        }

        $_SESSION['flash_success'] = "Đã cập nhật điểm danh cho $count khách.";
        header("Location: " . BASE_URL . "?act=diem-danh");
        exit;
    }

    public function list()
    {
        $diemDanh = $this->model->getAllDetailed();
        $this->render("admin/diem_danh_list", ['diemDanh' => $diemDanh]);
    }
}
?>