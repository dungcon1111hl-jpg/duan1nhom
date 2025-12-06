<?php
// Kiểm tra xem class đã tồn tại chưa trước khi khai báo
if (!class_exists('BookingController')) {

    class BookingController {
        private $db;
        private $model;
        private $tourModel;
        private $khachModel;
        private $khachThamGiaModel;

        public function __construct($db) {
            $this->db = $db;
            
            // Khởi tạo các Model cần thiết
            // (Đảm bảo các file model này đã được nạp ở index.php)
            $this->model = new BookingModel($db);
            $this->tourModel = new TourModel($db);
            $this->khachModel = new KhachHangModel($db);
            
            // Kiểm tra model phụ nếu có
            if(class_exists('KhachThamGiaModel')) {
                $this->khachThamGiaModel = new KhachThamGiaModel($db);
            }
        }

        // 1. Danh sách
        public function index() {
            $bookings = $this->model->getAll();
            require ROOT . "/views/admin/booking/index.php";
        }

        // 2. Chi tiết
        public function detail() {
            $id = $_GET['id'] ?? 0;
            $booking = $this->model->getOne($id);
            if (!$booking) die("Đơn hàng không tồn tại");

            $list_khach = [];
            if($this->khachThamGiaModel) {
                $list_khach = $this->khachThamGiaModel->getByBookingId($id);
            }
            
            require ROOT . "/views/admin/booking/detail.php";
        }

        // 3. Form Tạo mới
        public function create() {
            $khachhangs = $this->khachModel->getAll(); 
            $tours = $this->tourModel->getAll(); 
            require ROOT . "/views/admin/booking/create.php";
        }

        // 4. Lưu mới (Store)
        public function store() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // 1. Lấy thông tin Tour từ DB
                $tourId = $_POST['tour_id'];
                $tourInfo = $this->tourModel->getById($tourId);
                $giaTour = $tourInfo['gia_tour'] ?? 0;

                // 2. Xử lý Snapshot Tên Tour (Tránh lỗi NULL)
                $tenTourSnapshot = $_POST['snapshot_ten_tour'] ?? '';
                if (empty($tenTourSnapshot) && !empty($tourInfo)) {
                    $tenTourSnapshot = $tourInfo['ten_tour'];
                }

                // 3. Xử lý số lượng khách
                $passengers = $_POST['passengers'] ?? [];
                $soLuongKhach = count($passengers);
                if ($soLuongKhach == 0) {
                    $soLuongKhach = (int)($_POST['so_luong_khach'] ?? 1);
                }

                // 4. Tính tiền
                $tongTien = $giaTour * $soLuongKhach;

                // 5. Chuẩn bị dữ liệu
                $bookingData = [
                    'tour_id'           => $tourId,
                    'khach_hang_id'     => $_POST['khach_hang_id'],
                    'so_luong_khach'    => $soLuongKhach,
                    'tong_tien'         => $tongTien,
                    'ghi_chu'           => $_POST['ghi_chu'] ?? '',
                    'trang_thai'        => $_POST['trang_thai'] ?? 'CHO_XU_LY',
                    // Xóa dấu chấm/phẩy ở tiền cọc để lưu số
                    'da_thanh_toan'     => isset($_POST['da_thanh_toan']) ? str_replace(['.', ','], '', $_POST['da_thanh_toan']) : 0,

                    // Snapshot Khách
                    'ten_khach'         => $_POST['snapshot_kh_ho_ten'] ?? '',
                    'email_khach'       => $_POST['snapshot_kh_email'] ?? '',
                    'sdt_khach'         => $_POST['snapshot_kh_so_dien_thoai'] ?? '',
                    'dia_chi_khach'     => $_POST['snapshot_kh_dia_chi'] ?? '',
                    
                    // Snapshot Tour
                    'ten_tour_snapshot' => $tenTourSnapshot 
                ];

                // 6. Insert Booking
                $bookingId = $this->model->insert($bookingData);

                // 7. Lưu khách đi cùng (Nếu có)
                if ($bookingId && !empty($passengers) && $this->khachThamGiaModel) {
                    foreach ($passengers as $p) {
                        if (!empty($p['ho_ten'])) {
                            $this->khachThamGiaModel->insert([
                                'booking_id' => $bookingId,
                                'ho_ten'     => $p['ho_ten'],
                                'gioi_tinh'  => $p['gioi_tinh'],
                                'ngay_sinh'  => $p['ngay_sinh'],
                                'so_giay_to' => $p['so_giay_to'],
                                'ghi_chu'    => $p['ghi_chu']
                            ]);
                        }
                    }
                }

                // 8. Chuyển hướng
                header("Location: index.php?act=booking-detail&id=" . $bookingId);
                exit;
            }
        }

        // 5. Form Sửa
        public function edit() {
            $id = $_GET['id'] ?? 0;
            $booking = $this->model->getOne($id);
            if (!$booking) die("Booking không tồn tại");

            $khachhangs = $this->khachModel->getAll();
            $tours = $this->tourModel->getAll();

            require ROOT . "/views/admin/booking/edit.php";
        }

        // 6. Cập nhật
        public function update() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST['id'];
                
                // Tính lại tiền nếu đổi tour hoặc số lượng
                $tour = $this->tourModel->getById($_POST['tour_id']);
                $giaTour = $tour['gia_tour'] ?? 0;
                
                // Ưu tiên lấy tổng tiền nhập tay, nếu không thì tự tính
                $tongTien = !empty($_POST['tong_tien']) ? $_POST['tong_tien'] : ($giaTour * (int)$_POST['so_luong_khach']);

                $data = [
                    'id'                => $id,
                    'tour_id'           => $_POST['tour_id'],
                    'khach_hang_id'     => $_POST['khach_hang_id'],
                    'so_luong_khach'    => $_POST['so_luong_khach'],
                    'tong_tien'         => $tongTien,
                    'trang_thai'        => $_POST['trang_thai'],
                    'ghi_chu'           => $_POST['ghi_chu'] ?? ''
                ];

                $this->model->update($id, $data);
                header("Location: index.php?act=booking-detail&id=" . $id);
                exit;
            }
        }

        // 7. Xóa
        public function delete() {
            $id = $_GET['id'];
            $this->model->delete($id);
            header("Location: index.php?act=bookings");
            exit;
        }
        
        // 8. Cập nhật trạng thái nhanh (nếu cần)
        public function updateStatus() {
             // Logic cập nhật nhanh
        }
    }
}
?>