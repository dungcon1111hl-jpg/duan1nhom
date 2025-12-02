<?php
require_once 'models/DanhMucTourModel.php';

class DanhMucTourController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new DanhMucTourModel($db);
    }

    public function index() {
        $danh_mucs = $this->model->getAll();
        require ROOT . "/views/admin/danhmuc/list.php";
    }

    public function create() {
        require ROOT . "/views/admin/danhmuc/create.php";
    }

    // [ĐÃ SỬA] Thêm kiểm tra trùng lặp
    public function store() {
        $ma = trim($_POST['ma_danh_muc']); // Loại bỏ khoảng trắng thừa
        
        // 1. Kiểm tra xem mã đã tồn tại chưa
        if ($this->model->checkExists($ma)) {
            // Nếu trùng: Báo lỗi javascript và quay lại
            echo "<script>
                    alert('Lỗi: Mã danh mục \"$ma\" đã tồn tại! Vui lòng chọn mã khác.');
                    window.history.back();
                  </script>";
            return; // Dừng xử lý, không insert nữa
        }

        // 2. Nếu không trùng thì mới thêm
        $this->model->insert($_POST);
        header("Location: index.php?act=danhmuc-list");
        exit;
    }

    public function edit() {
        $id = $_GET['id'];
        $dm = $this->model->getOne($id);
        require ROOT . "/views/admin/danhmuc/edit.php";
    }

    public function update() {
        $this->model->update($_POST['id'], $_POST);
        header("Location: index.php?act=danhmuc-list");
        exit;
    }

    public function delete() {
        $this->model->delete($_GET['id']);
        header("Location: index.php?act=danhmuc-list");
        exit;
    }
}
?>