<?php
// Nạp model Dashboard
require_once 'models/DashboardModel.php';

class AdminController {
    private $db;
    private $dashboardModel;

    public function __construct() {
        // Kết nối DB (hàm connectDB từ commons/function.php)
        $this->db = connectDB();
        $this->dashboardModel = new DashboardModel($this->db);
    }

    public function Home() {
        // Lấy dữ liệu thống kê
        $metrics = $this->dashboardModel->getKeyMetrics();
        $bookingStats = $this->dashboardModel->getBookingStatusStats();
        $recentBookings = $this->dashboardModel->getRecentBookings(5); // Lấy 5 đơn mới nhất

        // Chuẩn bị dữ liệu cho biểu đồ Pie (ChartJS)
        $pieLabels = [];
        $pieData = [];
        $pieColors = [];

        // Map màu sắc cho đẹp
        $colorMap = [
            'CHO_XU_LY' => '#ffc107',    // Vàng
            'DA_XAC_NHAN' => '#17a2b8',  // Xanh dương nhạt
            'DA_THANH_TOAN' => '#007bff',// Xanh dương đậm
            'HOAN_THANH' => '#28a745',   // Xanh lá
            'HUY' => '#dc3545'           // Đỏ
        ];

        foreach ($bookingStats as $stat) {
            $pieLabels[] = $stat['trang_thai'];
            $pieData[] = $stat['so_luong'];
            $pieColors[] = $colorMap[$stat['trang_thai']] ?? '#6c757d';
        }

        // Gọi View
        require_once './views/index.php';
    }
}
?>