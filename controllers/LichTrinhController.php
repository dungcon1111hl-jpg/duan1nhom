<?php
require_once 'models/LichTrinhModel.php';

class LichTrinhController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new LichTrinhModel($db);
    }

    public function store() {
        // Hứng dữ liệu từ form
        $data = [
            'tour_id'       => $_POST['tour_id'],
            'ngay_thu'      => $_POST['ngay_thu'],
            'tieu_de'       => $_POST['tieu_de'],
            'dia_diem'      => $_POST['dia_diem'] ?? '',
            'gio_bat_dau'   => $_POST['gio_bat_dau'] ?? null,
            'gio_ket_thuc'  => $_POST['gio_ket_thuc'] ?? null,
            'noi_dung'      => $_POST['noi_dung'] ?? '',
            'ghi_chu'       => $_POST['ghi_chu'] ?? ''
        ];

        $this->model->insert($data);

        // Redirect về trang sửa tour (Tab lịch trình)
        if (isset($_POST['redirect_to']) && $_POST['redirect_to'] == 'tour-edit') {
            header("Location: index.php?act=tour-edit&id=" . $data['tour_id'] . "#schedule");
        } else {
            header("Location: index.php?act=tours");
        }
        exit;
    }

    public function delete() {
        $id = $_GET['id'];
        $tour_id = $_GET['tour_id'];
        $this->model->delete($id);
        header("Location: index.php?act=tour-edit&id=" . $tour_id . "#schedule");
        exit;
    }
}
?>