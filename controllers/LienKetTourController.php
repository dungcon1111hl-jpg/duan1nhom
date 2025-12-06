<?php
require_once 'models/LienKetTourModel.php';
require_once 'models/TourModel.php';
require_once 'models/NhaCungCapModel.php';

class LienKetTourController {
    private $model;
    private $tourModel;
    private $nccModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new LienKetTourModel($db);
        $this->tourModel = new TourModel($db);
        $this->nccModel = new NhaCungCapModel($db);
    }

    // 1. Hiển thị danh sách NCC của Tour
    public function index() {
        $tour_id = $_GET['tour_id'] ?? 0;
        
        if (!$tour_id) {
            echo "<script>alert('Vui lòng chọn Tour!'); window.location.href='index.php?act=tours';</script>";
            return;
        }

        // Lấy thông tin Tour
        $tour = $this->tourModel->getById($tour_id);
        
        // Lấy danh sách liên kết
        $links = $this->model->getByTour($tour_id);
        
        // Lấy tất cả NCC để đổ vào dropdown
        $allNCC = $this->nccModel->getAll();

        require ROOT . "/views/admin/lienket/list.php";
    }

    // 2. Xử lý Thêm / Sửa liên kết
    public function store() {
        $tour_id = $_POST['tour_id'];
        $gia_net = (float)$_POST['gia_net'];
        $gia_ban = (float)$_POST['gia_ban'];

        // --- VALIDATE DỮ LIỆU ---
        if ($gia_net <= 0) {
            echo "<script>alert('Lỗi: Giá NCC (Net) phải lớn hơn 0!'); window.history.back();</script>";
            return;
        }

        if ($gia_ban < $gia_net) {
            echo "<script>alert('Lỗi: Giá bán thấp hơn giá vốn! Vui lòng kiểm tra lại.'); window.history.back();</script>";
            return;
        }

        // Lưu dữ liệu
        $this->model->save($_POST);
        
        header("Location: index.php?act=lien-ket-list&tour_id=" . $tour_id);
        exit;
    }

    // 3. Xóa liên kết
    public function delete() {
        $id = $_GET['id'];
        $tour_id = $_GET['tour_id'];
        
        $this->model->delete($id);
        
        header("Location: index.php?act=lien-ket-list&tour_id=" . $tour_id);
        exit;
    }
}
?>