<?php
require_once 'models/LichBanHDVModel.php';
require_once 'models/HuongDanVienModel.php'; // Để lấy danh sách HDV

class LichBanHDVController {
    private $model;
    private $hdvModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new LichBanHDVModel($db);
        $this->hdvModel = new HuongDanVienModel($db);
    }

    public function index() {
        $list_ban = $this->model->getAll();
        require ROOT . "/views/admin/lich_ban_hdv/index.php";
    }

    public function create() {
        // Lấy danh sách HDV để chọn
        $hdvs = $this->hdvModel->getAll();
        if ($hdvs instanceof PDOStatement) $hdvs = $hdvs->fetchAll(PDO::FETCH_ASSOC);
        
        require ROOT . "/views/admin/lich_ban_hdv/create.php";
    }

    public function store() {
        $this->model->insert($_POST);
        header("Location: index.php?act=lich-ban-hdv");
        exit;
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $lich_ban = $this->model->getById($id);
        if (!$lich_ban) die("Dữ liệu không tồn tại");

        $hdvs = $this->hdvModel->getAll();
        if ($hdvs instanceof PDOStatement) $hdvs = $hdvs->fetchAll(PDO::FETCH_ASSOC);

        require ROOT . "/views/admin/lich_ban_hdv/edit.php";
    }

    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header("Location: index.php?act=lich-ban-hdv");
        exit;
    }

    public function delete() {
        $id = $_GET['id'];
        $this->model->delete($id);
        header("Location: index.php?act=lich-ban-hdv");
        exit;
    }
}
?>