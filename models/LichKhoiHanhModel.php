<?php
class LichKhoiHanhModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy danh sách lịch khởi hành (kèm tên tour)
    public function getAll() {
        $sql = "SELECT l.*, t.ten_tour, t.ma_tour 
                FROM lich_khoi_hanh l 
                JOIN tour t ON l.tour_id = t.id 
                ORDER BY l.ngay_khoi_hanh DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy chi tiết 1 lịch (kèm thông tin tour)
    public function getById($id) {
        $sql = "SELECT l.*, t.ten_tour, t.ma_tour 
                FROM lich_khoi_hanh l 
                JOIN tour t ON l.tour_id = t.id 
                WHERE l.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. [FIX LỖI] Hàm lấy dịch vụ đã đặt (để Controller gọi không bị lỗi)
    public function getServices($lich_id) {
        $sql = "SELECT pd.*, n.ten_don_vi, n.loai_dich_vu 
                FROM phan_bo_dich_vu pd
                LEFT JOIN nha_cung_cap n ON pd.ncc_id = n.id
                WHERE pd.lich_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 4. Hàm lấy nhân sự đã phân công
    public function getNhanSu($lich_id) {
        $sql = "SELECT * FROM phan_cong_nhan_su WHERE lich_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Tạo kế hoạch mới (Transaction: Lưu Lịch + Nhân sự + Dịch vụ cùng lúc)
    public function createPlan($data) {
        try {
            $this->conn->beginTransaction();

            // A. Insert Lịch
            $sql = "INSERT INTO lich_khoi_hanh (tour_id, ngay_khoi_hanh, ngay_ket_thuc, diem_tap_trung, so_cho_toi_da, gia_nguoi_lon, gia_tre_em, trang_thai, ghi_chu) 
                    VALUES (:tour, :bd, :kt, :diem, :cho, :gia_nl, :gia_te, 'DU_KIEN', :gc)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tour' => $data['tour_id'],
                ':bd'   => $data['ngay_khoi_hanh'],
                ':kt'   => $data['ngay_ket_thuc'],
                ':diem' => $data['diem_tap_trung'],
                ':cho'  => $data['so_cho_toi_da'] ?? 20,
                ':gia_nl' => $data['gia_nguoi_lon'] ?? 0,
                ':gia_te' => $data['gia_tre_em'] ?? 0,
                ':gc'   => $data['ghi_chu'] ?? ''
            ]);
            $lich_id = $this->conn->lastInsertId();

            // B. Insert Nhân Sự (nếu có)
            if (!empty($data['nhan_su'])) {
                $sqlNS = "INSERT INTO phan_cong_nhan_su (lich_id, ho_ten, vai_tro, so_dien_thoai) VALUES (?, ?, ?, ?)";
                $stmtNS = $this->conn->prepare($sqlNS);
                foreach ($data['nhan_su'] as $ns) {
                    if (!empty($ns['ho_ten'])) {
                        $stmtNS->execute([$lich_id, $ns['ho_ten'], $ns['vai_tro'], $ns['sdt'] ?? '']);
                    }
                }
            }

            // C. Insert Dịch Vụ (nếu có)
            if (!empty($data['dich_vu'])) {
                $sqlDV = "INSERT INTO phan_bo_dich_vu (lich_id, ncc_id, loai_dich_vu, chi_tiet, don_gia, so_luong, thanh_tien) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtDV = $this->conn->prepare($sqlDV);
                foreach ($data['dich_vu'] as $dv) {
                    if (!empty($dv['ncc_id'])) {
                        $tt = (float)$dv['don_gia'] * (int)$dv['so_luong'];
                        $stmtDV->execute([$lich_id, $dv['ncc_id'], $dv['loai_dich_vu'], $dv['chi_tiet'], $dv['don_gia'], $dv['so_luong'], $tt]);
                    }
                }
            }

            $this->conn->commit();
            return $lich_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // 6. Cập nhật thông tin lịch
    public function update($id, $data) {
        $sql = "UPDATE lich_khoi_hanh SET 
                tour_id=:tour, ngay_khoi_hanh=:bd, ngay_ket_thuc=:kt, diem_tap_trung=:diem, 
                so_cho_toi_da=:cho, gia_nguoi_lon=:gia_nl, gia_tre_em=:gia_te, trang_thai=:tt, ghi_chu=:gc 
                WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'   => $id,
            ':tour' => $data['tour_id'],
            ':bd'   => $data['ngay_khoi_hanh'],
            ':kt'   => $data['ngay_ket_thuc'],
            ':diem' => $data['diem_tap_trung'],
            ':cho'  => $data['so_cho_toi_da'],
            ':gia_nl' => $data['gia_nguoi_lon'],
            ':gia_te' => $data['gia_tre_em'],
            ':tt'   => $data['trang_thai'],
            ':gc'   => $data['ghi_chu']
        ]);
    }
    
    // Alias (để tương thích nếu Controller gọi tên khác)
    public function updateById($id, $data) { return $this->update($id, $data); }

    // 7. Xóa lịch
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM lich_khoi_hanh WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Alias
    public function deleteById($id) { return $this->delete($id); }
}
?>