<?php
require_once 'models/ChiPhiModel.php';
require_once 'models/LichKhoiHanhModel.php';

class ChiPhiController {
    private $model;
    private $lichModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new ChiPhiModel($db);
        $this->lichModel = new LichKhoiHanhModel($db);
    }

    // 1. Danh sách chi phí của 1 lịch trình
    public function index() {
        $lich_id = $_GET['lich_id'] ?? 0;
        
        if (!$lich_id) {
            // Nếu không có ID, quay về danh sách lịch để chọn
            echo "<script>alert('Vui lòng chọn chuyến đi!'); window.location.href='index.php?act=lich-khoi-hanh';</script>";
            return;
        }

        $lich = $this->lichModel->getById($lich_id);
        $chiphis = $this->model->getByLich($lich_id);
        
        // Gọi Model tính tổng tự động
        $tong_chi = $this->model->getTotalCost($lich_id);

        require ROOT . "/views/admin/chiphi/list.php";
    }

    // 2. Form thêm mới
    public function create() {
        $lich_id = $_GET['lich_id'] ?? 0;
        require ROOT . "/views/admin/chiphi/create.php";
    }

    // 3. Xử lý thêm mới
    public function store() {
        // Validate số tiền > 0
        if (empty($_POST['so_tien']) || (float)$_POST['so_tien'] <= 0) {
            echo "<script>alert('Số tiền phải lớn hơn 0!'); window.history.back();</script>";
            return;
        }

        $data = $_POST;
        $data['hinh_anh'] = $this->uploadImage();

        $this->model->insert($data);
        header("Location: index.php?act=chiphi-list&lich_id=" . $_POST['lich_id']);
        exit;
    }

    // 4. Form sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $chiphi = $this->model->getOne($id);
        
        if (!$chiphi) die("Khoản chi không tồn tại");
        
        require ROOT . "/views/admin/chiphi/edit.php";
    }

    // 5. Xử lý cập nhật
    public function update() {
        // Validate số tiền > 0
        if (empty($_POST['so_tien']) || (float)$_POST['so_tien'] <= 0) {
            echo "<script>alert('Số tiền phải lớn hơn 0!'); window.history.back();</script>";
            return;
        }

        $id = $_POST['id'];
        $data = $_POST;
        
        // Xử lý ảnh mới nếu có
        $newImage = $this->uploadImage();
        if ($newImage) {
            $data['hinh_anh'] = $newImage;
        }

        $this->model->update($id, $data);
        
        // Lấy lại thông tin để redirect đúng chỗ
        $chiphi = $this->model->getOne($id);
        header("Location: index.php?act=chiphi-list&lich_id=" . $chiphi['lich_id']);
        exit;
    }

    // 6. Xóa
    public function delete() {
        $id = $_GET['id'];
        $lich_id = $_GET['lich_id'];
        $this->model->delete($id);
        header("Location: index.php?act=chiphi-list&lich_id=" . $lich_id);
        exit;
    }

    // Hàm phụ trợ upload ảnh
    private function uploadImage() {
        if (!empty($_FILES['hinh_anh']['name'])) {
            $target_dir = "uploads/chiphi/";
            if (!is_dir(PATH_ROOT . $target_dir)) mkdir(PATH_ROOT . $target_dir, 0777, true);
            
            $file_name = time() . "_" . basename($_FILES['hinh_anh']['name']);
            $target_file = PATH_ROOT . $target_dir . $file_name;
            
            if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
                return $target_dir . $file_name;
            }
        }
        return null;
    }
}
?>