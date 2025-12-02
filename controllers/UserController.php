<?php
// Nạp model User (đã được require ở index.php, nhưng require lại cho chắc chắn nếu chạy độc lập)
if (!class_exists('User')) require_once 'models/User.php';

class UserController {
    private $model;

    public function __construct() {
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
        // Validate cơ bản (bạn có thể thêm check trùng username/email ở đây)
        $this->model->insert($_POST);
        header("Location: index.php?act=user-list");
        exit;
    }

    public function edit() {
        $id = $_GET['id'];
        $user = $this->model->getOne($id);
        if (!$user) die("Người dùng không tồn tại");
        require ROOT . "/views/admin/users/edit.php";
    }

    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header("Location: index.php?act=user-list");
        exit;
    }

    public function delete() {
        $id = $_GET['id'];
        // Không cho xóa chính mình
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