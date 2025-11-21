<?php
class AuthController {
    public $UserModel;

    public function __construct() {
        $this->UserModel = new User();
    }

    // 1. Hiển thị form login (GET)
    public function login() {
        // Nếu đã login rồi thì đá về admin luôn
        if (isset($_SESSION['user_admin'])) {
            header("Location: " . BASE_URL . "?act=admin");
            exit;
        }
        require_once './views/login.php';
    }

    // 2. Xử lý đăng nhập (POST) - ĐÂY LÀ HÀM BẠN ĐANG BỊ LỖI
    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->UserModel->checkLogin($username, $password);

            if ($user) {
                if ($user['role'] == 'admin' || $user['role'] == 'staff') {
                    // Lưu session
                    $_SESSION['user_admin'] = $user;
                    header("Location: " . BASE_URL . "?act=admin");
                    exit;
                } else {
                    $_SESSION['error'] = "Bạn không có quyền Admin!";
                }
            } else {
                $_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
            }
            // Quay lại trang login nếu lỗi
            header("Location: " . BASE_URL . "?act=login");
            exit;
        }
    }

    // 3. Đăng xuất
    public function logout() {
        unset($_SESSION['user_admin']);
        header("Location: " . BASE_URL . "?act=login");
        exit;
    }
}
?>