<?php
class NhatKyModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Lấy danh sách nhật ký theo ID tour (tương ứng với lich_id trong bảng)
    public function getAllByTourId($tourId) {
        // Sắp xếp ngày ghi mới nhất lên đầu
        $sql = "SELECT * FROM nhat_ky_tour WHERE lich_id = :lich_id ORDER BY ngay_ghi DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':lich_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm mới
    public function store($data) {
        $sql = "INSERT INTO nhat_ky_tour (lich_id, hdv_id, loai_ghi_chep, tieu_de, noi_dung, cach_xu_ly, danh_gia_sao, ngay_ghi) 
                VALUES (:lich_id, :hdv_id, :loai, :tieu_de, :noi_dung, :cach_xu_ly, :danh_gia, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':lich_id'    => $data['lich_id'],
            ':hdv_id'     => $data['hdv_id'] ?? null, // Có thể để null nếu Admin nhập
            ':loai'       => $data['loai_ghi_chep'],
            ':tieu_de'    => $data['tieu_de'],
            ':noi_dung'   => $data['noi_dung'],
            ':cach_xu_ly' => $data['cach_xu_ly'] ?? null,
            ':danh_gia'   => $data['danh_gia_sao'] ?? 0
        ]);
    }

    // Xóa
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM nhat_ky_tour WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}