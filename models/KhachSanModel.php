<?php
class KhachSanModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy danh sách để hiển thị ra ô chọn
    public function getAll() {
        $sql = "SELECT * FROM khach_san ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 khách sạn (để lấy tên khách sạn lưu vào bảng phân phòng)
    public function getOne($id) {
        $sql = "SELECT * FROM khach_san WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>