<?php
class ChiPhiModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByLich($lich_id) {
        $stmt = $this->conn->prepare("SELECT * FROM chi_phi_khac WHERE lich_id = ? ORDER BY ngay_chi DESC");
        $stmt->execute([$lich_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM chi_phi_khac WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insert không cần truyền 'id' vì DB đã tự tăng (AUTO_INCREMENT)
    public function insert($data) {
        $sql = "INSERT INTO chi_phi_khac (lich_id, ten_chi_phi, loai_chi_phi, so_tien, hinh_anh, ghi_chu, ngay_chi) 
                VALUES (:lid, :ten, :loai, :tien, :img, :gc, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':lid'  => $data['lich_id'],
            ':ten'  => $data['ten_chi_phi'],
            ':loai' => $data['loai_chi_phi'],
            ':tien' => $data['so_tien'],
            ':img'  => $data['hinh_anh'] ?? null,
            ':gc'   => $data['ghi_chu'] ?? ''
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE chi_phi_khac SET 
                    ten_chi_phi = :ten,
                    loai_chi_phi = :loai,
                    so_tien = :tien,
                    ghi_chu = :gc
                    " . (isset($data['hinh_anh']) ? ", hinh_anh = :img" : "") . "
                WHERE id = :id";
        
        $params = [
            ':ten'  => $data['ten_chi_phi'],
            ':loai' => $data['loai_chi_phi'],
            ':tien' => $data['so_tien'],
            ':gc'   => $data['ghi_chu'],
            ':id'   => $id
        ];

        if (isset($data['hinh_anh'])) {
            $params[':img'] = $data['hinh_anh'];
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM chi_phi_khac WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getTotalCost($lich_id) {
        $stmt = $this->conn->prepare("SELECT SUM(so_tien) FROM chi_phi_khac WHERE lich_id = ?");
        $stmt->execute([$lich_id]);
        return $stmt->fetchColumn() ?: 0;
    }
}
?>