<?php
class LichTrinhModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách theo tour
    public function getByTour($tour_id) {
        $sql = "SELECT * FROM lich_trinh_tour WHERE tour_id = :id ORDER BY ngay_thu ASC, gio_bat_dau ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // [CẬP NHẬT] Hàm Insert đầy đủ cột
    public function insert($data) {
        $sql = "INSERT INTO lich_trinh_tour (
                    tour_id, ngay_thu, thu_tu_ngay, tieu_de, dia_diem, 
                    noi_dung, hoat_dong, gio_bat_dau, gio_ket_thuc, ghi_chu
                ) VALUES (
                    :tour_id, :ngay_thu, :thu_tu_ngay, :tieu_de, :dia_diem, 
                    :noi_dung, :hoat_dong, :gio_bat_dau, :gio_ket_thuc, :ghi_chu
                )";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tour_id'      => $data['tour_id'],
            ':ngay_thu'     => $data['ngay_thu'],
            ':thu_tu_ngay'  => $data['ngay_thu'], // Mặc định sắp xếp theo ngày
            ':tieu_de'      => $data['tieu_de'],
            ':dia_diem'     => $data['dia_diem'] ?? null,
            ':noi_dung'     => $data['noi_dung'] ?? null,
            ':hoat_dong'    => $data['noi_dung'] ?? null, // Lưu giống nội dung nếu không tách biệt
            ':gio_bat_dau'  => !empty($data['gio_bat_dau']) ? $data['gio_bat_dau'] : null,
            ':gio_ket_thuc' => !empty($data['gio_ket_thuc']) ? $data['gio_ket_thuc'] : null,
            ':ghi_chu'      => $data['ghi_chu'] ?? null
        ]);
    }

    // Xóa
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM lich_trinh_tour WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>