<?php
class AuthController {
    public $UserModel;

    public function __construct() {
        $this->UserModel = new User();
    }

    public function login() {
        if (isset($_SESSION['user_admin'])) {
            header("Location: " . BASE_URL . "?act=admin");
            exit;
        }
        require_once './views/login.php';
    }

    // [ĐÃ SỬA] Điều hướng thông minh hơn khi đã login
    public function loginGuide() {
        if (isset($_SESSION['user_admin'])) {
            if ($_SESSION['user_admin']['role'] == 'guide') {
                header("Location: " . BASE_URL . "?act=guide-dashboard");
            } else {
                header("Location: " . BASE_URL . "?act=admin");
            }
            exit;
        }
        require_once './views/guide_login.php';
    }

    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Hàm checkLogin trong Model đã sửa để trả về 'LOCKED' nếu bị khóa
            $result = $this->UserModel->checkLogin($username, $password);

            if ($result === "LOCKED") {
                $_SESSION['error'] = "Tài khoản này đã bị KHÓA. Vui lòng liên hệ quản trị viên!";
                echo "<script>window.history.back();</script>";
                exit;
            } 
            elseif ($result) {
                $_SESSION['user_admin'] = $result;
                
                // Phân quyền điều hướng sau khi đăng nhập thành công
                if ($result['role'] == 'guide') {
                    header("Location: " . BASE_URL . "?act=guide-dashboard");
                } else {
                    header("Location: " . BASE_URL . "?act=admin");
                }
                exit;
            } else {
                $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
                echo "<script>window.history.back();</script>";
                exit;
            }
        }
    }

    public function logout() {
        unset($_SESSION['user_admin']);
        // Sau khi logout có thể chọn về trang login chính hoặc trang chủ
        header("Location: " . BASE_URL . "?act=login");
        exit;
    }
}
?>