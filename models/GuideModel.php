<?php
class GuideModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy danh sách Tour được phân công cho HDV này
    public function getMyTours($user_id) {
        // Sửa lỗi: Bỏ t.diem_tap_trung vì cột này nằm ở bảng lich_khoi_hanh (l.* đã bao gồm nó)
        $sql = "SELECT l.*, t.ten_tour, t.ma_tour, t.anh_minh_hoa, 
                       (SELECT COUNT(*) FROM khach_tham_gia k 
                        JOIN booking b ON k.booking_id = b.id 
                        WHERE b.lich_id = l.id AND b.trang_thai != 'HUY') as tong_khach
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

    // 3. Lấy danh sách khách (Dùng bảng khach_tham_gia)
    public function getPassengers($lich_id) {
        $sql = "SELECT 
                    ktg.id,
                    ktg.ho_ten,
                    ktg.gioi_tinh,
                    ktg.ngay_sinh,
                    ktg.yeu_cau_dac_biet AS ghi_chu_dac_biet, 
                    'Khách' AS loai_khach,
                    
                    -- Chuyển đổi trạng thái enum sang 0/1 cho checkbox
                    CASE WHEN ktg.trang_thai_diem_danh = 'CO_MAT' THEN 1 ELSE 0 END AS trang_thai_checkin,
                    
                    b.id AS ma_booking,
                    b.khach_hang_id,
                    kh.so_dien_thoai AS sdt_lien_he

                FROM khach_tham_gia ktg
                JOIN booking b ON ktg.booking_id = b.id
                JOIN khach_hang kh ON b.khach_hang_id = kh.id
                WHERE b.lich_id = :lid AND b.trang_thai != 'HUY'";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':lid' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Cập nhật điểm danh (Map về bảng khach_tham_gia)
    public function updatePassengerStatus($id, $status, $lat = null, $long = null) {
        // status từ controller gửi sang là 1 (có mặt) hoặc 0 (vắng)
        $dbStatus = ($status == 1) ? 'CO_MAT' : 'CHUA_DIEM_DANH';

        // Cập nhật bảng khach_tham_gia
        $sql = "UPDATE khach_tham_gia SET 
                    trang_thai_diem_danh = :stt 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':stt' => $dbStatus, ':id' => $id]);

        // Lưu log vào bảng diem_danh_khach để tracking lịch sử và GPS
        if ($status == 1) {
            // Lấy lich_id và khach_id từ bảng khach_tham_gia để insert vào diem_danh_khach
            // Vì bảng khach_tham_gia lưu ID khách tham gia, còn bảng diem_danh_khach cần ID khách hàng (user) hoặc ID khách tham gia tùy logic
            // Ở đây ta dùng ID khách tham gia làm khach_id trong bảng log cho đơn giản và chính xác với từng người
            $infoSql = "SELECT b.lich_id FROM khach_tham_gia k JOIN booking b ON k.booking_id = b.id WHERE k.id = :kid";
            $infoStmt = $this->conn->prepare($infoSql);
            $infoStmt->execute([':kid' => $id]);
            $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

            if ($info) {
                $sqlLog = "INSERT INTO diem_danh_khach (lich_id, khach_id, trang_thai, thoi_gian, latitude, longitude) 
                           VALUES (:lid, :kid, 'CO_MAT', NOW(), :lat, :long)";
                $this->conn->prepare($sqlLog)->execute([
                    ':lid' => $info['lich_id'],
                    ':kid' => $id,
                    ':lat' => $lat,
                    ':long' => $long
                ]);
            }
        }
    }

    // Helper: Cập nhật ghi chú (Yêu cầu đặc biệt)
    public function updateSpecialRequest($id, $note) {
        $sql = "UPDATE khach_tham_gia SET yeu_cau_dac_biet = :note WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':note' => $note, ':id' => $id]);
    }

    // Helper: Lấy ID HDV từ User ID
    public function getHdvIdByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT id FROM huong_dan_vien WHERE user_id = :uid");
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchColumn();
    }
}
?>