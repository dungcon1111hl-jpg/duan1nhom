<?php
require_once 'models/GuideModel.php';
require_once 'models/NhatKyModel.php';

class GuideController {
    private $model;
    private $nhatKyModel;

    public function __construct($db) {
        // Middleware: Chỉ cho phép HDV truy cập
        if (!isset($_SESSION['user_admin']) || $_SESSION['user_admin']['role'] !== 'guide') {
            header("Location: index.php?act=login");
            exit;
        }
        $this->model = new GuideModel($db);
        $this->nhatKyModel = new NhatKyModel($db);
    }

    // Trang Dashboard: Danh sách Tour
    public function index() {
        $user_id = $_SESSION['user_admin']['id'];
        $myTours = $this->model->getMyTours($user_id);
        require ROOT . "/views/guide/dashboard.php";
    }

    // Trang Chi tiết Tour (Tabbed Interface)
    public function detail() {
        $lich_id = $_GET['id'];
        $tour = $this->model->getTourDetail($lich_id);
        
        if (!$tour) die("Không tìm thấy thông tin tour.");

        $passengers = $this->model->getPassengers($lich_id);
        $diaries = $this->nhatKyModel->getAllByTourId($lich_id); // Tái sử dụng model Nhật ký
        
        require ROOT . "/views/guide/tour_detail.php";
    }

    // API: Check-in GPS (Nhận JSON từ Client)
    public function apiCheckIn() {
        header('Content-Type: application/json');
        
        // Lấy dữ liệu raw JSON
        $input = json_decode(file_get_contents('php://input'), true);
        
        if(isset($input['passenger_id'])) {
            $this->model->updatePassengerStatus(
                $input['passenger_id'], 
                $input['status'], 
                $input['lat'] ?? null, 
                $input['long'] ?? null
            );
            echo json_encode(['success' => true, 'msg' => 'Check-in thành công']);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Thiếu dữ liệu']);
        }
        exit;
    }

    // Cập nhật ghi chú đặc biệt
    public function updateNote() {
        $this->model->updateSpecialRequest($_POST['passenger_id'], $_POST['note']);
        header("Location: index.php?act=guide-detail&id=" . $_POST['lich_id'] . "#guests");
    }

    // Lưu nhật ký tour (Có upload ảnh)
    public function storeDiary() {
        $user_id = $_SESSION['user_admin']['id'];
        $hdv_id = $this->model->getHdvIdByUserId($user_id);
        
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
            'loai_ghi_chep' => $_POST['loai_nhat_ky'],
            'tieu_de' => $_POST['tieu_de'],
            'noi_dung' => $_POST['noi_dung'],
            'hinh_anh' => $hinh_anh
        ];

        $this->nhatKyModel->store($data);
        header("Location: index.php?act=guide-detail&id=" . $_POST['lich_id'] . "#diary");
    }
}
?>