<?php
// Nạp các Model cần thiết
require_once 'models/LichKhoiHanhModel.php';
require_once 'models/TourModel.php';
require_once 'models/NhaCungCapModel.php';
require_once 'models/HuongDanVienModel.php';
require_once 'models/PhanCongNhanSuModel.php';

// Kiểm tra file tồn tại trước khi require
if (file_exists('models/PhanBoDichVuModel.php')) require_once 'models/PhanBoDichVuModel.php';
if (file_exists('models/BaoCaoModel.php'))       require_once 'models/BaoCaoModel.php';

class LichKhoiHanhController {
    private $db;
    private $model;
    private $tourModel;
    private $nccModel;
    private $hdvModel;
    private $phanCongModel;
    private $dvModel;
    private $baoCaoModel;

    public function __construct($db) {
        $this->db = $db;
        
        // Khởi tạo Model
        $this->model = new LichKhoiHanhModel($db);
        $this->tourModel = new TourModel($db);
        $this->nccModel = new NhaCungCapModel($db);
        $this->hdvModel = new HuongDanVienModel($db);
        $this->phanCongModel = new PhanCongNhanSuModel($db);
        
        // Khởi tạo model phụ nếu có class
        if (class_exists('PhanBoDichVuModel')) {
            $this->dvModel = new PhanBoDichVuModel($db);
        }
        if (class_exists('BaoCaoModel')) {
            $this->baoCaoModel = new BaoCaoModel($db);
        }
    }

    // 1. Danh sách Lịch Khởi Hành
    public function index() {
        // [FIX LỖI] Gọi đúng tên hàm getAll() trong Model
        $lichs = $this->model->getAll(); 
        require ROOT . "/views/admin/lich_khoi_hanh/index.php";
    }

    // 2. Form Lập kế hoạch
    public function create() {
        $tours = $this->tourModel->getAll();
        $nccs = $this->nccModel->getAll();
        
        $hdvs = $this->hdvModel->getAll();
        if ($hdvs instanceof PDOStatement) $hdvs = $hdvs->fetchAll(PDO::FETCH_ASSOC);

        require ROOT . "/views/admin/lich_khoi_hanh/create.php";
    }

    // 3. Lưu kế hoạch
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $newId = $this->model->createPlan($_POST);
                header("Location: index.php?act=lich-detail&id=" . $newId);
                exit;
            } catch (Exception $e) {
                echo "Lỗi: " . $e->getMessage();
            }
        }
    }

    // 4. Form Sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $lich = $this->model->getById($id);
        $tours = $this->tourModel->getAll();
        
        if (!$lich) die("Lịch không tồn tại");

        require ROOT . "/views/admin/lich_khoi_hanh/edit.php";
    }

    // 5. Cập nhật
    public function update() {
        $id = $_POST['id'];
        $this->model->update($id, $_POST);
        header("Location: index.php?act=lich-khoi-hanh");
        exit;
    }

    // 6. Xóa
    public function delete() {
        $id = $_GET['id'];
        $this->model->delete($id);
        header("Location: index.php?act=lich-khoi-hanh");
        exit;
    }

    // 7. Xem Chi tiết (Điều hành)
    public function detail() {
        $id = $_GET['id'];
        $lich = $this->model->getById($id);
        if (!$lich) die("Lịch trình không tồn tại");
        
        // Lấy dữ liệu liên quan
        $list_nhansu = $this->phanCongModel->getByLich($id);
        
        // Lấy dịch vụ (nếu có model)
        $list_dichvu = [];
        if ($this->dvModel) {
            // Nếu model LichKhoiHanhModel có hàm getServices thì dùng, không thì dùng PhanBoDichVuModel
            if (method_exists($this->model, 'getServices')) {
                $list_dichvu = $this->model->getServices($id);
            } elseif (method_exists($this->dvModel, 'getByLich')) {
                $list_dichvu = $this->dvModel->getByLich($id);
            }
        }

        // Dữ liệu dropdown cho modal
        $dsNCC = $this->nccModel->getAll();
        $dsHDV = $this->hdvModel->getAll();
        if ($dsHDV instanceof PDOStatement) $dsHDV = $dsHDV->fetchAll(PDO::FETCH_ASSOC);

        require ROOT . "/views/admin/lich_khoi_hanh/detail.php";
    }

    // --- CÁC HÀM PHỤ KHÁC ---
    public function phanCong() { $this->detail(); }

    public function phanCongStore() {
        $this->phanCongModel->insert($_POST);
        header("Location: index.php?act=lich-detail&id=" . $_POST['lich_id']);
        exit;
    }

    public function phanCongDelete() {
        $id = $_GET['id'];
        $lich_id = $_GET['lich_id'];
        $this->phanCongModel->delete($id);
        header("Location: index.php?act=lich-detail&id=" . $lich_id);
        exit;
    }

    public function storeDichVu() {
        if ($this->dvModel) $this->dvModel->insert($_POST);
        header("Location: index.php?act=lich-detail&id=" . $_POST['lich_id']);
        exit;
    }

    public function deleteDichVu() {
        $id = $_GET['id'];
        $lich_id = $_GET['lich_id'];
        if ($this->dvModel) $this->dvModel->delete($id);
        header("Location: index.php?act=lich-detail&id=" . $lich_id);
        exit;
    }

    public function baoCao() {
        $lich_id = $_GET['id'];
        $lich = $this->model->getById($lich_id);
        
        $bc = $this->baoCaoModel ? $this->baoCaoModel->getDoanhThuTheoLich($lich_id) : [];
        require ROOT . "/views/admin/baocao/lich.php";
    }
    
    // API Check HDV
    public function checkHdvAvailability() {
        header('Content-Type: application/json');
        $start = $_GET['start'] ?? '';
        $end = $_GET['end'] ?? '';
        if (!$start || !$end) { echo json_encode([]); exit; }
        
        $busyList = $this->hdvModel->getBusyHDVs($start, $end);
        echo json_encode($busyList);
        exit;
    }
}
?>