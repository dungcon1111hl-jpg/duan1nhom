<?php 
session_start();
require_once './commons/env.php';
require_once './commons/function.php';

// Xác định thư mục gốc tuyệt đối của dự án
$baseDir = __DIR__;

// 1. Kết nối CSDL ngay đầu trang
$conn = connectDB();

// 2. Định nghĩa hằng ROOT (PATH_ROOT)
if (!defined('ROOT')) {
    define('ROOT', PATH_ROOT);
}

// ===========================================
// [FIX QUAN TRỌNG] NẠP TẤT CẢ MODEL VÀ CONTROLLER THỦ CÔNG
// (Bỏ Autoload để tránh lỗi "Class Not Found" do Laragon/đường dẫn)
// ===========================================

// Models
require_once $baseDir . '/models/TourModel.php';
require_once $baseDir . '/models/NhaCungCapModel.php';
require_once $baseDir . '/models/HinhAnhTourModel.php';
require_once $baseDir . '/models/LichTrinhModel.php';
require_once $baseDir . '/models/HuongDanVienModel.php'; // HDV Model

// Controllers
require_once $baseDir . '/controllers/AuthController.php';
require_once $baseDir . '/controllers/AdminController.php';
require_once $baseDir . '/controllers/TourController.php';
require_once $baseDir . '/controllers/HinhAnhTourController.php';
require_once $baseDir . '/controllers/LichTrinhController.php';
require_once $baseDir . '/controllers/HuongDanVienController.php'; // HDV Controller


$act = $_GET['act'] ?? '/';

switch ($act) {
    
    // === MẶC ĐỊNH & AUTH ===
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

    case 'admin':
        if (isset($_SESSION['user_admin'])) {
            (new AdminController())->Home(); 
        } else {
            header("Location: " . BASE_URL . "?act=login");
            exit;
        }
        break;

    // === QUẢN LÝ TOUR ===
    case 'tours':
        if (isset($_SESSION['user_admin'])) {
            (new TourController($conn))->index();
        } else { header("Location: " . BASE_URL . "?act=login"); }
        break;
        
    case 'tour-create': (new TourController($conn))->create(); break;
    case 'tour-store': (new TourController($conn))->store(); break;
    case 'tour-edit': (new TourController($conn))->edit(); break;
    case 'tour-update': (new TourController($conn))->update(); break;
    case 'tour-delete': (new TourController($conn))->delete(); break;
    case 'tour-detail': (new TourController($conn))->show(); break;

    // === QUẢN LÝ HÌNH ẢNH TOUR ===
    case 'tour-images': (new HinhAnhTourController($conn))->index(); break;
    case 'tour-images-store': (new HinhAnhTourController($conn))->store(); break;
    case 'tour-images-delete': (new HinhAnhTourController($conn))->delete(); break;

    // === QUẢN LÝ LỊCH TRÌNH TOUR ===
    case 'tour-schedule-store': (new LichTrinhController($conn))->store(); break;
    case 'tour-schedule-delete': (new LichTrinhController($conn))->delete(); break;
        
    // === QUẢN LÝ HƯỚNG DẪN VIÊN ===
    case 'hdv-list':
        (new HuongDanVienController($conn))->index();
        break;
    case 'hdv-create':
        (new HuongDanVienController($conn))->create();
        break;
    case 'hdv-store':
        (new HuongDanVienController($conn))->store();
        break;
    case 'hdv-edit':
        (new HuongDanVienController($conn))->edit();
        break;
    case 'hdv-update':
        (new HuongDanVienController($conn))->update();
        break;
    case 'hdv-delete':
        (new HuongDanVienController($conn))->delete();
        break;

    // === MẶC ĐỊNH ===
    default:
        echo "404 Not Found";
        break;
}
?>