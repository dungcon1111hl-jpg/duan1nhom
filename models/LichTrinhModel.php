<?php

class LichTrinhModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách lịch trình của 1 tour
    public function getByTour($tour_id) {
        $sql = "SELECT * FROM lich_trinh_tour 
                WHERE tour_id = :tour_id 
                ORDER BY thu_tu_ngay ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy một lịch trình
    public function getOne($id) {
        $sql = "SELECT * FROM lich_trinh_tour WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm lịch trình
    public function insert($tour_id, $thu_tu_ngay, $tieu_de, $hoat_dong, $gio_bat_dau, $gio_ket_thuc, $ghi_chu) {

        $sql = "INSERT INTO lich_trinh_tour 
                (tour_id, thu_tu_ngay, tieu_de, hoat_dong, gio_bat_dau, gio_ket_thuc, ghi_chu)
                VALUES 
                (:tour_id, :thu_tu_ngay, :tieu_de, :hoat_dong, :gio_bat_dau, :gio_ket_thuc, :ghi_chu)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':tour_id'      => $tour_id,
            ':thu_tu_ngay'  => $thu_tu_ngay,
            ':tieu_de'      => $tieu_de,
            ':hoat_dong'    => $hoat_dong,
            ':gio_bat_dau'  => $gio_bat_dau,
            ':gio_ket_thuc' => $gio_ket_thuc,
            ':ghi_chu'      => $ghi_chu
        ]);
    }

    // Cập nhật lịch trình
    public function update($id, $tour_id, $thu_tu_ngay, $tieu_de, $hoat_dong, $gio_bat_dau, $gio_ket_thuc, $ghi_chu) {

        $sql = "UPDATE lich_trinh_tour SET
                    tour_id = :tour_id,
                    thu_tu_ngay = :thu_tu_ngay,
                    tieu_de = :tieu_de,
                    hoat_dong = :hoat_dong,
                    gio_bat_dau = :gio_bat_dau,
                    gio_ket_thuc = :gio_ket_thuc,
                    ghi_chu = :ghi_chu
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id'           => $id,
            ':tour_id'      => $tour_id,
            ':thu_tu_ngay'  => $thu_tu_ngay,
            ':tieu_de'      => $tieu_de,
            ':hoat_dong'    => $hoat_dong,
            ':gio_bat_dau'  => $gio_bat_dau,
            ':gio_ket_thuc' => $gio_ket_thuc,
            ':ghi_chu'      => $ghi_chu
        ]);
    }

    // Xóa lịch trình
    public function delete($id) {
        $sql = "DELETE FROM lich_trinh_tour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

