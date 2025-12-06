<?php
// Nạp model User
if (!class_exists('User')) require_once 'models/User.php';

class UserController {
    private $model;

    public function __construct() {
        // [BẢO MẬT] CHẶN NHÂN VIÊN TRUY CẬP TRÁI PHÉP
        // Nếu chưa đăng nhập HOẶC vai trò không phải 'admin' -> Đá về trang chủ
        if (!isset($_SESSION['user_admin']) || $_SESSION['user_admin']['role'] !== 'admin') {
            echo "<script>
                    alert('CẢNH BÁO: Bạn không có quyền truy cập chức năng này!'); 
                    window.location.href='index.php?act=admin';
                  </script>";
            exit;
        }

        $this->model = new User();
    }

    public function index() {
        $users = $this->model->getAll();
        require ROOT . "/views/admin/users/index.php";
    }

    public function create() {
        require ROOT . "/views/admin/users/create.php";
    }

    public function store() {
        $this->model->insert($_POST);
        header("Location: index.php?act=user-list");
        exit;
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $user = $this->model->getOne($id);
        
        if (!$user) {
            echo "<script>alert('User không tồn tại!'); location.href='index.php?act=user-list';</script>";
            exit;
        }
        
        require ROOT . "/views/admin/users/edit.php";
    }

    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header("Location: index.php?act=user-list");
        exit;
    }

    // Chức năng KHÓA / MỞ KHÓA tài khoản
    public function toggleStatus() {
        $id = $_GET['id'];
        $currentStatus = $_GET['status']; 
        
        if ($id == $_SESSION['user_admin']['id']) {
            echo "<script>alert('Không thể tự khóa tài khoản chính mình!'); window.history.back();</script>";
            return;
        }

        $newStatus = ($currentStatus == 1) ? 0 : 1;
        $this->model->updateStatus($id, $newStatus);
        
        header("Location: index.php?act=user-list");
        exit;
    }

    public function delete() {
        $id = $_GET['id'];
        if ($id == $_SESSION['user_admin']['id']) {
            echo "<script>alert('Không thể xóa tài khoản đang đăng nhập!'); window.location.href='index.php?act=user-list';</script>";
            exit;
        }
        
        $this->model->delete($id);
        header("Location: index.php?act=user-list");
        exit;
    }
}
?>