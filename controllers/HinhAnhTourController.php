<?php

class HinhAnhTourController {

    private PDO $db;
    private HinhAnhTourModel $model;
    private TourModel $tourModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->model = new HinhAnhTourModel($db);
        $this->tourModel = new TourModel($db);
    }

    // 1. Chọn tour trước khi quản lý hình ảnh
    public function select_tour() {
        $tours = $this->tourModel->getAll([]); // nếu hàm getAll của bạn khác thì chỉnh lại
        include ROOT . "/views/hinhanhtour/select_tour.php";
    }

    // 2. Danh sách ảnh của 1 tour
    public function index() {
        $tour_id = $_GET['tour_id'] ?? null;
        if (!$tour_id) {
            die("Thiếu tour_id");
        }

        $tour = $this->tourModel->getOne($tour_id);
        if (!$tour) {
            die("Tour không tồn tại");
        }

        $data = $this->model->getByTour((int)$tour_id);
        $tour_id = (int)$tour_id;

        include ROOT . "/views/hinhanhtour/index.php";
    }

    // 3. Form thêm ảnh
    public function add() {
        $tour_id = $_GET['tour_id'] ?? null;
        if (!$tour_id) {
            die("Thiếu tour_id");
        }

        $tour = $this->tourModel->getOne($tour_id);
        if (!$tour) {
            die("Tour không tồn tại");
        }

        include ROOT . "/views/hinhanhtour/add.php";
    }

    // 4. Lưu ảnh upload
    public function store() {
        $tour_id = $_POST['tour_id'] ?? null;
        $mo_ta_anh = $_POST['mo_ta_anh'] ?? '';
        $thu_tu_hien_thi = !empty($_POST['thu_tu_hien_thi']) ? (int)$_POST['thu_tu_hien_thi'] : null;

        if (!$tour_id) {
            die("Thiếu tour_id");
        }

        if (empty($_FILES['image']['name'])) {
            die("Vui lòng chọn ảnh");
        }

        $uploadDir = ROOT . "/uploads/tour_images/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $file = $_FILES['image'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        $targetPath = $uploadDir . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            die("Upload file thất bại");
        }

        // Đường dẫn lưu vào DB (tương đối, để dùng trong <img src="">)
        $duong_dan_db = "uploads/tour_images/" . $safeName;

        $this->model->insert((int)$tour_id, $duong_dan_db, $mo_ta_anh, $thu_tu_hien_thi);

        header("Location: index.php?controller=hinhanhtour&action=index&tour_id=" . $tour_id);
        exit;
    }

    // 5. Form sửa ảnh
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die("Thiếu id");
        }

        $row = $this->model->getOne((int)$id);
        if (!$row) {
            die("Ảnh không tồn tại");
        }

        $tour_id = $row['tour_id'];
        include ROOT . "/views/hinhanhtour/edit.php";
    }

    // 6. Cập nhật ảnh
    public function update() {
        $id = $_POST['id'] ?? null;
        $tour_id = $_POST['tour_id'] ?? null;
        $mo_ta_anh = $_POST['mo_ta_anh'] ?? '';
        $thu_tu_hien_thi = (int)($_POST['thu_tu_hien_thi'] ?? 1);

        if (!$id || !$tour_id) {
            die("Thiếu dữ liệu");
        }

        $rowOld = $this->model->getOne((int)$id);
        if (!$rowOld) {
            die("Ảnh không tồn tại");
        }

        $duong_dan_moi = null;

        // Nếu có upload ảnh mới
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = ROOT . "/uploads/tour_images/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $file = $_FILES['image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safeName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

            $targetPath = $uploadDir . $safeName;

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                die("Upload file mới thất bại");
            }

            // Xóa file cũ nếu tồn tại
            $oldPath = ROOT . '/' . $rowOld['duong_dan'];
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }

            $duong_dan_moi = "uploads/tour_images/" . $safeName;
        }

        $this->model->update((int)$id, $mo_ta_anh, $thu_tu_hien_thi, $duong_dan_moi);

        header("Location: index.php?controller=hinhanhtour&action=index&tour_id=" . $tour_id);
        exit;
    }

    // 7. Xóa ảnh
    public function delete() {
        $id = $_GET['id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? null;

        if (!$id || !$tour_id) {
            die("Thiếu dữ liệu");
        }

        $row = $this->model->getOne((int)$id);
        if ($row) {
            $filePath = ROOT . '/' . $row['duong_dan'];
            if (is_file($filePath)) {
                @unlink($filePath);
            }
            $this->model->delete((int)$id);
        }

        header("Location: index.php?controller=hinhanhtour&action=index&tour_id=" . $tour_id);
        exit;
    }
}
