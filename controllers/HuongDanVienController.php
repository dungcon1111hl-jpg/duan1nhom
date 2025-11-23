<?php
require_once 'models/HuongDanVienModel.php';

class HuongDanVienController {
    private $db;
    private $hdvModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->hdvModel = new HuongDanVienModel($db);
    }

    // 1. Hiển thị danh sách
    public function index(): void {
        // Lấy dữ liệu từ model
        $hdvs = $this->hdvModel->getAll(); 
        
        // [FIX] Gọi đúng file view list.php trong thư mục huongdanvien
        require ROOT . "/views/admin/huongdanvien/list.php";
    }

    // 2. Hiển thị form thêm mới
    public function create(): void {
        // [FIX] Gọi đúng file view create.php
        require ROOT . "/views/admin/huongdanvien/create.php";
    }

    // 3. Lưu dữ liệu
    public function store(): void {
        // Xử lý upload ảnh nếu có (bạn có thể copy hàm uploadImage từ TourController sang đây nếu cần)
        // ...

        $this->hdvModel->insert($_POST);
        
        // [FIX] Redirect về đúng act=hdv-list
        header("Location: index.php?act=hdv-list");
        exit;
    }

    // 4. Hiển thị form sửa
    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $hdv = $this->hdvModel->getOne($id);

        // [FIX] Gọi đúng file view edit.php
        require ROOT . "/views/admin/huongdanvien/edit.php";
    }

    // 5. Cập nhật dữ liệu
    public function update(): void {
        $id = (int)$_POST['id'];
        $this->hdvModel->update($id, $_POST);

        // [FIX] Redirect về đúng act=hdv-list
        header("Location: index.php?act=hdv-list");
        exit;
    }

    // 6. Xóa
    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->hdvModel->delete($id);

        // [FIX] Redirect về đúng act=hdv-list
        header("Location: index.php?act=hdv-list");
        exit;
    }
}
?>