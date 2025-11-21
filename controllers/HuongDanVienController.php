<?php

class HuongDanVienController {

    private $model;

    public function __construct($db) {
        $this->model = new HuongDanVienModel($db);
    }

    public function index() {
        $data = $this->model->all();
        include ROOT . "/views/huongdanvien/list.php";
    }

    public function create() {
        include ROOT . "/views/huongdanvien/add.php";
    }
public function profile() {
    $id = $_GET['id'] ?? null;
    if (!$id) die("Thiếu ID!");

    $hdv = $this->model->getOne($id);

    if (!$hdv) die("HDV không tồn tại!");

    include ROOT . "/views/huongdanvien/profile.php";
}


    public function store() {

        // xử lý ảnh
        $file = $_FILES['anh_dai_dien'] ?? null;
        $anh = "";

        if ($file && $file['size'] > 0) {
            $target = "uploads/hdv/" . time() . "_" . $file['name'];
            move_uploaded_file($file['tmp_name'], $target);
            $anh = $target;
        }

        $data = [
            ':ho_ten' => $_POST['ho_ten'],
            ':ngay_sinh' => $_POST['ngay_sinh'],
            ':so_dien_thoai' => $_POST['so_dien_thoai'],
            ':email' => $_POST['email'],
            ':ngon_ngu' => implode(",", $_POST['ngon_ngu']), // nhiều ngôn ngữ
            ':chung_chi' => $_POST['chung_chi'],
            ':kinh_nghiem' => $_POST['kinh_nghiem'],
            ':suc_khoe' => $_POST['suc_khoe'],
            ':anh_dai_dien' => $anh,
            ':trang_thai' => $_POST['trang_thai']
        ];

        $this->model->insert($data);
        header("Location: index.php?controller=huongdanvien&action=index");
    }

    public function edit() {
        $id = $_GET['id'];
        $hdv = $this->model->getOne($id);

        include ROOT . "/views/huongdanvien/edit.php";
    }

    public function update() {
        $id = $_POST['id'];

        // Update ảnh
        $file = $_FILES['anh_dai_dien'];
        $anh = $_POST['anh_cu'];

        if ($file['size'] > 0) {
            $target = "uploads/hdv/" . time() . "_" . $file['name'];
            move_uploaded_file($file['tmp_name'], $target);
            $anh = $target;
        }

        $data = [
            ':ho_ten' => $_POST['ho_ten'],
            ':ngay_sinh' => $_POST['ngay_sinh'],
            ':so_dien_thoai' => $_POST['so_dien_thoai'],
            ':email' => $_POST['email'],
            ':ngon_ngu' => implode(",", $_POST['ngon_ngu']),
            ':chung_chi' => $_POST['chung_chi'],
            ':kinh_nghiem' => $_POST['kinh_nghiem'],
            ':suc_khoe' => $_POST['suc_khoe'],
            ':anh_dai_dien' => $anh,
            ':trang_thai' => $_POST['trang_thai']
        ];

        $this->model->update($id, $data);
        header("Location: index.php?controller=huongdanvien&action=index");
    }

    public function delete() {
        $id = $_GET['id'];
        $this->model->delete($id);
        header("Location: index.php?controller=huongdanvien&action=index");
    }

}
