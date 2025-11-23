<?php
require_once 'models/TourModel.php';
require_once 'models/NhaCungCapModel.php';
require_once 'models/HinhAnhTourModel.php';
require_once 'models/LichTrinhModel.php';
require_once 'models/HuongDanVienModel.php';

class TourController {
    private $db;
    private $tourModel;
    private $nccModel;
    private $hdvModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->tourModel = new TourModel($db);
        $this->nccModel = new NhaCungCapModel($db);
        $this->hdvModel = new HuongDanVienModel($db);
    }

    private function uploadImage(?string $oldImage = null): ?string {
        if (empty($_FILES['anh_minh_hoa']['name'])) return $oldImage;
        $folder = "uploads/tours/";
        if (!is_dir(PATH_ROOT . $folder)) mkdir(PATH_ROOT . $folder, 0777, true);

        if ($oldImage && file_exists(PATH_ROOT . $folder . $oldImage)) unlink(PATH_ROOT . $folder . $oldImage);

        $ext = pathinfo($_FILES['anh_minh_hoa']['name'], PATHINFO_EXTENSION);
        $newName = "tour_" . time() . "_" . rand(1000,9999) . "." . $ext;
        move_uploaded_file($_FILES['anh_minh_hoa']['tmp_name'], PATH_ROOT . $folder . $newName);
        return $newName;
    }

    public function index(): void {
        $tours = $this->tourModel->getAll($_GET);
        require ROOT . "/views/admin/tour_management.php";
    }

    // FIX: Dùng getAll()
    public function create(): void {
        $dsNcc = $this->nccModel->getAll();
        $dsHDV = $this->hdvModel->getAll(); // <--- ĐÃ FIX LỖI: Dùng getAll()
        if ($dsHDV instanceof PDOStatement) $dsHDV = $dsHDV->fetchAll(PDO::FETCH_ASSOC);

        require ROOT . "/views/admin/tour_create.php";
    }

    public function store(): void {
        $_POST['anh_minh_hoa'] = $this->uploadImage();
        
        $tour_id = $this->tourModel->store($_POST);
        
        if ($tour_id && !empty($_POST['ncc'])) {
            $this->tourModel->updateTourNCC($tour_id, $_POST['ncc']);
        }
        
        header("Location: index.php?act=tours");
        exit;
    }

    /**
     * Hiển thị form chỉnh sửa tour (Đã fix lỗi gọi hàm all())
     */
    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $tour = $this->tourModel->getById($id);
        if (!$tour) die("Tour không tồn tại!");

        // Dữ liệu Tab Thông tin chung
        $dsNcc = $this->nccModel->getAll();
        $dsHDV = $this->hdvModel->getAll(); // <--- ĐÃ FIX LỖI: Dùng getAll()
        if ($dsHDV instanceof PDOStatement) $dsHDV = $dsHDV->fetchAll(PDO::FETCH_ASSOC);
        $selectedNcc = $this->tourModel->getSelectedNCC($id);

        // Dữ liệu Tab Ảnh & Lịch trình
        $hinhAnhModel = new HinhAnhTourModel($this->db);
        $list_anh = $hinhAnhModel->getByTour($id);
        $lichTrinhModel = new LichTrinhModel($this->db);
        $list_lich_trinh = $lichTrinhModel->getByTour($id);

        require ROOT . "/views/admin/tour_edit.php";
    }

    public function update(): void {
        $id = (int)$_POST['id'];
        $_POST['anh_minh_hoa'] = $this->uploadImage($_POST['anh_cu'] ?? null);
        $this->tourModel->update($id, $_POST);
        header("Location: index.php?act=tour-edit&id=" . $id);
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->tourModel->softDelete($id);
        header("Location: index.php?act=tours");
        exit;
    }

    public function show(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $tour = $this->tourModel->getById($id);
            $hinhAnhModel = new HinhAnhTourModel($this->db);
            $list_anh = $hinhAnhModel->getByTour($id);
            $lichTrinhModel = new LichTrinhModel($this->db);
            $list_lich_trinh = $lichTrinhModel->getByTour($id);
            
            // Lấy thông tin HDV chi tiết để hiển thị (Nếu có hdv_id)
            $hdv = null;
            if (!empty($tour['hdv_id'])) {
                $hdv = $this->hdvModel->getOne($tour['hdv_id']);
            }
            
            require ROOT . "/views/admin/tour_show.php";
        }
    }
}
?>