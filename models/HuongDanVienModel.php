<?php
class HuongDanVienModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM huong_dan_vien ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // [MỚI] Lấy danh sách ID các HDV đang bận trong khoảng thời gian này
    public function getBusyIds($startTime, $endTime) {
        $busyIds = [];

        // 1. Kiểm tra trong bảng Lịch Bận (Nghỉ phép, việc riêng...)
        $sql1 = "SELECT DISTINCT hdv_id FROM lich_ban_hdv 
                 WHERE trang_thai = 'DA_XAC_NHAN' 
                 AND (
                    (ngay_bat_dau <= :end1 AND ngay_ket_thuc >= :start1)
                 )";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([':start1' => $startTime, ':end1' => $endTime]);
        $busyIds = array_merge($busyIds, $stmt1->fetchAll(PDO::FETCH_COLUMN));

        // 2. Kiểm tra trong bảng Phân Công (Đang đi tour khác)
        $sql2 = "SELECT DISTINCT pc.hdv_id 
                 FROM phan_cong_nhan_su pc
                 JOIN lich_khoi_hanh lk ON pc.lich_id = lk.id
                 WHERE pc.hdv_id IS NOT NULL 
                 AND lk.trang_thai != 'HUY'
                 AND (
                    (lk.ngay_khoi_hanh <= :end2 AND lk.ngay_ket_thuc >= :start2)
                 )";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute([':start2' => $startTime, ':end2' => $endTime]);
        $busyIds = array_merge($busyIds, $stmt2->fetchAll(PDO::FETCH_COLUMN));

        return array_unique($busyIds); // Loại bỏ ID trùng lặp
    }

    // ... (Các hàm insert, update, delete cũ giữ nguyên) ...
    public function getOne($id) { /*...*/ }
    public function insert($data) { /*...*/ }
    public function update($id, $data) { /*...*/ }
    public function delete($id) { /*...*/ }
}
?>