<?php
class NhaCungCapModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM nha_cung_cap ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM nha_cung_cap WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insert không cần truyền 'id' vì Database đã tự động tăng (AUTO_INCREMENT)
    public function insert($data) {
        $sql = "INSERT INTO nha_cung_cap (ten_don_vi, loai_dich_vu, nguoi_lien_he, so_dien_thoai, email, dia_chi, ghi_chu) 
                VALUES (:ten, :loai, :nlh, :sdt, :email, :dc, :gc)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ten'   => $data['ten_don_vi'],
            ':loai'  => $data['loai_dich_vu'],
            ':nlh'   => $data['nguoi_lien_he'] ?? null,
            ':sdt'   => $data['so_dien_thoai'],
            ':email' => $data['email'] ?? null,
            ':dc'    => $data['dia_chi'] ?? null,
            ':gc'    => $data['ghi_chu'] ?? null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE nha_cung_cap SET 
                    ten_don_vi = :ten, 
                    loai_dich_vu = :loai, 
                    nguoi_lien_he = :nlh,
                    so_dien_thoai = :sdt, 
                    email = :email, 
                    dia_chi = :dc, 
                    ghi_chu = :gc 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'    => $id,
            ':ten'   => $data['ten_don_vi'],
            ':loai'  => $data['loai_dich_vu'],
            ':nlh'   => $data['nguoi_lien_he'] ?? null,
            ':sdt'   => $data['so_dien_thoai'],
            ':email' => $data['email'] ?? null,
            ':dc'    => $data['dia_chi'] ?? null,
            ':gc'    => $data['ghi_chu'] ?? null
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM nha_cung_cap WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>