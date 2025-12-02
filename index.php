<?php 
session_start();

// 1. Nạp cấu hình và hàm chung
require_once './commons/env.php';
require_once './commons/function.php';

// 2. Xác định đường dẫn gốc (Tuyệt đối)
$baseDir = __DIR__;

// 3. Kết nối CSDL
$conn = connectDB();

// 4. Định nghĩa hằng ROOT nếu chưa có
if (!defined('ROOT')) {
    define('ROOT', PATH_ROOT);
}

// ===============================================================
// [FIX QUAN TRỌNG] NẠP THỦ CÔNG TOÀN BỘ MODEL VÀ CONTROLLER
// ===============================================================

// --- 1. NẠP MODELS ---
require_once $baseDir . '/models/User.php';
require_once $baseDir . '/models/TourModel.php';
require_once $baseDir . '/models/NhaCungCapModel.php';
require_once $baseDir . '/models/HinhAnhTourModel.php';
require_once $baseDir . '/models/LichTrinhModel.php';
require_once $baseDir . '/models/HuongDanVienModel.php';
if (file_exists($baseDir . '/models/KhachHangModel.php')) require_once $baseDir . '/models/KhachHangModel.php';
if (file_exists($baseDir . '/models/LichKhoiHanhModel.php')) require_once $baseDir . '/models/LichKhoiHanhModel.php';
if (file_exists($baseDir . '/models/PhanCongNhanSuModel.php')) require_once $baseDir . '/models/PhanCongNhanSuModel.php';
if (file_exists($baseDir . '/models/PhanBoDichVuModel.php')) require_once $baseDir . '/models/PhanBoDichVuModel.php';
if (file_exists($baseDir . '/models/BaoCaoModel.php')) require_once $baseDir . '/models/BaoCaoModel.php';
if (file_exists($baseDir . '/models/DatDichVuModel.php')) require_once $baseDir . '/models/DatDichVuModel.php';
if (file_exists($baseDir . '/models/ChiPhiModel.php'))    require_once $baseDir . '/models/ChiPhiModel.php';
if (file_exists($baseDir . '/models/ThanhToanModel.php')) require_once $baseDir . '/models/ThanhToanModel.php';
if (file_exists($baseDir . '/models/BookingModel.php'))   require_once $baseDir . '/models/BookingModel.php';
if (file_exists($baseDir . '/models/DanhMucTourModel.php')) require_once $baseDir . '/models/DanhMucTourModel.php';
if (file_exists($baseDir . '/controllers/UserController.php')) require_once $baseDir . '/controllers/UserController.php';
// [MỚI] Nạp Model Lịch Bận HDV
if (file_exists($baseDir . '/models/LichBanHDVModel.php')) require_once $baseDir . '/models/LichBanHDVModel.php';
if (file_exists($baseDir . '/controllers/GuideController.php')) require_once $baseDir . '/controllers/GuideController.php';

// --- 2. NẠP CONTROLLERS ---
require_once $baseDir . '/controllers/AuthController.php';
require_once $baseDir . '/controllers/AdminController.php';
require_once $baseDir . '/controllers/TourController.php';
require_once $baseDir . '/controllers/HinhAnhTourController.php';
require_once $baseDir . '/controllers/LichTrinhController.php';
require_once $baseDir . '/controllers/HuongDanVienController.php';

// Nạp các controller phụ
if (file_exists($baseDir . '/controllers/KhachHangController.php')) require_once $baseDir . '/controllers/KhachHangController.php';
if (file_exists($baseDir . '/controllers/LichKhoiHanhController.php')) {
    require_once $baseDir . '/controllers/LichKhoiHanhController.php';
} elseif (file_exists($baseDir . '/controllers/Lichkhoihanhcontroller.php')) {
    require_once $baseDir . '/controllers/Lichkhoihanhcontroller.php';
}
if (file_exists($baseDir . '/controllers/DatDichVuController.php')) require_once $baseDir . '/controllers/DatDichVuController.php';
if (file_exists($baseDir . '/controllers/ChiPhiController.php'))    require_once $baseDir . '/controllers/ChiPhiController.php';
if (file_exists($baseDir . '/controllers/ThanhToanController.php')) require_once $baseDir . '/controllers/ThanhToanController.php';
if (file_exists($baseDir . '/controllers/BookingController.php'))   require_once $baseDir . '/controllers/BookingController.php'; 
if (file_exists($baseDir . '/controllers/DanhMucTourController.php')) require_once $baseDir . '/controllers/DanhMucTourController.php';

// [MỚI] Nạp Controller Lịch Bận HDV
if (file_exists($baseDir . '/controllers/LichBanHDVController.php')) require_once $baseDir . '/controllers/LichBanHDVController.php';


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
            header("Location: " . BASE_URL . "?act=admin");
        }
        exit;

    case 'login': (new AuthController())->login(); break;
    case 'check-login': (new AuthController())->checkLogin(); break;
    case 'logout': (new AuthController())->logout(); break;

    // === ADMIN DASHBOARD ===
    case 'admin':
        if (isset($_SESSION['user_admin'])) {
            (new AdminController())->Home(); 
        } else {
            header("Location: " . BASE_URL . "?act=login");
            exit;
        }
        break;
        case 'login': 
        (new AuthController())->login(); 
        break;
        
    case 'login-guide':  // [MỚI] Route cho HDV
        (new AuthController())->loginGuide(); 
        break;
    
    case 'check-login': 
        (new AuthController())->checkLogin(); 
        break;
// ...
    // Thêm vào danh sách case của Tour hoặc HDV
    case 'tour-check-hdv': 
        (new TourController($conn))->checkAvailability(); 
        break;
    // ...
    // === 1. QUẢN LÝ TOUR ===
    case 'tours': (new TourController($conn))->index(); break;
    case 'tour-create': (new TourController($conn))->create(); break;
    case 'tour-store': (new TourController($conn))->store(); break;
    case 'tour-edit': (new TourController($conn))->edit(); break;
    case 'tour-update': (new TourController($conn))->update(); break;
    case 'tour-delete': (new TourController($conn))->delete(); break;
    case 'tour-detail': (new TourController($conn))->show(); break;

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
    case 'lich-store-nhansu':     (new LichKhoiHanhController($conn))->storeNhanSu(); break;
    case 'lich-delete-nhansu':    (new LichKhoiHanhController($conn))->deleteNhanSu(); break;
    case 'lich-store-dichvu':     (new LichKhoiHanhController($conn))->storeDichVu(); break;
    case 'lich-delete-dichvu':    (new LichKhoiHanhController($conn))->deleteDichVu(); break;
    case 'lich-detail':           (new LichKhoiHanhController($conn))->detail(); break;
    case 'lich-baocao':           (new LichKhoiHanhController($conn))->baoCao(); break;
case 'api-check-hdv': (new LichKhoiHanhController($conn))->checkAvailability(); break;
    // === 5. HƯỚNG DẪN VIÊN ===
    case 'hdv-list':   (new HuongDanVienController($conn))->index(); break;
    case 'hdv-create': (new HuongDanVienController($conn))->create(); break;
    case 'hdv-store':  (new HuongDanVienController($conn))->store(); break;
    case 'hdv-edit':   (new HuongDanVienController($conn))->edit(); break;
    case 'hdv-update': (new HuongDanVienController($conn))->update(); break;
    case 'hdv-delete': (new HuongDanVienController($conn))->delete(); break;

    // === 6. KHÁCH HÀNG ===
    case 'khach-hang':        (new KhachHangController($conn))->index(); break;
    case 'khachhang-create':  (new KhachHangController($conn))->create(); break;
    case 'khachhang-store':   (new KhachHangController($conn))->store(); break;
    case 'khachhang-edit':    (new KhachHangController($conn))->edit(); break;
    case 'khachhang-update':  (new KhachHangController($conn))->update(); break;
    case 'khachhang-delete':  (new KhachHangController($conn))->delete(); break;
    case 'khachhang_list':   (new KhachHangController($conn))->index(); break;
    // === 7. BOOKING ===
   // === QUẢN LÝ BOOKING CHUNG (Danh sách, Thêm, Sửa, Xóa) ===
    case 'booking-list': (new BookingController())->index(); break;
    case 'booking-create': (new BookingController())->create(); break;
    case 'booking-store': (new BookingController())->store(); break;
    case 'booking-detail': (new BookingController())->detail(); break;
    case 'booking-edit': (new BookingController())->edit(); break;
    case 'booking-update': (new BookingController())->update(); break;
    case 'booking-delete': (new BookingController())->delete(); break;
    case 'booking-update-status': (new BookingController())->updateStatus(); break;
    
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

    // === 10. DANH MỤC TOUR ===
    case 'danhmuc-list':    (new DanhMucTourController($conn))->index(); break;
    case 'danhmuc-create':  (new DanhMucTourController($conn))->create(); break;
    case 'danhmuc-store':   (new DanhMucTourController($conn))->store(); break;
    case 'danhmuc-edit':    (new DanhMucTourController($conn))->edit(); break;
    case 'danhmuc-update':  (new DanhMucTourController($conn))->update(); break;
    case 'danhmuc-delete':  (new DanhMucTourController($conn))->delete(); break;

    // === 11. LỊCH BẬN HDV (Mới thêm) ===
    case 'lich-ban-hdv':    (new LichBanHDVController($conn))->index(); break;
    case 'lich-ban-create': (new LichBanHDVController($conn))->create(); break;
    case 'lich-ban-store':  (new LichBanHDVController($conn))->store(); break;
    case 'lich-ban-edit':   (new LichBanHDVController($conn))->edit(); break;
    case 'lich-ban-update': (new LichBanHDVController($conn))->update(); break;
    case 'lich-ban-delete': (new LichBanHDVController($conn))->delete(); break;
    // 2. Thêm Case vào switch($act)
    // === QUẢN LÝ TÀI KHOẢN USER ===
    case 'user-list':   (new UserController())->index(); break;
    case 'user-create': (new UserController())->create(); break;
    case 'user-store':  (new UserController())->store(); break;
    case 'user-edit':   (new UserController())->edit(); break;
    case 'user-update': (new UserController())->update(); break;
    case 'user-delete': (new UserController())->delete(); break;
    
    // === DÀNH CHO HƯỚNG DẪN VIÊN (Giao diện riêng) ===
    case 'guide-dashboard':     (new GuideController($conn))->index(); break;
    case 'guide-detail':        (new GuideController($conn))->detail(); break;
    case 'guide-update-guest':  (new GuideController($conn))->updatePassenger(); break;
    case 'guide-store-diary':   (new GuideController($conn))->storeDiary(); break;
    // === 404 NOT FOUND ===

    default:
        echo "404 Not Found - Trang không tồn tại!";
        break;
}
?>