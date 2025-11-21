<?php

class TourController {

    private PDO $db;
    private TourModel $tourModel;

    public function __construct(PDO $db) {
        // Khởi tạo đối tượng db và model
        $this->db = $db;
        $this->tourModel = new TourModel($db);
    }

    // Hàm tải ảnh minh họa lên
    private function uploadImage(?string $oldImage = null): ?string
    {
        if (empty($_FILES['anh_minh_hoa']['name'])) {
            return $oldImage;
        }

        $folder = "uploads/tours/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        if ($oldImage && file_exists($folder . $oldImage)) {
            unlink($folder . $oldImage);
        }

        $ext = pathinfo($_FILES['anh_minh_hoa']['name'], PATHINFO_EXTENSION);
        $newName = "tour_" . time() . "_" . rand(1000,9999) . "." . $ext;

        move_uploaded_file($_FILES['anh_minh_hoa']['tmp_name'], $folder . $newName);

        return $newName;
    }

    // Hiển thị danh sách tour
    public function index(): void {
        $filters = [
            'ten_tour'  => $_GET['ten_tour']  ?? '',
            'dia_diem'  => $_GET['dia_diem']  ?? '',
            'trang_thai'=> $_GET['trang_thai']?? '',
            'ngay_from' => $_GET['ngay_from'] ?? '',
            'ngay_to'   => $_GET['ngay_to']   ?? '',
        ];

        $tours = $this->tourModel->getAll($filters);
        require ROOT . "/views/admin/tour_management.php";
    }

    // Tạo tour mới
    public function create(): void {
        require ROOT . "/views/admin/tour_create.php";
    }

    // Lưu tour mới vào cơ sở dữ liệu
    public function store(): void
    {
        $_POST['anh_minh_hoa'] = $this->uploadImage();
        $this->tourModel->store($_POST);

        header("Location: index.php?controller=tour&action=index");
        exit;
    }

    // Hiển thị form chỉnh sửa tour
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $tour = $this->tourModel->getById($id);

        require ROOT . "/views/admin/tour_edit.php";
    }

    // Cập nhật tour
    public function update(): void
    {
        $id = (int)$_POST['id'];

        $_POST['anh_minh_hoa'] = $this->uploadImage($_POST['anh_cu'] ?? null);

        $this->tourModel->update($id, $_POST);

        header("Location: index.php?controller=tour&action=index");
        exit;
    }

    // Xóa tour
    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        $tour = $this->tourModel->getById($id);

        if ($tour && $tour['anh_minh_hoa']) {
            $path = "uploads/tours/" . $tour['anh_minh_hoa'];
            if (file_exists($path)) unlink($path);
        }

        $this->tourModel->delete($id);

        header("Location: index.php?controller=tour&action=index");
        exit;
    }

    // Xem chi tiết tour
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $tour = $this->tourModel->getById($id);

            if ($tour) {
                require ROOT . "/views/admin/tour_show.php";
            } else {
                echo "Tour không tồn tại!";
            }
        } else {
            echo "ID tour không hợp lệ!";
        }
    }
}
