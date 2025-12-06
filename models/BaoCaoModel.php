<?php
class BaoCaoModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy thống kê tổng hợp theo khoảng thời gian
    public function getThongKeTongHop($fromDate, $toDate) {
        // SQL này sẽ tính tổng Doanh thu, Chi phí DV, Chi phí Khác cho từng Lịch khởi hành
        $sql = "SELECT 
                    l.id as lich_id,
                    l.ngay_khoi_hanh,
                    l.ngay_ket_thuc,
                    t.ten_tour,
                    t.ma_tour,
                    
                    -- 1. Tính Tổng Doanh Thu (Booking đã xác nhận/thanh toán)
                    (SELECT COALESCE(SUM(tong_tien), 0) 
                     FROM booking 
                     WHERE lich_id = l.id 
                     AND trang_thai IN ('DA_XAC_NHAN', 'DA_THANH_TOAN', 'HOAN_THANH')) as tong_doanh_thu,

                    -- 2. Tính Chi phí Dịch vụ (Trả NCC)
                    (SELECT COALESCE(SUM(thanh_tien), 0) 
                     FROM phan_bo_dich_vu 
                     WHERE lich_id = l.id) as tong_chi_phi_ncc,

                    -- 3. Tính Chi phí Phát sinh (Cầu đường, ăn uống...)
                    (SELECT COALESCE(SUM(so_tien), 0) 
                     FROM chi_phi_khac 
                     WHERE lich_id = l.id) as tong_chi_phi_khac

                FROM lich_khoi_hanh l
                JOIN tour t ON l.tour_id = t.id
                WHERE l.ngay_khoi_hanh BETWEEN :tu_ngay AND :den_ngay
                ORDER BY l.ngay_khoi_hanh DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':tu_ngay' => $fromDate,
            ':den_ngay' => $toDate
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>