<?php
// models/DiemDanhModel.php
require_once "BaseModel.php";

class DiemDanhModel extends BaseModel
{
    protected $table = "diem_danh_khach";

    public function addCheckin($lich_id, $khach_id, $trang_thai, $hinh_anh, $ten_khach = null, $ten_tour = null)
    {
        $sql = "INSERT INTO {$this->table}
                (lich_id, khach_id, trang_thai, hinh_anh, thoi_gian, ten_khach, ten_tour)
                VALUES (:lich_id, :khach_id, :trang_thai, :hinh_anh, NOW(), :ten_khach, :ten_tour)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':lich_id' => $lich_id,
            ':khach_id' => $khach_id,
            ':trang_thai' => $trang_thai, // ⭐ dữ liệu từ form
            ':hinh_anh' => $hinh_anh,
            ':ten_khach' => $ten_khach,
            ':ten_tour' => $ten_tour
        ]);
    }

    public function getAllDetailed()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
