<?php
class ThanhToanModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Hàm lấy tất cả giao dịch (Sửa để lấy cột ma_giao_dich)
    public function getAllTransactions() {
        $sql = "SELECT tt.*, 
                       b.snapshot_kh_ho_ten as ten_khach,
                       b.snapshot_ten_tour as ten_tour,
                       u.full_name as ten_nhan_vien
                FROM thanh_toan tt
                LEFT JOIN booking b ON tt.booking_id = b.id
                LEFT JOIN users u ON tt.nhan_vien_id = u.id
                ORDER BY tt.ngay_thanh_toan DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Hàm lấy 1 giao dịch để sửa
    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM thanh_toan WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByBooking($booking_id) {
        // ... (Giữ nguyên code cũ) ...
        // Bạn có thể copy lại từ file cũ nếu cần
        $sql = "SELECT tt.*, u.full_name as ten_nhan_vien 
                FROM thanh_toan tt 
                LEFT JOIN users u ON tt.nhan_vien_id = u.id 
                WHERE booking_id = :bid ORDER BY ngay_thanh_toan DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':bid' => $booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // [QUAN TRỌNG] Insert với mã random
    public function insert($data) {
        try {
            $this->conn->beginTransaction();

            // Sinh mã ngẫu nhiên: Vd: TRX-83921
            $randCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $ma_gd = 'TRX-' . $randCode;

            $sql = "INSERT INTO thanh_toan (booking_id, ma_giao_dich, so_tien, phuong_thuc, nhan_vien_id, ghi_chu, ngay_thanh_toan) 
                    VALUES (:bid, :ma_gd, :tien, :pt, :uid, :note, NOW())";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':bid'    => $data['booking_id'],
                ':ma_gd'  => $ma_gd, // Lưu mã
                ':tien'   => $data['so_tien'],
                ':pt'     => $data['phuong_thuc'],
                ':uid'    => $_SESSION['user_admin']['id'] ?? 0,
                ':note'   => $data['ghi_chu']
            ]);

            // Cập nhật tổng tiền bên Booking
            $this->updateBookingStatus($data['booking_id']);
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();
            $sql = "UPDATE thanh_toan SET so_tien=:t, phuong_thuc=:p, ghi_chu=:g WHERE id=:id";
            $this->conn->prepare($sql)->execute([
                ':t'=>$data['so_tien'], ':p'=>$data['phuong_thuc'], ':g'=>$data['ghi_chu'], ':id'=>$id
            ]);
            
            // Lấy booking_id để update lại tổng
            $curr = $this->getOne($id);
            $this->updateBookingStatus($curr['booking_id']);

            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack(); return false;
        }
    }

    public function delete($id) {
        $curr = $this->getOne($id);
        if($curr) {
            $this->conn->prepare("DELETE FROM thanh_toan WHERE id=?")->execute([$id]);
            $this->updateBookingStatus($curr['booking_id']);
        }
    }

    private function updateBookingStatus($booking_id) {
        $tong = $this->conn->query("SELECT SUM(so_tien) FROM thanh_toan WHERE booking_id=$booking_id")->fetchColumn() ?: 0;
        $this->conn->prepare("UPDATE booking SET da_thanh_toan=? WHERE id=?")->execute([$tong, $booking_id]);
    }
}
?>