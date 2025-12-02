<?php

// === REQUIRE CÁC MODEL ===
require_once './models/BookingModel.php';
require_once './models/TourModel.php';
require_once './models/KhachHangModel.php';
require_once './models/KhachThamGiaModel.php';
require_once './models/ThanhToanModel.php';

class BookingController {
    private $bookingModel;
    private $tourModel;
    private $khachHangModel;
    private $khachThamGiaModel;
    private $thanhToanModel;

    public function __construct() {
        $db = connectDB(); 

        $this->bookingModel      = new BookingModel();
        $this->khachThamGiaModel = new KhachThamGiaModel();
        $this->tourModel         = new TourModel($db);
        $this->khachHangModel    = new KhachHangModel($db);
        $this->thanhToanModel    = new ThanhToanModel($db);
    }

    // --- HÀM PHỤ TRỢ: Chuyển đổi tiền tệ về số chuẩn ---
    // Giúp xử lý các trường hợp giá lưu là "1.000.000" hoặc "1,000,000"
    private function toNumber($str) {
        if (is_numeric($str)) return (float)$str;
        // Xóa tất cả ký tự không phải số và dấu chấm thập phân (nếu cần)
        // Ở đây ta xóa dấu chấm, phẩy, khoảng trắng, chữ đ
        return (float)str_replace(['.', ',', 'đ', 'VNĐ', ' '], '', $str);
    }

    public function index() {
        $bookings = $this->bookingModel->getAll();
        require_once 'views/admin/booking/index.php';
    }

    public function create() {
        $toursStmt = $this->tourModel->getAll([]); 
        $tours = $toursStmt->fetchAll(PDO::FETCH_ASSOC);
        $customers = $this->khachHangModel->getAll();
        require_once 'views/admin/booking/create.php';
    }

    // 3. XỬ LÝ LƯU BOOKING
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $tour_id = $_POST['tour_id'] ?? null;
            $khach_hang_id = $_POST['khach_hang_id'] ?? null;
            $so_luong_nguoi_lon = max(1, (int)($_POST['so_luong_nguoi_lon'] ?? 1));
            $so_luong_tre_em = (int)($_POST['so_luong_tre_em'] ?? 0);
            
            $loai_booking = $_POST['loai_booking'] ?? 'DOAN';
            $trang_thai = $_POST['trang_thai'] ?? 'CHO_XU_LY';

            // Xử lý tiền từ form (ưu tiên)
            $tong_tien = $this->toNumber($_POST['tong_tien'] ?? 0);
            $da_thanh_toan = $this->toNumber($_POST['da_thanh_toan'] ?? 0);

            // Fallback: Nếu tổng tiền = 0 (do lỗi JS hoặc ko nhập), server tự tính
            if ($tong_tien <= 0 && $tour_id) {
                $tour = $this->tourModel->getById($tour_id);
                if ($tour) {
                    // Dùng hàm toNumber để đảm bảo giá lấy từ DB là số chuẩn
                    $gia_nl = $this->toNumber($tour['gia_nguoi_lon']);
                    $gia_te = $this->toNumber($tour['gia_tre_em']);
                    $gia_tour = $this->toNumber($tour['gia_tour']);

                    // Logic giá
                    if ($gia_nl <= 0) $gia_nl = $gia_tour; 
                    if ($gia_te <= 0) $gia_te = round($gia_nl * 0.7); // 70% nếu ko có giá trẻ em

                    $tong_tien = ($gia_nl * $so_luong_nguoi_lon) + ($gia_te * $so_luong_tre_em);
                }
            }

            // Snapshot thông tin
            $ten_tour_snapshot = 'Chưa xác định';
            if ($tour_id) {
                $tourInfo = $this->tourModel->getById($tour_id);
                if ($tourInfo) $ten_tour_snapshot = $tourInfo['ten_tour'];
            }
            $kh = $this->khachHangModel->getById($khach_hang_id);

            $data = [
                'tour_id' => $tour_id,
                'snapshot_ten_tour' => $ten_tour_snapshot,
                'khach_hang_id' => $khach_hang_id,
                'so_luong_nguoi_lon' => $so_luong_nguoi_lon,
                'so_luong_tre_em' => $so_luong_tre_em,
                'loai_booking' => $loai_booking, 
                
                'snapshot_kh_ho_ten' => $kh['ho_ten'] ?? '',
                'snapshot_kh_email' => $kh['email'] ?? '',
                'snapshot_kh_so_dien_thoai' => $kh['so_dien_thoai'] ?? '',
                'snapshot_kh_dia_chi' => $kh['dia_chi'] ?? '',
                
                'ngay_dat' => date('Y-m-d H:i:s'),
                'tong_tien' => $tong_tien,
                'da_thanh_toan' => $da_thanh_toan,
                'trang_thai' => $trang_thai
            ];

            $this->bookingModel->insert($data);
            header('Location: index.php?act=booking-list&msg=success');
            exit();
        }
    }
    
    // 4. CHI TIẾT
    public function detail() {
        $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->getOne($id);
        $guestList = $this->khachThamGiaModel->getByBookingId($id); 
        require_once 'views/admin/booking/detail.php';
    }

    // 5. CẬP NHẬT TRẠNG THÁI
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->bookingModel->updateStatus($_POST['id'], $_POST['trang_thai']);
            header("Location: " . BASE_URL . "?act=booking-detail&id=" . $_POST['id']);
            exit;
        }
    }
    
    // 6. EDIT FORM
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->getOne($id);
        
        $toursStmt = $this->tourModel->getAll([]); 
        $tours = $toursStmt->fetchAll(PDO::FETCH_ASSOC);
        $customers = $this->khachHangModel->getAll();

        require_once 'views/admin/booking/edit.php';
    }

    // 7. XỬ LÝ CẬP NHẬT
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $tour_id = $_POST['tour_id'];
            $khach_hang_id = $_POST['khach_hang_id'];
            $so_luong_nguoi_lon = (int)$_POST['so_luong_nguoi_lon'];
            $so_luong_tre_em = (int)$_POST['so_luong_tre_em'];
            
            // Tính lại tiền (Backend Calculation để an toàn)
            $tour = $this->tourModel->getById($tour_id);
            $gia_nl = $this->toNumber($tour['gia_nguoi_lon'] > 0 ? $tour['gia_nguoi_lon'] : $tour['gia_tour']);
            $gia_te = $this->toNumber($tour['gia_tre_em'] > 0 ? $tour['gia_tre_em'] : ($gia_nl * 0.7));
            
            $tong_tien = ($gia_nl * $so_luong_nguoi_lon) + ($gia_te * $so_luong_tre_em);
            
            // Tiền đã thanh toán lấy từ form
            $da_thanh_toan = $this->toNumber($_POST['da_thanh_toan']);

            $kh = $this->khachHangModel->getById($khach_hang_id);

            $data = [
                'tour_id' => $tour_id,
                'snapshot_ten_tour' => $tour['ten_tour'],
                'khach_hang_id' => $khach_hang_id,
                'so_luong_nguoi_lon' => $so_luong_nguoi_lon,
                'so_luong_tre_em' => $so_luong_tre_em,
                'loai_booking' => $_POST['loai_booking'],
                
                'snapshot_kh_ho_ten' => $kh['ho_ten'] ?? '',
                'snapshot_kh_email' => $kh['email'] ?? '',
                'snapshot_kh_so_dien_thoai' => $kh['so_dien_thoai'] ?? '',
                'snapshot_kh_dia_chi' => $kh['dia_chi'] ?? '',
                
                'tong_tien' => $tong_tien,
                'da_thanh_toan' => $da_thanh_toan,
                'trang_thai' => $_POST['trang_thai']
            ];

            $this->bookingModel->update($id, $data);
            header("Location: " . BASE_URL . "?act=booking-list");
            exit;
        }
    }

    public function delete() {
        $this->bookingModel->delete($_GET['id']);
        header("Location: " . BASE_URL . "?act=booking-list");
        exit;
    }

    // ... (Giữ nguyên các hàm quản lý khách tham gia storeGuest, editGuest...)
    public function storeGuest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':booking_id' => $_POST['booking_id'],
                ':ho_ten' => $_POST['ho_ten'],
                ':gioi_tinh' => $_POST['gioi_tinh'],
                ':ngay_sinh' => $_POST['ngay_sinh'] ?: null,
                ':so_giay_to' => $_POST['so_giay_to'],
                ':ghi_chu' => $_POST['ghi_chu'],
                ':yeu_cau_dac_biet' => $_POST['yeu_cau_dac_biet']
            ];
            $this->khachThamGiaModel->insert($data);
            header("Location: " . BASE_URL . "?act=booking-detail&id=" . $_POST['booking_id']);
            exit;
        }
    }
    
    public function deleteGuest() {
        $this->khachThamGiaModel->delete($_GET['id']);
        header("Location: " . BASE_URL . "?act=booking-detail&id=" . $_GET['booking_id']);
        exit;
    }
    
    public function editGuest() {
        $id = $_GET['id'];
        $booking_id = $_GET['booking_id'];
        $guest = $this->khachThamGiaModel->getOne($id);
        require_once 'views/admin/booking/guest_edit.php';
    }
    
    public function updateGuest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ho_ten' => $_POST['ho_ten'],
                'gioi_tinh' => $_POST['gioi_tinh'],
                'ngay_sinh' => $_POST['ngay_sinh'] ?: null,
                'so_giay_to' => $_POST['so_giay_to'],
                'ghi_chu' => $_POST['ghi_chu'],
                'yeu_cau_dac_biet' => $_POST['yeu_cau_dac_biet']
            ];
            $this->khachThamGiaModel->update($_POST['id'], $data);
            header("Location: " . BASE_URL . "?act=booking-detail&id=" . $_POST['booking_id']);
            exit;
        }
    }
}
?>