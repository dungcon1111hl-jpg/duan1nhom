<?php
class HuongDanVienModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách tất cả HDV
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM huong_dan_vien ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 HDV
    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM huong_dan_vien WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới HDV (Fix lỗi Unknown column: Đã khớp với database sau khi chạy lệnh SQL ở Bước 1)
    public function insert($data) {
        $sql = "INSERT INTO huong_dan_vien (
                    ho_ten, ngay_sinh, so_dien_thoai, email, 
                    ngon_ngu, kinh_nghiem, chung_chi, 
                    trang_thai, gioi_tinh, dia_chi, anh_dai_dien
                ) VALUES (
                    :ten, :ns, :sdt, :email, 
                    :nn, :kn, :cc, 
                    :tt, :gt, :dc, :anh
                )";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ten'   => $data['ho_ten'],
            ':ns'    => !empty($data['ngay_sinh']) ? $data['ngay_sinh'] : null,
            ':sdt'   => $data['so_dien_thoai'],
            ':email' => $data['email'] ?? '',
            ':nn'    => $data['ngon_ngu'] ?? '',
            ':kn'    => $data['kinh_nghiem'] ?? '',
            ':cc'    => $data['chung_chi'] ?? '',
            ':tt'    => $data['trang_thai'] ?? 'DANG_LAM_VIEC',
            ':gt'    => $data['gioi_tinh'] ?? 'Khac', // Cột này gây lỗi nếu DB chưa có
            ':dc'    => $data['dia_chi'] ?? '',       // Cột này cũng cần thêm vào DB
            ':anh'   => $data['anh_dai_dien'] ?? null
        ]);
    }

    // Cập nhật thông tin HDV
    public function update($id, $data) {
        // Xây dựng câu lệnh update động (chỉ update ảnh nếu có ảnh mới)
        $anh_sql = "";
        $params = [
            ':id'    => $id,
            ':ten'   => $data['ho_ten'],
            ':ns'    => !empty($data['ngay_sinh']) ? $data['ngay_sinh'] : null,
            ':sdt'   => $data['so_dien_thoai'],
            ':email' => $data['email'] ?? '',
            ':nn'    => $data['ngon_ngu'] ?? '',
            ':kn'    => $data['kinh_nghiem'] ?? '',
            ':cc'    => $data['chung_chi'] ?? '',
            ':tt'    => $data['trang_thai'],
            ':gt'    => $data['gioi_tinh'] ?? 'Khac',
            ':dc'    => $data['dia_chi'] ?? ''
        ];

        if (!empty($data['anh_dai_dien'])) {
            $anh_sql = ", anh_dai_dien = :anh";
            $params[':anh'] = $data['anh_dai_dien'];
        }

        $sql = "UPDATE huong_dan_vien SET 
                    ho_ten = :ten, 
                    ngay_sinh = :ns, 
                    so_dien_thoai = :sdt, 
                    email = :email, 
                    ngon_ngu = :nn, 
                    kinh_nghiem = :kn, 
                    chung_chi = :cc, 
                    trang_thai = :tt,
                    gioi_tinh = :gt,
                    dia_chi = :dc
                    $anh_sql
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Xóa HDV
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM huong_dan_vien WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Kiểm tra lịch bận (Dùng cho chức năng check trùng lịch)
    public function getBusyHDVs($start, $end) {
        $sql = "SELECT hdv_id FROM lich_ban_hdv WHERE 
                (ngay_bat_dau <= :end AND ngay_ket_thuc >= :start) AND trang_thai = 'DA_XAC_NHAN'
                UNION
                SELECT pc.hdv_id FROM phan_cong_nhan_su pc 
                JOIN lich_khoi_hanh l ON pc.lich_id = l.id
                WHERE (l.ngay_khoi_hanh <= :end AND l.ngay_ket_thuc >= :start) AND l.trang_thai != 'HUY'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':start' => $start, ':end' => $end]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>