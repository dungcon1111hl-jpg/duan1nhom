<?php

class HuongDanVienModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function all() {
        $sql = "SELECT * FROM huong_dan_vien ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function getOne($id) {
        $sql = "SELECT * FROM huong_dan_vien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $sql = "INSERT INTO huong_dan_vien
                (ho_ten, ngay_sinh, so_dien_thoai, email, ngon_ngu, chung_chi,
                 kinh_nghiem, suc_khoe, anh_dai_dien, trang_thai)
                VALUES
                (:ho_ten, :ngay_sinh, :so_dien_thoai, :email, :ngon_ngu, :chung_chi,
                 :kinh_nghiem, :suc_khoe, :anh_dai_dien, :trang_thai)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $data['id'] = $id;

        $sql = "UPDATE huong_dan_vien SET
                    ho_ten = :ho_ten,
                    ngay_sinh = :ngay_sinh,
                    so_dien_thoai = :so_dien_thoai,
                    email = :email,
                    ngon_ngu = :ngon_ngu,
                    chung_chi = :chung_chi,
                    kinh_nghiem = :kinh_nghiem,
                    suc_khoe = :suc_khoe,
                    anh_dai_dien = :anh_dai_dien,
                    trang_thai = :trang_thai
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $sql = "DELETE FROM huong_dan_vien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

}
