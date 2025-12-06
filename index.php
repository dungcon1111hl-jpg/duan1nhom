<?php 
session_start();

// 1. Nạp cấu hình và hàm chung
require_once './commons/env.php';
require_once './commons/function.php';

// 2. Xác định đường dẫn gốc
$baseDir = __DIR__;

// 3. Kết nối CSDL
$conn = connectDB();

// 4. Định nghĩa hằng ROOT
if (!defined('ROOT')) {
    define('ROOT', PATH_ROOT);
}

// ===============================================================
// NẠP MODELS VÀ CONTROLLERS
// ===============================================================

// --- MODELS CHÍNH ---
require_once $baseDir . '/models/User.php';
require_once $baseDir . '/models/TourModel.php';
require_once $baseDir . '/models/NhaCungCapModel.php';
require_once $baseDir . '/models/HinhAnhTourModel.php';
require_once $baseDir . '/models/LichTrinhModel.php';
require_once $baseDir . '/models/HuongDanVienModel.php';
require_once $baseDir . '/models/KhachHangModel.php';
require_once $baseDir . '/models/BookingModel.php';
require_once $baseDir . '/models/KhachThamGiaModel.php';
require_once $baseDir . '/models/ThanhToanModel.php';
require_once $baseDir . '/models/ChiPhiModel.php';
require_once $baseDir . '/models/DanhMucTourModel.php';

// --- MODELS PHỤ ---
if (file_exists($baseDir . '/models/LichKhoiHanhModel.php')) require_once $baseDir . '/models/LichKhoiHanhModel.php';
if (file_exists($baseDir . '/models/PhanCongNhanSuModel.php')) require_once $baseDir . '/models/PhanCongNhanSuModel.php';
if (file_exists($baseDir . '/models/PhanBoDichVuModel.php')) require_once $baseDir . '/models/PhanBoDichVuModel.php';
if (file_exists($baseDir . '/models/BaoCaoModel.php')) require_once $baseDir . '/models/BaoCaoModel.php';
if (file_exists($baseDir . '/models/DatDichVuModel.php')) require_once $baseDir . '/models/DatDichVuModel.php';
if (file_exists($baseDir . '/models/LichBanHDVModel.php')) require_once $baseDir . '/models/LichBanHDVModel.php';
if (file_exists($baseDir . '/models/GuideModel.php')) require_once $baseDir . '/models/GuideModel.php';
if (file_exists($baseDir . '/models/DiemDanhModel.php')) require_once $baseDir . '/models/DiemDanhModel.php';
if (file_exists($baseDir . '/models/PhanPhongModel.php')) require_once $baseDir . '/models/PhanPhongModel.php';
if (file_exists($baseDir . '/models/NhatKyModel.php')) require_once $baseDir . '/models/NhatKyModel.php';


// --- CONTROLLERS ---
require_once $baseDir . '/controllers/AuthController.php';
require_once $baseDir . '/controllers/AdminController.php';
require_once $baseDir . '/controllers/TourController.php';
require_once $baseDir . '/controllers/BookingController.php'; 
require_once $baseDir . '/controllers/HinhAnhTourController.php';
require_once $baseDir . '/controllers/LichTrinhController.php';
require_once $baseDir . '/controllers/HuongDanVienController.php';
require_once $baseDir . '/controllers/KhachHangController.php';
require_once $baseDir . '/controllers/ThanhToanController.php';
require_once $baseDir . '/controllers/ChiPhiController.php';
require_once $baseDir . '/controllers/NhaCungCapController.php';
require_once $baseDir . '/controllers/DatDichVuController.php';
require_once $baseDir . '/controllers/DanhMucTourController.php';
require_once $baseDir . '/controllers/UserController.php';

// Controllers bổ sung
if (file_exists($baseDir . '/controllers/LichKhoiHanhController.php')) require_once $baseDir . '/controllers/LichKhoiHanhController.php';
if (file_exists($baseDir . '/controllers/LichBanHDVController.php')) require_once $baseDir . '/controllers/LichBanHDVController.php';
if (file_exists($baseDir . '/controllers/LienKetTourController.php')) require_once $baseDir . '/controllers/LienKetTourController.php';
if (file_exists($baseDir . '/controllers/BaoCaoController.php')) require_once $baseDir . '/controllers/BaoCaoController.php';
if (file_exists($baseDir . '/controllers/GuideController.php')) require_once $baseDir . '/controllers/GuideController.php';

// Controllers chức năng mở rộng
if (file_exists($baseDir . '/controllers/DiemDanhController.php')) require_once $baseDir . '/controllers/DiemDanhController.php';
if (file_exists($baseDir . '/controllers/PhanPhongController.php')) require_once $baseDir . '/controllers/PhanPhongController.php';
if (file_exists($baseDir . '/controllers/NhatKyController.php')) require_once $baseDir . '/controllers/NhatKyController.php';


// ===============================================================
// ĐIỀU HƯỚNG (ROUTING)
// ===============================================================

$act = $_GET['act'] ?? '/';

switch ($act) {
    
    // === TRANG CHỦ / ĐĂNG NHẬP ===
    case '/':
        if (!isset($_SESSION['user_admin'])) {
            header("Location: " . BASE_URL . "?act=login");
        } else {
            // [ĐÃ SỬA] Kiểm tra quyền để chuyển hướng đúng Dashboard
            if (isset($_SESSION['user_admin']['role']) && $_SESSION['user_admin']['role'] == 'guide') {
                header("Location: " . BASE_URL . "?act=guide-dashboard");
            } else {
                header("Location: " . BASE_URL . "?act=admin");
            }
        }
        case 'khachhang-store-api': 
        (new KhachHangController($conn))->storeApi(); 
        break;
        exit;

    case 'login': (new AuthController())->login(); break;
    case 'login-guide': (new AuthController())->loginGuide(); break;
    case 'check-login': (new AuthController())->checkLogin(); break;
    case 'logout': (new AuthController())->logout(); break;

    // === ADMIN DASHBOARD ===
    case 'admin':
        if (isset($_SESSION['user_admin'])) {
            // Nếu là guide cố vào admin thì đẩy về guide-dashboard
            if ($_SESSION['user_admin']['role'] == 'guide') {
                header("Location: " . BASE_URL . "?act=guide-dashboard");
                exit;
            }
            (new AdminController())->Home(); 
        } else {
            header("Location: " . BASE_URL . "?act=login");
            exit;
        }
        break;

    // === 1. QUẢN LÝ TOUR ===
    case 'tours': (new TourController($conn))->index(); break;
    case 'tour-create': (new TourController($conn))->create(); break;
    case 'tour-store': (new TourController($conn))->store(); break;
    case 'tour-edit': (new TourController($conn))->edit(); break;
    case 'tour-update': (new TourController($conn))->update(); break;
    case 'tour-delete': (new TourController($conn))->delete(); break;
    case 'tour-detail': (new TourController($conn))->show(); break;
    case 'tour-check-hdv': (new TourController($conn))->checkAvailability(); break;

    // === 2. HÌNH ẢNH TOUR ===
    case 'tour-images': (new HinhAnhTourController($conn))->index(); break;
    case 'tour-images-store': (new HinhAnhTourController($conn))->store(); break;
    case 'tour-images-delete': (new HinhAnhTourController($conn))->delete(); break;

    // === 3. LỊCH TRÌNH TOUR ===
    case 'tour-schedule-store': (new LichTrinhController($conn))->store(); break;
    case 'tour-schedule-delete': (new LichTrinhController($conn))->delete(); break;
    
    // === 4. LỊCH KHỞI HÀNH ===
    case 'lich-khoi-hanh':        (new LichKhoiHanhController($conn))->index(); break;
    case 'lich-khoi-hanh-create': (new LichKhoiHanhController($conn))->create(); break;
    case 'lich-khoi-hanh-store':  (new LichKhoiHanhController($conn))->store(); break;
    case 'lich-khoi-hanh-edit':   (new LichKhoiHanhController($conn))->edit(); break;
    case 'lich-khoi-hanh-update': (new LichKhoiHanhController($conn))->update(); break;
    case 'lich-khoi-hanh-delete': (new LichKhoiHanhController($conn))->delete(); break;
    
    case 'lich-phan-cong':        (new LichKhoiHanhController($conn))->phanCong(); break;
    case 'lich-phan-cong-store':  (new LichKhoiHanhController($conn))->phanCongStore(); break;
    case 'lich-phan-cong-delete': (new LichKhoiHanhController($conn))->phanCongDelete(); break;
    
    case 'lich-detail':           (new LichKhoiHanhController($conn))->detail(); break;
    case 'lich-baocao':           (new LichKhoiHanhController($conn))->baoCao(); break;
    case 'api-check-hdv':         (new LichKhoiHanhController($conn))->checkAvailability(); break;
    case 'lich-danh-sach-khach':  (new LichKhoiHanhController($conn))->danhSachKhach(); break;
    case 'lich-update-dichvu-status': (new LichKhoiHanhController($conn))->updateDichVuStatus(); break;

    // === 5. HƯỚNG DẪN VIÊN & LỊCH BẬN ===
    case 'hdv-list':   (new HuongDanVienController($conn))->index(); break;
    case 'hdv-create': (new HuongDanVienController($conn))->create(); break;
    case 'hdv-store':  (new HuongDanVienController($conn))->store(); break;
    case 'hdv-edit':   (new HuongDanVienController($conn))->edit(); break;
    case 'hdv-update': (new HuongDanVienController($conn))->update(); break;
    case 'hdv-delete': (new HuongDanVienController($conn))->delete(); break;

    case 'lich-ban-hdv':    (new LichBanHDVController($conn))->index(); break;
    case 'lich-ban-create': (new LichBanHDVController($conn))->create(); break;
    case 'lich-ban-store':  (new LichBanHDVController($conn))->store(); break;
    case 'lich-ban-edit':   (new LichBanHDVController($conn))->edit(); break;
    case 'lich-ban-update': (new LichBanHDVController($conn))->update(); break;
    case 'lich-ban-delete': (new LichBanHDVController($conn))->delete(); break;

    // === 6. KHÁCH HÀNG ===
    case 'khach-hang':        (new KhachHangController($conn))->index(); break;
    case 'khachhang-create':  (new KhachHangController($conn))->create(); break;
    case 'khachhang-store':   (new KhachHangController($conn))->store(); break;
    case 'khachhang-edit':    (new KhachHangController($conn))->edit(); break;
    case 'khachhang-update':  (new KhachHangController($conn))->update(); break;
    case 'khachhang-delete':  (new KhachHangController($conn))->delete(); break;

    // === 7. BOOKING (ĐẶT TOUR) ===
    // === 7. BOOKING (ĐẶT TOUR) ===
case 'booking-list':          (new BookingController($conn))->index(); break;
case 'booking-create':        (new BookingController($conn))->create(); break;
case 'booking-store':         (new BookingController($conn))->store(); break;
case 'booking-detail':        (new BookingController($conn))->detail(); break;
case 'booking-edit':          (new BookingController($conn))->edit(); break;
case 'booking-update':        (new BookingController($conn))->update(); break;
case 'booking-delete':        (new BookingController($conn))->delete(); break;
case 'booking-update-status': (new BookingController($conn))->updateStatus(); break;

// --- QUẢN LÝ KHÁCH CỦA BOOKING ---
case 'booking-guest-list':    (new BookingController($conn))->guestList(); break;
case 'booking-guest-store':   (new BookingController($conn))->storeGuest(); break;
case 'booking-guest-edit':    (new BookingController($conn))->editGuest(); break;
case 'booking-guest-update':  (new BookingController($conn))->updateGuest(); break;
case 'booking-guest-delete':  (new BookingController($conn))->deleteGuest(); break;
case 'booking-guest-import':  (new BookingController($conn))->importGuests(); break;

    // === 8. THANH TOÁN ===
    case 'thanhtoan-list':   (new ThanhToanController($conn))->index(); break;
    case 'thanhtoan-create': (new ThanhToanController($conn))->create(); break;
    case 'thanhtoan-store':  (new ThanhToanController($conn))->store(); break;
    case 'thanhtoan-edit':   (new ThanhToanController($conn))->edit(); break;
    case 'thanhtoan-update': (new ThanhToanController($conn))->update(); break;
    case 'thanhtoan-delete': (new ThanhToanController($conn))->delete(); break;

    // === 9. DỊCH VỤ & CHI PHÍ ===
    case 'dichvu-list':   (new DatDichVuController($conn))->index(); break;
    case 'dichvu-create': (new DatDichVuController($conn))->create(); break;
    case 'dichvu-store':  (new DatDichVuController($conn))->store(); break;
    case 'dichvu-edit':   (new DatDichVuController($conn))->edit(); break;
    case 'dichvu-update': (new DatDichVuController($conn))->update(); break;
    case 'dichvu-delete': (new DatDichVuController($conn))->delete(); break;

    case 'chiphi-list':   (new ChiPhiController($conn))->index(); break;
    case 'chiphi-create': (new ChiPhiController($conn))->create(); break;
    case 'chiphi-store':  (new ChiPhiController($conn))->store(); break;
    case 'chiphi-edit':   (new ChiPhiController($conn))->edit(); break;
    case 'chiphi-update': (new ChiPhiController($conn))->update(); break;
    case 'chiphi-delete': (new ChiPhiController($conn))->delete(); break;

    // === 10. DANH MỤC & NHÀ CUNG CẤP ===
    case 'danhmuc-list':    (new DanhMucTourController($conn))->index(); break;
    case 'danhmuc-create':  (new DanhMucTourController($conn))->create(); break;
    case 'danhmuc-store':   (new DanhMucTourController($conn))->store(); break;
    case 'danhmuc-edit':    (new DanhMucTourController($conn))->edit(); break;
    case 'danhmuc-update':  (new DanhMucTourController($conn))->update(); break;
    case 'danhmuc-delete':  (new DanhMucTourController($conn))->delete(); break;

    case 'nha-cung-cap': (new NhaCungCapController($conn))->index(); break;
    case 'ncc-create':   (new NhaCungCapController($conn))->create(); break;
    case 'ncc-store':    (new NhaCungCapController($conn))->store(); break;
    case 'ncc-edit':     (new NhaCungCapController($conn))->edit(); break;
    case 'ncc-update':   (new NhaCungCapController($conn))->update(); break;
    case 'ncc-delete':   (new NhaCungCapController($conn))->delete(); break;

    case 'lien-ket-list':   (new LienKetTourController($conn))->index(); break;
    case 'lien-ket-store':  (new LienKetTourController($conn))->store(); break;
    case 'lien-ket-delete': (new LienKetTourController($conn))->delete(); break;

    // === 11. ĐIỂM DANH (Admin View) ===
    case 'diem-danh':       (new DiemDanhController())->index(); break;
    case 'diem-danh-store': (new DiemDanhController())->store(); break;
    case 'diem-danh-list':  (new DiemDanhController())->list(); break;

    // === 12. PHÂN PHÒNG ===
    case 'phan-phong':
    case 'phan-phong-create': (new PhanPhongController())->create(); break;
    case 'phan-phong-store':  (new PhanPhongController())->store(); break;
    case 'phan-phong-list':   (new PhanPhongController())->list(); break;

    // === 13. NHẬT KÝ TOUR (Admin View) ===
    case 'nhat-ky-tour':   (new NhatKyController($conn))->index(); break;
    case 'nhat-ky-store':  (new NhatKyController($conn))->store(); break;
    case 'nhat-ky-delete': (new NhatKyController($conn))->delete(); break;

    // === 14. USER & BÁO CÁO ===
    case 'user-list':          (new UserController())->index(); break;
    case 'user-create':        (new UserController())->create(); break;
    case 'user-store':         (new UserController())->store(); break;
    case 'user-edit':          (new UserController())->edit(); break;
    case 'user-update':        (new UserController())->update(); break;
    case 'user-delete':        (new UserController())->delete(); break;
    case 'user-toggle-status': (new UserController())->toggleStatus(); break;

    case 'baocao-tonghop':     (new BaoCaoController($conn))->index(); break;

    // === 15. GIAO DIỆN HƯỚNG DẪN VIÊN (HDV) ===
    case 'guide-dashboard':     (new GuideController($conn))->index(); break;      
    case 'guide-detail':        (new GuideController($conn))->detail(); break;     
    case 'guide-update-note':   (new GuideController($conn))->updateNote(); break; 
    case 'guide-store-diary':   (new GuideController($conn))->storeDiary(); break; 
    case 'api-checkin':         (new GuideController($conn))->apiCheckIn(); break; 

    default:
        require ROOT . "/views/404.html";
        break;
}
?>