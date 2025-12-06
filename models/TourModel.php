<?php
class TourModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // [CẬP NHẬT] Thêm hdv_id vào buildData
    private function buildData(array $data): array
    {
        return [
            ':ma_tour'         => $data['ma_tour'] ?? null,
            ':ten_tour'        => $data['ten_tour'] ?? null,
            ':loai_tour'       => $data['loai_tour'] ?? 'TRONG_NUOC',
            ':loai_nang_cao'   => $data['loai_tour_nang_cao'] ?? 'TRON_GOI',
            ':doi_tuong'       => $data['doi_tuong_khach'] ?? 'KHACH_LE',
            ':mo_ta_ngan'      => $data['mo_ta_ngan'] ?? null,
            ':dia_bd'          => $data['dia_diem_bat_dau'] ?? null,
            ':dia_tg'          => $data['diem_trung_chuyen'] ?? null,
            ':dia_kt'          => $data['dia_diem_ket_thuc'] ?? null,
            ':ngay_bd'         => !empty($data['ngay_khoi_hanh']) ? $data['ngay_khoi_hanh'] : null,
            ':ngay_kt'         => !empty($data['ngay_ket_thuc']) ? $data['ngay_ket_thuc'] : null,
            ':gia_tour'        => !empty($data['gia_tour']) ? (float)$data['gia_tour'] : 0,
            ':gia_nl'          => !empty($data['gia_nguoi_lon']) ? (float)$data['gia_nguoi_lon'] : 0,
            ':gia_te'          => !empty($data['gia_tre_em']) ? (float)$data['gia_tre_em'] : 0,
            ':gia_eb'          => !empty($data['gia_em_be']) ? (float)$data['gia_em_be'] : 0,
            ':phu_thu'         => !empty($data['phu_thu']) ? (float)$data['phu_thu'] : 0,
            ':mo_ta_ct'        => $data['mo_ta_chi_tiet'] ?? null,
            
            // [MỚI] Thay thong_tin_hdv bằng hdv_id
            ':hdv_id'          => !empty($data['hdv_id']) ? (int)$data['hdv_id'] : null, 
            
            ':anh'             => $data['anh_minh_hoa'] ?? null,
            ':sl_ve'           => !empty($data['so_luong_ve']) ? (int)$data['so_luong_ve'] : 0,
            ':sl_min'          => !empty($data['so_khach_toithieu']) ? (int)$data['so_khach_toithieu'] : 1,
            ':sl_con'          => isset($data['so_ve_con_lai']) ? (int)$data['so_ve_con_lai'] : 0,
            ':trang_thai'      => $data['trang_thai'] ?? 'CON_VE',
            ':chinh_sach'      => $data['chinh_sach'] ?? null,
        ];
    }

    // [CẬP NHẬT] store: Thêm hdv_id vào câu lệnh INSERT
    public function store(array $data): int|bool {
        $sql = "INSERT INTO tour (
            ma_tour, ten_tour, loai_tour, loai_tour_nang_cao, doi_tuong_khach, mo_ta_ngan,
            dia_diem_bat_dau, diem_trung_chuyen, dia_diem_ket_thuc,
            ngay_khoi_hanh, ngay_ket_thuc,
            gia_tour, gia_nguoi_lon, gia_tre_em, gia_em_be, phu_thu,
            mo_ta_chi_tiet, hdv_id, anh_minh_hoa, 
            so_luong_ve, so_khach_toithieu, so_ve_con_lai,
            trang_thai, chinh_sach, ngay_tao
        ) VALUES (
            :ma_tour, :ten_tour, :loai_tour, :loai_nang_cao, :doi_tuong, :mo_ta_ngan,
            :dia_bd, :dia_tg, :dia_kt,
            :ngay_bd, :ngay_kt,
            :gia_tour, :gia_nl, :gia_te, :gia_eb, :phu_thu,
            :mo_ta_ct, :hdv_id, :anh,
            :sl_ve, :sl_min, :sl_con,
            :trang_thai, :chinh_sach, NOW()
        )";
        
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($this->buildData($data))) {
            $newId = $this->db->lastInsertId();
            if (!empty($data['ncc'])) {
                // SỬA: Đổi tên saveTourNCC thành updateTourNCC hoặc thêm phương thức public
                $this->updateTourNCC((int)$newId, $data['ncc']); 
            }
            return (int)$newId;
        }
        return false;
    }

    // [CẬP NHẬT] update: Thêm hdv_id vào câu lệnh UPDATE
    public function update(int $id, array $data): bool {
        $sql = "UPDATE tour SET
            ma_tour = :ma_tour, ten_tour = :ten_tour,
            loai_tour = :loai_tour, loai_tour_nang_cao = :loai_nang_cao, doi_tuong_khach = :doi_tuong,
            mo_ta_ngan = :mo_ta_ngan,
            dia_diem_bat_dau = :dia_bd, diem_trung_chuyen = :dia_tg, dia_diem_ket_thuc = :dia_kt,
            ngay_khoi_hanh = :ngay_bd, ngay_ket_thuc = :ngay_kt,
            gia_tour = :gia_tour, gia_nguoi_lon = :gia_nl, gia_tre_em = :gia_te, gia_em_be = :gia_eb, phu_thu = :phu_thu,
            mo_ta_chi_tiet = :mo_ta_ct, hdv_id = :hdv_id, 
            anh_minh_hoa = :anh,
            so_luong_ve = :sl_ve, so_khach_toithieu = :sl_min, so_ve_con_lai = :sl_con,
            trang_thai = :trang_thai, chinh_sach = :chinh_sach, ngay_cap_nhat = NOW()
        WHERE id = :id";

        $params = $this->buildData($data);
        $params[':id'] = $id;

        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($params)) {
            // SỬA: Đổi tên saveTourNCC thành updateTourNCC
            $this->updateTourNCC($id, $data['ncc'] ?? []);
            return true;
        }
        return false;
    }

    // Các hàm getById, getAll, softDelete, getSelectedNCC GIỮ NGUYÊN
    public function getById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM tour WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll(array $filters = []): PDOStatement {
        $sql = "SELECT * FROM tour WHERE 1=1";
        if (!empty($filters['ten_tour'])) {
            $sql .= " AND (ten_tour LIKE '%" . $filters['ten_tour'] . "%' OR ma_tour LIKE '%" . $filters['ten_tour'] . "%')";
        }
        // Có thể thêm các filter khác ở đây
        $sql .= " ORDER BY id DESC";
        return $this->db->query($sql);
    }
    
    public function softDelete(int $id) {
        $stmt = $this->db->prepare("UPDATE tour SET trang_thai = 'NGUNG_HOAT_DONG' WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    public function getSelectedNCC(int $tour_id) {
        $stmt = $this->db->prepare("SELECT ncc_id FROM tour_ncc WHERE tour_id = :id");
        $stmt->execute([':id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // [FIX] Thêm phương thức updateTourNCC (công khai) để Controller gọi được
    public function updateTourNCC(int $tour_id, array $ncc_ids) {
        // 1. Xóa các liên kết cũ
        $stmt = $this->db->prepare("DELETE FROM tour_ncc WHERE tour_id = :id");
        $stmt->execute([':id' => $tour_id]);
        
        // 2. Thêm các liên kết mới
        if (!empty($ncc_ids)) {
            $ins = $this->db->prepare("INSERT INTO tour_ncc (tour_id, ncc_id) VALUES (:tid, :nid)");
            foreach ($ncc_ids as $nid) {
                // Đảm bảo nid là số nguyên để tránh lỗi
                $ins->execute([':tid' => $tour_id, ':nid' => (int)$nid]);
            }
        }
    }
}
?>