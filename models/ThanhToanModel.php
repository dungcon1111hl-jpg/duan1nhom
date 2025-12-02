<?php
class ThanhToanModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // [CẬP NHẬT] Lấy danh sách thanh toán + Tên khách hàng
    public function getByBooking($booking_id) {
        $sql = "SELECT tt.*, 
                       u.full_name as ten_nhan_vien,
                       kh.ho_ten as ten_khach_hang 
                FROM thanh_toan tt
                LEFT JOIN users u ON tt.nhan_vien_id = u.id
                LEFT JOIN booking b ON tt.booking_id = b.id
                LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.id
                WHERE tt.booking_id = :bid 
                ORDER BY tt.ngay_thanh_toan DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':bid' => $booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // [MỚI] Lấy 1 phiếu thu để sửa
    public function getOne($id) {
        $sql = "SELECT * FROM thanh_toan WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới (Giữ nguyên, chỉ nhắc lại logic updateBookingStatus)
    public function insert($data) {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO thanh_toan (booking_id, so_tien, phuong_thuc, nhan_vien_id, ghi_chu, ngay_thanh_toan) 
                    VALUES (:bid, :tien, :pt, :uid, :note, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':bid'  => $data['booking_id'],
                ':tien' => $data['so_tien'],
                ':pt'   => $data['phuong_thuc'],
                ':uid'  => $_SESSION['user_admin']['id'] ?? 0,
                ':note' => $data['ghi_chu']
            ]);

            $this->updateBookingStatus($data['booking_id']); // Tính lại tổng tiền
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // [MỚI] Cập nhật phiếu thu
    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE thanh_toan SET 
                        so_tien = :tien, 
                        phuong_thuc = :pt, 
                        ghi_chu = :note 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id'   => $id,
                ':tien' => $data['so_tien'],
                ':pt'   => $data['phuong_thuc'],
                ':note' => $data['ghi_chu']
            ]);

            // Tính lại tổng tiền cho booking này
            $this->updateBookingStatus($data['booking_id']);
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // [CẬP NHẬT] Xóa và tính lại tiền
    public function delete($id) {
        // Lấy booking_id trước khi xóa
        $stmt = $this->conn->prepare("SELECT booking_id FROM thanh_toan WHERE id = ?");
        $stmt->execute([$id]);
        $booking_id = $stmt->fetchColumn();

        if ($booking_id) {
            $this->conn->prepare("DELETE FROM thanh_toan WHERE id = ?")->execute([$id]);
            $this->updateBookingStatus($booking_id); // Tính lại tiền
        }
    }

    // Hàm nội bộ: Tính toán lại trạng thái booking
    private function updateBookingStatus($booking_id) {
        // 1. Tổng đã thu
        $stmt = $this->conn->prepare("SELECT SUM(so_tien) FROM thanh_toan WHERE booking_id = ?");
        $stmt->execute([$booking_id]);
        $tong_da_thu = $stmt->fetchColumn() ?: 0;

        // 2. Tổng giá trị đơn hàng
        $stmt = $this->conn->prepare("SELECT tong_tien FROM booking WHERE id = ?");
        $stmt->execute([$booking_id]);
        $tong_tien_tour = $stmt->fetchColumn() ?: 0;

        // 3. Xác định trạng thái
        $status = 'CHUA_THANH_TOAN';
        if ($tong_da_thu >= $tong_tien_tour) {
            $status = 'DA_THANH_TOAN';
        } elseif ($tong_da_thu > 0) {
            $status = 'DA_COC';
        }

        // 4. Update Booking
        $update = $this->conn->prepare("UPDATE booking SET da_thanh_toan = ?, trang_thai_thanh_toan = ? WHERE id = ?");
        $update->execute([$tong_da_thu, $status, $booking_id]);
    }
}
?>