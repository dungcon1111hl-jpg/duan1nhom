<?php
class GuideModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy danh sách Tour được phân công cho HDV này
    public function getMyTours($user_id) {
        // Logic: User -> HDV -> Phân công -> Lịch -> Tour
        $sql = "SELECT l.*, t.ten_tour, t.ma_tour, t.anh_minh_hoa 
                FROM lich_khoi_hanh l
                JOIN tour t ON l.tour_id = t.id
                JOIN phan_cong_nhan_su pc ON l.id = pc.lich_id
                JOIN huong_dan_vien hdv ON pc.hdv_id = hdv.id
                WHERE hdv.user_id = :uid 
                AND pc.vai_tro = 'HUONG_DAN_VIEN'
                ORDER BY l.ngay_khoi_hanh DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy chi tiết 1 Lịch trình
    public function getTourDetail($lich_id) {
        $sql = "SELECT l.*, t.ten_tour, t.ma_tour, t.mo_ta_chi_tiet as lich_trinh_mau
                FROM lich_khoi_hanh l
                JOIN tour t ON l.tour_id = t.id
                WHERE l.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $lich_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Lấy danh sách khách trong đoàn (Chi tiết từng người)
    public function getPassengers($lich_id) {
        $sql = "SELECT bk.*, b.ma_booking, b.khach_hang_id, kh.so_dien_thoai as sdt_lien_he
                FROM booking_khach bk
                JOIN booking b ON bk.booking_id = b.id
                JOIN khach_hang kh ON b.khach_hang_id = kh.id
                WHERE b.lich_id = :lid AND b.trang_thai != 'HUY'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':lid' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Cập nhật điểm danh / Ghi chú đặc biệt
    public function updatePassengerStatus($id, $status, $note) {
        $sql = "UPDATE booking_khach SET trang_thai_checkin = :stt, ghi_chu_dac_biet = :note WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':stt' => $status, ':note' => $note, ':id' => $id]);
    }

    // 5. Lấy nhật ký tour
    public function getDiaries($lich_id) {
        $sql = "SELECT * FROM nhat_ky_tour WHERE lich_id = :id ORDER BY ngay_ghi DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Viết nhật ký mới
    public function addDiary($data) {
        $sql = "INSERT INTO nhat_ky_tour (lich_id, hdv_id, tieu_de, noi_dung, loai_nhat_ky, hinh_anh) 
                VALUES (:lid, :hid, :td, :nd, :loai, :anh)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':lid' => $data['lich_id'],
            ':hid' => $data['hdv_id'],
            ':td'  => $data['tieu_de'],
            ':nd'  => $data['noi_dung'],
            ':loai'=> $data['loai_nhat_ky'],
            ':anh' => $data['hinh_anh'] ?? null
        ]);
    }
    
    // Helper: Lấy HDV ID từ User ID
    public function getHdvIdByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT id FROM huong_dan_vien WHERE user_id = :uid");
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchColumn();
    }
}
?>