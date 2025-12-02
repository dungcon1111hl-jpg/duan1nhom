<?php
require_once 'models/GuideModel.php';

class GuideController {
    private $model;

    public function __construct($db) {
        // Kiểm tra quyền: Phải là HDV mới được vào
        if (!isset($_SESSION['user_admin']) || $_SESSION['user_admin']['role'] !== 'guide') {
            header("Location: " . BASE_URL . "?act=login");
            exit;
        }
        $this->model = new GuideModel($db);
    }

    // 1. Dashboard: Danh sách Tour của tôi
    public function index() {
        $user_id = $_SESSION['user_admin']['id'];
        $myTours = $this->model->getMyTours($user_id);
        require ROOT . "/views/guide/dashboard.php";
    }

    // 2. Chi tiết Tour (Chứa các Tab: Lịch trình, Khách, Nhật ký)
    public function detail() {
        $lich_id = $_GET['id'];
        $tour = $this->model->getTourDetail($lich_id);
        
        if (!$tour) die("Không tìm thấy lịch trình hoặc bạn không được phân công.");

        $passengers = $this->model->getPassengers($lich_id);
        $diaries = $this->model->getDiaries($lich_id);
        
        require ROOT . "/views/guide/tour_detail.php";
    }

    // 3. Xử lý Điểm danh & Cập nhật ghi chú (Ajax hoặc Form)
    public function updatePassenger() {
        $id = $_POST['passenger_id'];
        $status = isset($_POST['checkin']) ? 1 : 0;
        $note = $_POST['note'];
        
        $this->model->updatePassengerStatus($id, $status, $note);
        
        // Quay lại trang chi tiết, tab khách hàng
        header("Location: index.php?act=guide-detail&id=" . $_POST['lich_id'] . "#guests");
    }

    // 4. Viết nhật ký / Báo cáo sự cố
    public function storeDiary() {
        $user_id = $_SESSION['user_admin']['id'];
        $hdv_id = $this->model->getHdvIdByUserId($user_id);
        
        // Upload ảnh
        $hinh_anh = null;
        if (!empty($_FILES['hinh_anh']['name'])) {
            $target = "uploads/nhatky/" . time() . "_" . $_FILES['hinh_anh']['name'];
            if(move_uploaded_file($_FILES['hinh_anh']['tmp_name'], PATH_ROOT . $target)) {
                $hinh_anh = $target;
            }
        }

        $data = [
            'lich_id' => $_POST['lich_id'],
            'hdv_id' => $hdv_id,
            'tieu_de' => $_POST['tieu_de'],
            'noi_dung' => $_POST['noi_dung'],
            'loai_nhat_ky' => $_POST['loai_nhat_ky'],
            'hinh_anh' => $hinh_anh
        ];

        $this->model->addDiary($data);
        header("Location: index.php?act=guide-detail&id=" . $_POST['lich_id'] . "#diary");
    }
}
?>