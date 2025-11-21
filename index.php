<?php 
session_start();
require_once './commons/env.php';
require_once './commons/function.php';

// 1. Kết nối CSDL ngay đầu trang
$conn = connectDB();

// 2. Định nghĩa hằng ROOT nếu chưa có (để dùng trong Controller)
if (!defined('ROOT')) {
    define('ROOT', PATH_ROOT);
}

// Autoload class
spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class);
    if (file_exists("./controllers/$file.php")) {
        require_once "./controllers/$file.php";
    } elseif (file_exists("./models/$file.php")) {
        require_once "./models/$file.php";
    }
});

$act = $_GET['act'] ?? '/';

switch ($act) {
    
    // === MẶC ĐỊNH VÀO LOGIN ===
    case '/':
        // Nếu chưa đăng nhập thì về trang login
        if (!isset($_SESSION['user_admin'])) {
            header("Location: " . BASE_URL . "?act=login");
        } else {
            header("Location: " . BASE_URL . "?act=admin");
        }
        exit;

    // === AUTH ===
    case 'login':
        (new AuthController())->login();
        break;
    
    case 'check-login':
        (new AuthController())->checkLogin(); 
        break;
        
    case 'logout':
        (new AuthController())->logout();
        break;

    // === ADMIN DASHBOARD ===
    case 'admin':
        if (isset($_SESSION['user_admin'])) {
            (new AdminController())->Home();
        } else {
            header("Location: " . BASE_URL . "?act=login");
            exit;
        }
        break;

    // === QUẢN LÝ TOUR (Đã sửa) ===
    case 'tours':
        // Kiểm tra đăng nhập trước khi cho vào
        if (isset($_SESSION['user_admin'])) {
            (new TourController($conn))->index();
        } else {
            header("Location: " . BASE_URL . "?act=login");
        }
        break;
        
    case 'tour-create':
        (new TourController($conn))->create();
        break;
        
    case 'tour-store':
        (new TourController($conn))->store();
        break;
        
    case 'tour-edit':
        (new TourController($conn))->edit();
        break;
        
    case 'tour-update':
        (new TourController($conn))->update();
        break;
        
    case 'tour-delete':
        (new TourController($conn))->delete();
        break;

    // === QUẢN LÝ CHI TIẾT TOUR (Show) ===
    case 'tour-detail': // Hoặc action 'show' tùy controller
        (new TourController($conn))->show();
        break;

    default:
        echo "404 Not Found";
        break;
}
?>