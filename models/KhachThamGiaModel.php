<?php
class KhachThamGiaModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    /**
     * Lấy thông tin 1 khách theo ID (HÀM BỊ THIẾU)
     */
    public function getOne($id) {
        $sql = "SELECT * FROM khach_tham_gia WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy danh sách khách của 1 booking cụ thể
     */
    public function getByBookingId($booking_id) {
        $sql = "SELECT * FROM khach_tham_gia WHERE booking_id = :booking_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':booking_id' => $booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm một khách hàng mới vào booking
     */
    public function insert($data) {
        $sql = "INSERT INTO khach_tham_gia (booking_id, ho_ten, gioi_tinh, ngay_sinh, so_giay_to, ghi_chu, yeu_cau_dac_biet) 
                VALUES (:booking_id, :ho_ten, :gioi_tinh, :ngay_sinh, :so_giay_to, :ghi_chu, :yeu_cau_dac_biet)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Cập nhật thông tin khách
     */
    public function update($id, $data) {
        $sql = "UPDATE khach_tham_gia SET 
                    ho_ten = :ho_ten, 
                    gioi_tinh = :gioi_tinh, 
                    ngay_sinh = :ngay_sinh, 
                    so_giay_to = :so_giay_to, 
                    ghi_chu = :ghi_chu, 
                    yeu_cau_dac_biet = :yeu_cau_dac_biet 
                WHERE id = :id";
        
        $params = [
            ':ho_ten' => $data['ho_ten'],
            ':gioi_tinh' => $data['gioi_tinh'],
            ':ngay_sinh' => !empty($data['ngay_sinh']) ? $data['ngay_sinh'] : null,
            ':so_giay_to' => $data['so_giay_to'],
            ':ghi_chu' => $data['ghi_chu'],
            ':yeu_cau_dac_biet' => $data['yeu_cau_dac_biet'],
            ':id' => $id 
        ];

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params); 
    }

    /**
     * Xóa một khách hàng khỏi booking
     */
    public function delete($id) {
        $sql = "DELETE FROM khach_tham_gia WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>