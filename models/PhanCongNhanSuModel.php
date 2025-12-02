<?php
class PhanCongNhanSuModel {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function getByLich($lich_id) {
        $sql = "SELECT * FROM phan_cong_nhan_su WHERE lich_id = :id ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $sql = "INSERT INTO phan_cong_nhan_su (lich_id, ho_ten, vai_tro, so_dien_thoai, ghi_chu) 
                VALUES (:lid, :ten, :vt, :sdt, :gc)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':lid' => $data['lich_id'],
            ':ten' => $data['ho_ten'],
            ':vt'  => $data['vai_tro'],
            ':sdt' => $data['so_dien_thoai'] ?? '',
            ':gc'  => $data['ghi_chu'] ?? ''
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM phan_cong_nhan_su WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>