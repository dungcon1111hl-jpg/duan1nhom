<?php
class PhanPhongModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Lấy khách từ bảng diem_danh_khach đã check-in LÀ 'CÓ MẶT' theo lich_id
     * Trả về mảng gồm: diem_danh_id, ten_khach, khach_id, trang_thai, hinh_anh
     */
    public function getKhachByLich($lich_id) {
        $sql = "
            SELECT 
                id AS diem_danh_id,
                ten_khach,
                khach_id,
                trang_thai,
                hinh_anh
            FROM diem_danh_khach
            WHERE lich_id = :lich_id
              AND khach_id IS NOT NULL
              AND trang_thai = 'CO_MAT' -- ⭐ CHỈ LẤY KHÁCH CÓ MẶT ⭐
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['lich_id' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lưu phân phòng vào bảng phan_phong_khach
     */
    public function store($data) {
        $sql = "
            INSERT INTO phan_phong_khach
            (lich_id, khach_id, ten_khach, so_phong, loai_phong, so_nguoi, ghi_chu, created_at)
            VALUES (:lich_id, :khach_id, :ten_khach, :so_phong, :loai_phong, :so_nguoi, :ghi_chu, NOW())
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':lich_id'   => $data['lich_id'],
            ':khach_id'  => $data['khach_id'],
            ':ten_khach' => $data['ten_khach'] ?? null,
            ':so_phong'  => $data['so_phong'],
            ':loai_phong'=> $data['loai_phong'] ?? null,
            ':so_nguoi'  => $data['so_nguoi'] ?? 1,
            ':ghi_chu'   => $data['ghi_chu'] ?? null
        ]);
    }

    /**
     * Lấy danh sách phòng đã phân theo lich_id
     */
    public function getList($lich_id) {
        $sql = "SELECT * FROM phan_phong_khach WHERE lich_id = :lich_id ORDER BY so_phong ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['lich_id' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}