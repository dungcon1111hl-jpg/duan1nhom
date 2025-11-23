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

    // Danh sách ảnh của 1 tour (Giao diện chính)
    public function index() {
        $tour_id = $_GET['tour_id'] ?? null;
        if (!$tour_id) {
            header("Location: index.php?act=tours");
            exit;
        }

        $tour = $this->tourModel->getById($tour_id); // Dùng getById thay vì getOne cho chuẩn Model
        if (!$tour) {
            die("Tour không tồn tại");
        }

        $list_anh = $this->model->getByTour((int)$tour_id);
        
        // Đường dẫn tới view đã sửa lại
        require_once PATH_ROOT . "/views/admin/hinhanhtour/index.php";
    }

    // Xử lý upload (Hỗ trợ nhiều ảnh files[])
    public function store() {
        $tour_id = $_POST['tour_id'] ?? null;
        
        if (!$tour_id) {
            header("Location: index.php?act=tours");
            exit;
        }

        // Kiểm tra folder upload
        $uploadDir = "uploads/tour_images/";
        if (!is_dir(PATH_ROOT . $uploadDir)) {
            mkdir(PATH_ROOT . $uploadDir, 0777, true);
        }

        // Xử lý upload nhiều file (name="files[]")
        if (isset($_FILES['files']['name']) && is_array($_FILES['files']['name'])) {
            $files = $_FILES['files'];
            $count = count($files['name']);

            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] == 0) {
                    $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                    $safeName = time() . '_' . uniqid() . '.' . $ext;
                    $targetPath = PATH_ROOT . $uploadDir . $safeName;
                    
                    if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                        // Lưu đường dẫn tương đối vào DB
                        $dbPath = $uploadDir . $safeName;
                        $this->model->insert((int)$tour_id, $dbPath);
                    }
                }
            }
        }
        // Fallback: Xử lý upload 1 file (name="image") nếu dùng form cũ
        elseif (isset($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
            $file = $_FILES['image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safeName = time() . '_' . uniqid() . '.' . $ext;
            $targetPath = PATH_ROOT . $uploadDir . $safeName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $dbPath = $uploadDir . $safeName;
                $this->model->insert((int)$tour_id, $dbPath);
            }
        }

        // Redirect về trang quản lý ảnh
        header("Location: index.php?act=tour-images&tour_id=" . $tour_id);
        exit;
    }

    // Xóa ảnh
    public function delete() {
        $id = $_GET['id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? null;

        if ($id && $tour_id) {
            $row = $this->model->getOne((int)$id);
            if ($row) {
                // Xóa file vật lý
                $filePath = PATH_ROOT . $row['duong_dan'];
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
                // Xóa trong DB
                $this->model->delete((int)$id);
            }
        }

        header("Location: index.php?act=tour-images&tour_id=" . $tour_id);
        exit;
    }
}