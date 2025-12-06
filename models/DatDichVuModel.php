<?php
class DatDichVuModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM dat_dich_vu ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByBooking($booking_id) {
        $stmt = $this->conn->prepare("SELECT * FROM dat_dich_vu WHERE booking_id = ? ORDER BY id DESC");
        $stmt->execute([$booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM dat_dich_vu WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // [FIX] Thêm mới và cập nhật tiền an toàn
    public function insert($data) {
        try {
            $this->conn->beginTransaction();

            $thanh_tien = (float)$data['so_luong'] * (float)$data['don_gia'];
            
            // 1. Thêm dịch vụ
            $sql = "INSERT INTO dat_dich_vu (booking_id, ten_dich_vu, so_luong, don_gia, thanh_tien, ghi_chu) 
                    VALUES (:bid, :ten, :sl, :gia, :tt, :gc)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':bid' => $data['booking_id'],
                ':ten' => $data['ten_dich_vu'],
                ':sl'  => $data['so_luong'],
                ':gia' => $data['don_gia'],
                ':tt'  => $thanh_tien,
                ':gc'  => $data['ghi_chu'] ?? ''
            ]);

            // 2. Cộng tiền vào Booking (Dùng COALESCE để tránh lỗi NULL)
            $this->updateBookingTotal($data['booking_id'], $thanh_tien);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            // Lưu lỗi vào Session để hiển thị ra màn hình
            if(session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['system_error'] = $e->getMessage();
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();

            $old = $this->getById($id);
            if (!$old) throw new Exception("Không tìm thấy dịch vụ cũ");

            $thanh_tien_moi = (float)$data['so_luong'] * (float)$data['don_gia'];

            $sql = "UPDATE dat_dich_vu SET 
                        ten_dich_vu = :ten, 
                        so_luong = :sl, 
                        don_gia = :gia, 
                        thanh_tien = :tt, 
                        ghi_chu = :gc 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ten' => $data['ten_dich_vu'],
                ':sl'  => $data['so_luong'],
                ':gia' => $data['don_gia'],
                ':tt'  => $thanh_tien_moi,
                ':gc'  => $data['ghi_chu'],
                ':id'  => $id
            ]);

            // Cập nhật chênh lệch tiền
            $diff = $thanh_tien_moi - $old['thanh_tien'];
            if ($diff != 0) {
                $this->updateBookingTotal($old['booking_id'], $diff);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            $_SESSION['system_error'] = $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        try {
            $this->conn->beginTransaction();
            
            $old = $this->getById($id);
            if ($old) {
                $this->conn->prepare("DELETE FROM dat_dich_vu WHERE id = ?")->execute([$id]);
                $this->updateBookingTotal($old['booking_id'], -$old['thanh_tien']);
            }
            
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
        }
    }

    // [QUAN TRỌNG] Hàm cập nhật tiền an toàn với giá trị NULL
    private function updateBookingTotal($booking_id, $amount) {
        // COALESCE(tong_tien, 0) sẽ biến NULL thành 0 trước khi cộng
        $sql = "UPDATE booking SET tong_tien = COALESCE(tong_tien, 0) + :amount WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':amount' => $amount, ':id' => $booking_id]);
    }
}
?>