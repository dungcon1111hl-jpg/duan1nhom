<?php
class PhanBoDichVuModel {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function getByLich($lich_id) {
        // Join để lấy tên nhà cung cấp
        $sql = "SELECT pd.*, n.ten_don_vi 
                FROM phan_bo_dich_vu pd
                JOIN nha_cung_cap n ON pd.ncc_id = n.id
                WHERE pd.lich_id = :id ORDER BY pd.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $thanh_tien = (float)$data['so_luong'] * (float)$data['don_gia'];
        $sql = "INSERT INTO phan_bo_dich_vu (lich_id, ncc_id, loai_dich_vu, chi_tiet, don_gia, so_luong, thanh_tien, ghi_chu) 
                VALUES (:lid, :ncc, :loai, :ct, :gia, :sl, :tt, :gc)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':lid'  => $data['lich_id'],
            ':ncc'  => $data['ncc_id'],
            ':loai' => $data['loai_dich_vu'],
            ':ct'   => $data['chi_tiet'],
            ':gia'  => $data['don_gia'],
            ':sl'   => $data['so_luong'],
            ':tt'   => $thanh_tien,
            ':gc'   => $data['ghi_chu'] ?? ''
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM phan_bo_dich_vu WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>