<?php
require_once 'models/HuongDanVienModel.php';

class HuongDanVienController {

    private $db;
    private $model;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->model = new HuongDanVienModel($db);
    }

    // Hàm phụ trợ cho upload ảnh đại diện HDV
    private function uploadImage($oldImage = null) {
        if (empty($_FILES['anh_dai_dien']['name'])) return $oldImage;
        
        $folder = "uploads/hdv/";
        if (!is_dir(PATH_ROOT . $folder)) mkdir(PATH_ROOT . $folder, 0777, true);

        // Xóa ảnh cũ
        if ($oldImage && file_exists(PATH_ROOT . $oldImage)) unlink(PATH_ROOT . $oldImage);

        $ext = pathinfo($_FILES['anh_dai_dien']['name'], PATHINFO_EXTENSION);
        $newName = $folder . "hdv_" . time() . "_" . rand(100,999) . "." . $ext;
        
        move_uploaded_file($_FILES['anh_dai_dien']['tmp_name'], PATH_ROOT . $newName);
        return $newName;
    }

    // 1. Danh sách HDV
    public function index() {
        $hdvs = $this->model->getAll(); 
        if (is_object($hdvs)) {
            $hdvs = $hdvs->fetchAll(PDO::FETCH_ASSOC);
        }
        require ROOT . "/views/admin/huongdanvien/list.php";
    }

    // 2. Form thêm mới
    public function create() {
        require ROOT . "/views/admin/huongdanvien/create.php";
    }

    // 3. Lưu dữ liệu
    public function store() {
        $_POST['anh_dai_dien'] = $this->uploadImage();
        
        $this->model->insert($_POST);
        header("Location: index.php?act=hdv-list");
        exit;
    }

    // 4. Form sửa
    public function edit() {
        $id = $_GET['id'];
        $hdv = $this->model->getOne($id);
        
        if (!$hdv) die("HDV không tồn tại!");
        require ROOT . "/views/admin/huongdanvien/edit.php";
    }

    // 5. Cập nhật dữ liệu
    public function update() {
        $id = $_POST['id'];
        $anh_cu = $_POST['anh_cu'] ?? null;
        
        $_POST['anh_dai_dien'] = $this->uploadImage($anh_cu);
        
        $this->model->update($id, $_POST);
        header("Location: index.php?act=hdv-list");
        exit;
    }

    // 6. Xóa
    public function delete() {
        $id = $_GET['id'];
        $hdv = $this->model->getOne($id);
        
        // Xóa ảnh vật lý
        if ($hdv && !empty($hdv['anh_dai_dien']) && file_exists(PATH_ROOT . $hdv['anh_dai_dien'])) {
            unlink(PATH_ROOT . $hdv['anh_dai_dien']);
        }

        $this->model->delete($id);
        header("Location: index.php?act=hdv-list");
        exit;
    }
}