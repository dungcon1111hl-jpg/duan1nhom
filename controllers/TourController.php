<?php
require_once 'models/TourModel.php';
require_once 'models/NhaCungCapModel.php';
require_once 'models/HinhAnhTourModel.php';
require_once 'models/LichTrinhModel.php';
require_once 'models/HuongDanVienModel.php';
require_once 'models/DanhMucTourModel.php';

class TourController {
    private $db;
    private $tourModel;
    private $nccModel;
    private $hdvModel;
    private $danhmucModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->tourModel = new TourModel($db);
        $this->nccModel = new NhaCungCapModel($db);
        $this->hdvModel = new HuongDanVienModel($db);
        $this->danhmucModel = new DanhMucTourModel($db);
    }

    // [MỚI] API Kiểm tra lịch bận HDV (Trả về JSON cho JS xử lý)
    public function checkAvailability() {
        header('Content-Type: application/json');
        $start = $_GET['start'] ?? '';
        $end   = $_GET['end'] ?? '';

        if (empty($start) || empty($end)) {
            echo json_encode([]);
            exit;
        }

        // Gọi Model HuongDanVien để lấy danh sách bận
        // (Hàm getBusyHDVs đã được cung cấp ở bước trước)
        $busyList = $this->hdvModel->getBusyHDVs($start, $end);
        
        echo json_encode($busyList);
        exit;
    }

    // Upload ảnh
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
        $dsDanhMuc = $this->danhmucModel->getAll();
        $tours = $this->tourModel->getAll($_GET);
        require ROOT . "/views/admin/tour_management.php";
    }

    public function create(): void {
        $dsNcc = $this->nccModel->getAll();
        
        // Lấy tất cả HDV (bao gồm cả người bận, JS sẽ lọc sau)
        $dsHDV = $this->hdvModel->getAll(); 
        if ($dsHDV instanceof PDOStatement) $dsHDV = $dsHDV->fetchAll(PDO::FETCH_ASSOC);

        $dsDanhMuc = $this->danhmucModel->getAll();

        require ROOT . "/views/admin/tour_create.php";
    }

    public function store(): void {
        // [VALIDATE] Kiểm tra lại lần cuối ở server xem HDV có bận không
        if (!empty($_POST['hdv_id']) && !empty($_POST['ngay_khoi_hanh']) && !empty($_POST['ngay_ket_thuc'])) {
            $busyList = $this->hdvModel->getBusyHDVs($_POST['ngay_khoi_hanh'], $_POST['ngay_ket_thuc']);
            if (isset($busyList[$_POST['hdv_id']])) {
                echo "<script>alert('Lỗi: HDV đã bận vào ngày này! Vui lòng chọn người khác.'); window.history.back();</script>";
                return;
            }
        }

        $_POST['anh_minh_hoa'] = $this->uploadImage();
        $tour_id = $this->tourModel->store($_POST);
        
        if ($tour_id && !empty($_POST['ncc'])) {
            $this->tourModel->updateTourNCC($tour_id, $_POST['ncc']);
        }
        
        header("Location: index.php?act=tours");
        exit;
    }

    // [CẬP NHẬT] edit: Lấy danh sách Danh mục
    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $tour = $this->tourModel->getById($id);
        if (!$tour) die("Tour không tồn tại!");

        $dsNcc = $this->nccModel->getAll();
        
        $dsHDV = $this->hdvModel->getAll();
        if ($dsHDV instanceof PDOStatement) $dsHDV = $dsHDV->fetchAll(PDO::FETCH_ASSOC);

        // [MỚI] Lấy danh mục
        $dsDanhMuc = $this->danhmucModel->getAll();

        $selectedNcc = $this->tourModel->getSelectedNCC($id);
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
            
            $hdv = null;
            if (!empty($tour['hdv_id'])) {
                $hdv = $this->hdvModel->getOne($tour['hdv_id']);
            }

            // Lấy tên danh mục để hiển thị chi tiết
            $danhmuc = $this->danhmucModel->getOne($tour['loai_tour']); 
            
            require ROOT . "/views/admin/tour_show.php";
        }
    }
}
?>