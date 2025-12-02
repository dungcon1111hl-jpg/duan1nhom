<?php
class DanhMucTourModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM danh_muc_tour ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM danh_muc_tour WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // [MỚI] Hàm kiểm tra mã danh mục đã tồn tại chưa
    public function checkExists($ma_danh_muc) {
        $stmt = $this->conn->prepare("SELECT count(*) FROM danh_muc_tour WHERE ma_danh_muc = :ma");
        $stmt->execute([':ma' => $ma_danh_muc]);
        return $stmt->fetchColumn() > 0; // Trả về true nếu đã có
    }

    public function insert($data) {
        $sql = "INSERT INTO danh_muc_tour (ten_danh_muc, ma_danh_muc, mo_ta) VALUES (:ten, :ma, :mo_ta)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ten' => $data['ten_danh_muc'],
            ':ma' => $data['ma_danh_muc'], // Nếu trùng ở đây sẽ báo lỗi, nên cần check trước ở Controller
            ':mo_ta' => $data['mo_ta'] ?? null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE danh_muc_tour SET ten_danh_muc = :ten, mo_ta = :mo_ta WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':ten' => $data['ten_danh_muc'],
            ':mo_ta' => $data['mo_ta'] ?? null
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM danh_muc_tour WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>