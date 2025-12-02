<?php
class BaoCaoModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tính toán doanh thu, chi phí, lợi nhuận của 1 lịch khởi hành
    public function getDoanhThuTheoLich($lichId) {
        // 1. Tổng Doanh Thu (Từ bảng Booking)
        // Chỉ tính các đơn đã xác nhận, đã thanh toán hoặc hoàn thành
        $sqlThu = "SELECT 
                        COUNT(id) as tong_so_don,
                        SUM(so_luong_khach) as tong_khach,
                        SUM(tong_tien) as doanh_thu 
                   FROM booking 
                   WHERE lich_id = :id 
                   AND trang_thai IN ('DA_XAC_NHAN', 'DA_THANH_TOAN', 'HOAN_THANH')";
        
        $stmtThu = $this->conn->prepare($sqlThu);
        $stmtThu->execute([':id' => $lichId]);
        $thu = $stmtThu->fetch(PDO::FETCH_ASSOC);

        // 2. Tổng Chi Phí (Từ bảng Chi Phí Lịch)
        $chi_phi = 0;
        try {
            // Kiểm tra xem bảng chi_phi_lich có tồn tại không trước khi query (để tránh lỗi nếu chưa tạo bảng)
            $checkTable = $this->conn->query("SHOW TABLES LIKE 'chi_phi_lich'")->rowCount();
            
            if ($checkTable > 0) {
                $sqlChi = "SELECT SUM(chi_phi_thuc_te) as tong_chi 
                           FROM chi_phi_lich 
                           WHERE lich_id = :id";
                $stmtChi = $this->conn->prepare($sqlChi);
                $stmtChi->execute([':id' => $lichId]);
                $resultChi = $stmtChi->fetch(PDO::FETCH_ASSOC);
                $chi_phi = $resultChi['tong_chi'] ?? 0;
            }
        } catch (Exception $e) {
            $chi_phi = 0; 
        }

        return [
            'so_don'    => $thu['tong_so_don'] ?? 0,
            'so_khach'  => $thu['tong_khach'] ?? 0,
            'doanh_thu' => $thu['doanh_thu'] ?? 0,
            'chi_phi'   => $chi_phi,
            'loi_nhuan' => ($thu['doanh_thu'] ?? 0) - $chi_phi
        ];
    }
}
?>