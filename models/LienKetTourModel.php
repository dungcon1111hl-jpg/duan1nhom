<?php
class LienKetTourModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy danh sách NCC đã liên kết với Tour (Load cả thông tin Tour + NCC)
    public function getByTour($tour_id) {
        $sql = "SELECT lk.*, 
                       ncc.ten_don_vi, ncc.loai_dich_vu, ncc.so_dien_thoai,
                       t.ten_tour, t.ma_tour
                FROM lien_ket_tour_ncc lk
                JOIN nha_cung_cap ncc ON lk.ncc_id = ncc.id
                JOIN tour t ON lk.tour_id = t.id
                WHERE lk.tour_id = :tid
                ORDER BY lk.id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tid' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy chi tiết 1 liên kết
    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM lien_ket_tour_ncc WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Kiểm tra xem NCC đã được thêm vào Tour chưa
    public function checkExist($tour_id, $ncc_id) {
        $stmt = $this->conn->prepare("SELECT id FROM lien_ket_tour_ncc WHERE tour_id = :t AND ncc_id = :n");
        $stmt->execute([':t' => $tour_id, ':n' => $ncc_id]);
        return $stmt->fetchColumn();
    }

    // 4. Thêm mới / Cập nhật liên kết
    public function save($data) {
        // Nếu đã tồn tại -> Update, chưa -> Insert
        $existId = $this->checkExist($data['tour_id'], $data['ncc_id']);

        if ($existId) {
            $sql = "UPDATE lien_ket_tour_ncc SET 
                        gia_net = :gn, 
                        gia_ban = :gb, 
                        ghi_chu = :gc 
                    WHERE id = :id";
            $params = [
                ':gn' => $data['gia_net'], 
                ':gb' => $data['gia_ban'], 
                ':gc' => $data['ghi_chu'], 
                ':id' => $existId
            ];
        } else {
            $sql = "INSERT INTO lien_ket_tour_ncc (tour_id, ncc_id, gia_net, gia_ban, ghi_chu) 
                    VALUES (:t, :n, :gn, :gb, :gc)";
            $params = [
                ':t'  => $data['tour_id'], 
                ':n'  => $data['ncc_id'], 
                ':gn' => $data['gia_net'], 
                ':gb' => $data['gia_ban'], 
                ':gc' => $data['ghi_chu']
            ];
        }
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // 5. Xóa liên kết
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM lien_ket_tour_ncc WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>