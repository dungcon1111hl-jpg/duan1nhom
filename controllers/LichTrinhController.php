<?php

class LichTrinhController {

    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new LichTrinhModel($db);
    }

    // DANH SÁCH
    public function index() {
        $tour_id = $_GET['tour_id'] ?? 0;
        if ($tour_id == 0) die("Thiếu tour_id!");

        $data = $this->model->getByTour($tour_id);
        require ROOT . "/views/lichtrinh/list.php";
    }

    // XEM CHI TIẾT
    public function detail() {
        $row = $this->model->getOne($_GET['id']);
        require ROOT . "/views/lichtrinh/detail.php";
    }

    // FORM THÊM
    public function add() {
        $tour_id = $_GET['tour_id'];
        require ROOT . "/views/lichtrinh/add.php";
    }

    // LƯU THÊM
    public function store() {
        $this->model->insert(
            $_POST['tour_id'],
            $_POST['thu_tu_ngay'],
            $_POST['tieu_de'],
            $_POST['hoat_dong'],
            $_POST['gio_bat_dau'],
            $_POST['gio_ket_thuc'],
            $_POST['ghi_chu']
        );

        header("Location: index.php?controller=lichtrinh&action=index&tour_id=" . $_POST['tour_id']);
    }

    // FORM SỬA
    public function edit() {
        $row = $this->model->getOne($_GET['id']);
        require ROOT . "/views/lichtrinh/edit.php";
    }

    // LƯU SỬA
    public function update() {
        $this->model->update(
            $_POST['id'],
            $_POST['tour_id'],
            $_POST['thu_tu_ngay'],
            $_POST['tieu_de'],
            $_POST['hoat_dong'],
            $_POST['gio_bat_dau'],
            $_POST['gio_ket_thuc'],
            $_POST['ghi_chu']
        );

        header("Location: index.php?controller=lichtrinh&action=index&tour_id=" . $_POST['tour_id']);
    }

    // XÓA
    public function delete() {
        $this->model->delete($_GET['id']);

        header("Location: index.php?controller=lichtrinh&action=index&tour_id=" . $_GET['tour_id']);
    }
}
