<?php

class PhanPhongController extends BaseController
{
    private $phanPhongModel;
    private $khachModel;
    private $lichModel;

    public function __construct()
    {
        $this->phanPhongModel = new PhanPhongModel();
        $this->khachModel = new KhachHangModel();
        $this->lichModel = new LichModel();
    }

    public function create()
    {
        $khach = $this->khachModel->getAll();
        $lich = $this->lichModel->getAll();

        return $this->render("phan_phong/create", [
            "khach" => $khach,
            "lich" => $lich
        ]);
    }

    public function store()
    {
        $data = [
            "lich_id" => $_POST['lich_id'],
            "khach_id" => $_POST['khach_id'],
            "loai_phong" => $_POST['loai_phong'],
            "so_nguoi" => $_POST['so_nguoi'],
            "so_phong" => $_POST['so_phong'],
            "ghi_chu" => $_POST['ghi_chu']
        ];

        $this->phanPhongModel->create($data);

        $_SESSION['flash_success'] = "Phân phòng thành công!";
        header("Location: " . BASE_URL . "?act=phan-phong-list");
        exit;
    }

    public function list()
    {
        $rows = $this->phanPhongModel->getAll();
        return $this->render("phan_phong/list", ["data" => $rows]);
    }
}
