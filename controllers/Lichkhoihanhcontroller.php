<?php
require_once 'models/LichKhoiHanhModel.php';
require_once 'models/PhanCongNhanSuModel.php';
require_once 'models/BaoCaoModel.php';

class OperationController
{
    private $lichModel;
    private $phanCongModel;
    private $baoCaoModel;

    public function __construct()
    {
        $this->lichModel     = new LichKhoiHanhModel();
        $this->phanCongModel = new PhanCongNhanSuModel();
        $this->baoCaoModel   = new BaoCaoModel();
    }

    // 3.1 – List lịch khởi hành
    public function indexLich()
    {
        $lichList = $this->lichModel->getAll();
        require 'views/lich_khoi_hanh/index.php';
    }

    // Form tạo mới
    public function createLich()
    {
        // Có thể cần list tour để chọn
        require_once 'models/TourModel.php';
        $tourModel = new TourModel();
        $tours = $tourModel->getAll();
        require 'views/lich_khoi_hanh/create.php';
    }

    // Lưu lịch mới
    public function storeLich()
    {
        $data = [
            'tour_id'        => $_POST['tour_id'],
            'ngay_bat_dau'   => $_POST['ngay_bat_dau'],
            'ngay_ket_thuc'  => $_POST['ngay_ket_thuc'],
            'diem_tap_trung' => $_POST['diem_tap_trung'] ?? null,
            'so_cho_toi_da'  => $_POST['so_cho_toi_da'] ?? null,
            'so_cho_da_dat'  => 0,
            'ghi_chu'        => $_POST['ghi_chu'] ?? null,
        ];
        $this->lichModel->create($data);
        header('Location: index.php?act=lich_khoi_hanh');
        exit;
    }

    // Form edit
    public function editLich()
    {
        $id = $_GET['id'];
        $lich = $this->lichModel->find($id);

        require_once 'models/TourModel.php';
        $tourModel = new TourModel();
        $tours = $tourModel->getAll();

        require 'views/lich_khoi_hanh/edit.php';
    }

    // Cập nhật
    public function updateLich()
    {
        $id = $_POST['id'];
        $data = [
            'tour_id'        => $_POST['tour_id'],
            'ngay_bat_dau'   => $_POST['ngay_bat_dau'],
            'ngay_ket_thuc'  => $_POST['ngay_ket_thuc'],
            'diem_tap_trung' => $_POST['diem_tap_trung'] ?? null,
            'so_cho_toi_da'  => $_POST['so_cho_toi_da'] ?? null,
            'so_cho_da_dat'  => $_POST['so_cho_da_dat'] ?? 0,
            'ghi_chu'        => $_POST['ghi_chu'] ?? null,
        ];
        $this->lichModel->updateById($id, $data);
        header('Location: index.php?act=lich_khoi_hanh');
        exit;
    }

    // Xóa
    public function deleteLich()
    {
        $id = $_GET['id'];
        $this->lichModel->deleteById($id);
        header('Location: index.php?act=lich_khoi_hanh');
        exit;
    }

    // 3.2 – Quản lý phân công HDV cho 1 lịch
    public function phanCong()
    {
        $lichId = $_GET['lich_id'];
        $lich   = $this->lichModel->find($lichId);

        // list phân công hiện tại
        $phanCongList = $this->phanCongModel->getByLich($lichId);

        // list hướng dẫn viên để chọn
        require_once 'models/HuongDanVienModel.php';
        $hdvModel = new HuongDanVienModel();
        $dsHDV = $hdvModel->getAll();

        require 'views/phan_cong_nhan_su/index.php';
    }

    public function storePhanCong()
    {
        $data = [
            'lich_id' => $_POST['lich_id'],
            'hdv_id'  => $_POST['hdv_id'],
            'ghi_chu' => $_POST['ghi_chu'] ?? null,
        ];
        $this->phanCongModel->create($data);
        header('Location: index.php?act=phan_cong&lich_id=' . $data['lich_id']);
        exit;
    }

    public function deletePhanCong()
    {
        $id     = $_GET['id'];
        $lichId = $_GET['lich_id'];
        $this->phanCongModel->deleteById($id);
        header('Location: index.php?act=phan_cong&lich_id=' . $lichId);
        exit;
    }

    // 3.4 – Báo cáo doanh thu 1 lịch
    public function baoCaoLich()
    {
        $lichId = $_GET['lich_id'];
        $lich   = $this->lichModel->find($lichId);
        $baoCao = $this->baoCaoModel->getDoanhThuTheoLich($lichId);

        require 'views/bao_cao/lich.php';
    }
}
