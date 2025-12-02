<?php
class LichBanHDVModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách lịch bận (kèm tên HDV)
    public function getAll() {
        $sql = "SELECT lb.*, h.ho_ten, h.so_dien_thoai 
                FROM lich_ban_hdv lb 
                JOIN huong_dan_vien h ON lb.hdv_id = h.id 
                ORDER BY lb.ngay_bat_dau DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 lịch bận
    public function getById($id) {
        $sql = "SELECT * FROM lich_ban_hdv WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới
    public function insert($data) {
        $sql = "INSERT INTO lich_ban_hdv (hdv_id, ngay_bat_dau, ngay_ket_thuc, ly_do, loai_lich, trang_thai, ghi_chu) 
                VALUES (:hdv_id, :bd, :kt, :ly_do, :loai, :tt, :gc)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':hdv_id' => $data['hdv_id'],
            ':bd'     => $data['ngay_bat_dau'],
            ':kt'     => $data['ngay_ket_thuc'],
            ':ly_do'  => $data['ly_do'],
            ':loai'   => $data['loai_lich'],
            ':tt'     => $data['trang_thai'] ?? 'CHO_XAC_NHAN',
            ':gc'     => $data['ghi_chu'] ?? ''
        ]);
    }

    // Cập nhật
    public function update($id, $data) {
        $sql = "UPDATE lich_ban_hdv SET 
                    hdv_id = :hdv_id,
                    ngay_bat_dau = :bd,
                    ngay_ket_thuc = :kt,
                    ly_do = :ly_do,
                    loai_lich = :loai,
                    trang_thai = :tt,
                    ghi_chu = :gc
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':hdv_id' => $data['hdv_id'],
            ':bd'     => $data['ngay_bat_dau'],
            ':kt'     => $data['ngay_ket_thuc'],
            ':ly_do'  => $data['ly_do'],
            ':loai'   => $data['loai_lich'],
            ':tt'     => $data['trang_thai'],
            ':gc'     => $data['ghi_chu'] ?? ''
        ]);
    }

    // Xóa
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM lich_ban_hdv WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Kiểm tra trùng lịch (Để warning khi phân công)
    public function checkConflict($hdv_id, $start, $end) {
        // Logic kiểm tra xem khoảng thời gian này HDV có bận không
        $sql = "SELECT COUNT(*) FROM lich_ban_hdv 
                WHERE hdv_id = :uid 
                AND trang_thai = 'DA_XAC_NHAN'
                AND (
                    (ngay_bat_dau <= :end AND ngay_ket_thuc >= :start)
                )";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid'=>$hdv_id, ':start'=>$start, ':end'=>$end]);
        return $stmt->fetchColumn() > 0;
    }
}
?>