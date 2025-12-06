<?php
require_once __DIR__ . "/../models/NhatKyModel.php"; 

class NhatKyController {
    private PDO $db;
    private NhatKyModel $nhatKyModel;
    private TourModel $tourModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->nhatKyModel = new NhatKyModel($db);
        $this->tourModel = new TourModel($db);
    }

    // Hiển thị danh sách và form
    public function index() {
        // Lấy ID tour từ URL
        $tour_id = $_GET['id'] ?? 0;
        
        // --- TRƯỜNG HỢP 1: CHƯA CHỌN TOUR ---
        if ($tour_id == 0) {
            $ds_tour = $this->tourModel->getAll(); 
            require ROOT . "/views/admin/nhat_ky_chon_tour.php"; 
            return;
        }

        // --- TRƯỜNG HỢP 2: ĐÃ CHỌN TOUR ---
        
        // 1. Lấy thông tin tour để hiện tên
        $tour = $this->tourModel->getById($tour_id);
        
        // 2. Lấy danh sách nhật ký
        $logs = $this->nhatKyModel->getAllByTourId($tour_id);

        // 3. Tính toán thống kê cho Dashboard nhỏ (Để file view không bị lỗi biến $stats)
        $stats = [
            'total' => count($logs),
            'su_co' => 0,
            'phan_hoi' => 0,
            'total_star' => 0,
            'count_star' => 0
        ];

        foreach ($logs as $log) {
            if ($log['loai_ghi_chep'] === 'SU_CO') $stats['su_co']++;
            if ($log['loai_ghi_chep'] === 'PHAN_HOI') $stats['phan_hoi']++;
            if ($log['loai_ghi_chep'] === 'DANH_GIA') {
                $stats['total_star'] += $log['danh_gia_sao'];
                $stats['count_star']++;
            }
        }
        
        $stats['danh_gia_avg'] = $stats['count_star'] > 0 ? round($stats['total_star'] / $stats['count_star'], 1) : 0;

        // 4. Gọi view hiển thị chi tiết
        // Lưu ý: Đảm bảo file này nằm đúng đường dẫn views/admin/nhat_ky_list.php
        require ROOT . "/views/admin/nhat_ky_list.php";
    }

    // Lưu dữ liệu
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_id = $_POST['lich_id'];
            
            // Xử lý dữ liệu
            $data = [
                'lich_id' => $tour_id,
                'hdv_id' => $_SESSION['user_admin']['id'] ?? null,
                'loai_ghi_chep' => $_POST['loai_ghi_chep'],
                'tieu_de' => $_POST['tieu_de'],
                'noi_dung' => $_POST['noi_dung'],
                'cach_xu_ly' => $_POST['cach_xu_ly'] ?? '',
                'danh_gia_sao' => $_POST['danh_gia_sao'] ?? 0
            ];

            $this->nhatKyModel->store($data);

            // Thông báo thành công (nếu view có hỗ trợ)
            $_SESSION['flash_success'] = "Đã lưu nhật ký thành công!";

            // Quay lại trang danh sách (SỬA LẠI act CHO ĐÚNG)
            header("Location: index.php?act=nhat-ky-tour&id=" . $tour_id);
            exit;
        }
    }

    // Xóa
    public function delete() {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        if ($id > 0) {
            $this->nhatKyModel->delete($id);
            $_SESSION['flash_success'] = "Đã xóa bản ghi thành công!";
        }
        
        // Quay lại trang danh sách (SỬA LẠI act CHO ĐÚNG)
        header("Location: index.php?act=nhat-ky-tour&id=" . $tour_id);
        exit;
    }
}