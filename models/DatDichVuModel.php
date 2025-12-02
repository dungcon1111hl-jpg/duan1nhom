<?php
class DatDichVuModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // [MỚI] Lấy tất cả dịch vụ (cho trang danh sách tổng)
    public function getAll() {
        $sql = "SELECT * FROM dat_dich_vu ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy theo Booking ID
    public function getByBookingId($booking_id) {
        $sql = "SELECT * FROM dat_dich_vu WHERE booking_id = :id ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 dịch vụ
    public function getOne($id) {
        $sql = "SELECT * FROM dat_dich_vu WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới
    public function insert($data) {
        $thanh_tien = (float)$data['so_luong'] * (float)$data['don_gia'];
        $sql = "INSERT INTO dat_dich_vu (booking_id, ten_dich_vu, so_luong, don_gia, thanh_tien, ghi_chu) 
                VALUES (:booking_id, :ten_dich_vu, :so_luong, :don_gia, :thanh_tien, :ghi_chu)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':booking_id'  => $data['booking_id'],
            ':ten_dich_vu' => $data['ten_dich_vu'],
            ':so_luong'    => $data['so_luong'],
            ':don_gia'     => $data['don_gia'],
            ':thanh_tien'  => $thanh_tien,
            ':ghi_chu'     => $data['ghi_chu'] ?? ''
        ]);
    }

    // Cập nhật
    public function update($id, $data) {
        $thanh_tien = (float)$data['so_luong'] * (float)$data['don_gia'];
        $sql = "UPDATE dat_dich_vu SET 
                    ten_dich_vu = :ten_dich_vu,
                    so_luong = :so_luong,
                    don_gia = :don_gia,
                    thanh_tien = :thanh_tien,
                    ghi_chu = :ghi_chu
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':ten_dich_vu' => $data['ten_dich_vu'],
            ':so_luong'    => $data['so_luong'],
            ':don_gia'     => $data['don_gia'],
            ':thanh_tien'  => $thanh_tien,
            ':ghi_chu'     => $data['ghi_chu'] ?? ''
        ]);
    }

    // Xóa
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM dat_dich_vu WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>