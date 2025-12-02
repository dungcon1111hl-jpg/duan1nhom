<?php
class AuthController {
    public $UserModel;

    public function __construct() {
        $this->UserModel = new User();
    }

    // 1. Login Admin/Staff (Mặc định)
    public function login() {
        if (isset($_SESSION['user_admin'])) {
            header("Location: " . BASE_URL . "?act=admin");
            exit;
        }
        require_once './views/login.php';
    }

    // 2. [MỚI] Login Hướng dẫn viên
    public function loginGuide() {
        if (isset($_SESSION['user_admin'])) {
            header("Location: " . BASE_URL . "?act=admin"); // Hoặc trang dành riêng cho HDV
            exit;
        }
        require_once './views/guide_login.php';
    }

    // 3. Xử lý đăng nhập (Dùng chung)
    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->UserModel->checkLogin($username, $password);

            if ($user) {
                // [QUAN TRỌNG] Thêm 'guide' vào danh sách quyền được phép
                if ($user['role'] == 'admin' || $user['role'] == 'staff' || $user['role'] == 'guide') {
                    
                    $_SESSION['user_admin'] = $user; // Lưu session chung
                    
                    // Điều hướng dựa trên quyền (nếu muốn tách trang)
                    if ($user['role'] == 'guide') {
                        header("Location: " . BASE_URL . "?act=admin"); // Hoặc ?act=guide-dashboard nếu có
                    } else {
                        header("Location: " . BASE_URL . "?act=admin");
                    }
                    exit;
                } else {
                    $_SESSION['error'] = "Tài khoản của bạn không có quyền truy cập!";
                }
            } else {
                $_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
            }
            
            // Trả về trang login tương ứng (đơn giản là quay lại trang trước đó hoặc mặc định)
            echo "<script>window.history.back();</script>";
            exit;
        }
    }

    public function logout() {
        unset($_SESSION['user_admin']);
        header("Location: " . BASE_URL . "?act=login");
        exit;
    }
}
?>