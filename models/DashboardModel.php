<?php
class DashboardModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Thống kê 4 chỉ số quan trọng (Cards trên cùng)
    public function getKeyMetrics() {
        // Tổng doanh thu (Dựa trên booking đã chốt/thanh toán)
        $sqlThu = "SELECT COALESCE(SUM(tong_tien), 0) FROM booking 
                   WHERE trang_thai IN ('DA_XAC_NHAN', 'DA_THANH_TOAN', 'HOAN_THANH')";
        $doanh_thu = $this->conn->query($sqlThu)->fetchColumn();

        // Tổng chi phí (Dịch vụ + Khác)
        // Lưu ý: Kiểm tra xem bảng chi_phi_khac đã có chưa, nếu chưa có thì bỏ dòng sqlChi2
        $chi_phi = 0;
        try {
            $sqlChi1 = "SELECT COALESCE(SUM(thanh_tien), 0) FROM phan_bo_dich_vu";
            $chi_phi += $this->conn->query($sqlChi1)->fetchColumn();

            $sqlChi2 = "SELECT COALESCE(SUM(so_tien), 0) FROM chi_phi_khac";
            $chi_phi += $this->conn->query($sqlChi2)->fetchColumn();
        } catch (Exception $e) {
            // Bỏ qua lỗi nếu chưa tạo đủ bảng
        }

        // Tổng số tour đang chạy/sắp chạy
        $sqlTour = "SELECT COUNT(*) FROM lich_khoi_hanh WHERE trang_thai != 'HUY'";
        $so_tour = $this->conn->query($sqlTour)->fetchColumn();

        // Tổng số khách phục vụ
        $sqlKhach = "SELECT COALESCE(SUM(so_luong_nguoi_lon + so_luong_tre_em), 0) FROM booking WHERE trang_thai != 'HUY'";
        $so_khach = $this->conn->query($sqlKhach)->fetchColumn();

        return [
            'doanh_thu' => $doanh_thu,
            'chi_phi'   => $chi_phi,
            'loi_nhuan' => $doanh_thu - $chi_phi,
            'so_tour'   => $so_tour,
            'so_khach'  => $so_khach
        ];
    }

    // 2. Lấy dữ liệu biểu đồ tròn (Tỷ lệ trạng thái Booking)
    public function getBookingStatusStats() {
        $sql = "SELECT trang_thai, COUNT(*) as so_luong FROM booking GROUP BY trang_thai";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Lấy danh sách booking mới nhất
    public function getRecentBookings($limit = 10) {
        $sql = "SELECT b.*, t.ten_tour, 
                       COALESCE(b.snapshot_kh_ho_ten, k.ho_ten) as ten_khach 
                FROM booking b 
                LEFT JOIN tour t ON b.tour_id = t.id 
                LEFT JOIN khach_hang k ON b.khach_hang_id = k.id 
                ORDER BY b.ngay_dat DESC LIMIT $limit";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>