<?php
// Thêm lệnh require_once để nạp định nghĩa lớp PhanPhongModel
    require_once __DIR__ . "/../models/PhanPhongModel.php"; 

class PhanPhongController {

    private $db;
    private $model;

    public function __construct() {
        // Giả định connectDB() và PhanPhongModel được định nghĩa/autoload đúng
        $this->db = connectDB(); 
        $this->model = new PhanPhongModel($this->db);
    }

    public function create() {
        $lich_id = $_GET['lich_id'] ?? 0;

        // Đảm bảo tên biến là $list_khach để khớp với view
        $list_khach = $this->model->getKhachByLich($lich_id); 

        require ROOT . "/views/admin/phan_phong_create.php";
    }

    public function store() {

        if (empty($_POST['lich_id'] || empty($_POST['khach_id']))) {
            $_SESSION['flash_error'] = "Thiếu thông tin lịch trình hoặc khách hàng.";
            header("Location: index.php?act=phan-phong-list&lich_id=" . $_POST['lich_id']);
            exit;
        }

        // Lấy tên khách hàng từ bảng diem_danh_khach
        // Dùng khach_id và lich_id để truy vấn bản ghi điểm danh gần nhất
        $stmt = $this->db->prepare("SELECT ten_khach FROM diem_danh_khach WHERE khach_id = :id AND lich_id = :lich_id ORDER BY thoi_gian DESC LIMIT 1");
        $stmt->execute(['id' => $_POST['khach_id'], 'lich_id' => $_POST['lich_id']]);
        $ten = $stmt->fetch(PDO::FETCH_ASSOC);

        // Gán tên khách đã tìm được vào mảng POST
        $_POST['ten_khach'] = $ten['ten_khach'] ?? ('Khách ID: ' . $_POST['khach_id']);

        // Gọi Model để lưu dữ liệu
        if ($this->model->store($_POST)) {
            $_SESSION['flash_success'] = "Phân phòng thành công!";
        } else {
            $_SESSION['flash_error'] = "Lỗi khi lưu phân phòng! Có thể khách đã được phân phòng rồi.";
        }

        header("Location: index.php?act=phan-phong-list&lich_id=" . $_POST['lich_id']);
        exit;
    }

    public function list() {
        $lich_id = $_GET['lich_id'] ?? 0;
        $list = $this->model->getList($lich_id);

        require ROOT . "/views/admin/phan_phong_list.php";
    }
}
?>