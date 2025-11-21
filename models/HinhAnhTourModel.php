<?php

class HinhAnhTourModel {

    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // Lấy tất cả ảnh của 1 tour
    public function getByTour(int $tour_id): array {
        $sql = "SELECT * FROM hinh_anh_tour 
                WHERE tour_id = :tour_id 
                ORDER BY thu_tu_hien_thi ASC, id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 ảnh theo id
    public function getOne(int $id): ?array {
        $sql = "SELECT * FROM hinh_anh_tour WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // Thêm mới ảnh
    public function insert(int $tour_id, string $duong_dan, string $mo_ta_anh = '', int $thu_tu_hien_thi = null): bool {
        // Nếu không truyền thứ tự, set = max+1
        if ($thu_tu_hien_thi === null) {
            $sqlOrder = "SELECT COALESCE(MAX(thu_tu_hien_thi), 0) + 1 AS next_order 
                         FROM hinh_anh_tour WHERE tour_id = :tour_id";
            $stmtOrder = $this->conn->prepare($sqlOrder);
            $stmtOrder->execute([':tour_id' => $tour_id]);
            $thu_tu_hien_thi = (int)$stmtOrder->fetchColumn();
        }

        $sql = "INSERT INTO hinh_anh_tour (tour_id, duong_dan, mo_ta_anh, thu_tu_hien_thi)
                VALUES (:tour_id, :duong_dan, :mo_ta_anh, :thu_tu_hien_thi)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':tour_id'          => $tour_id,
            ':duong_dan'        => $duong_dan,
            ':mo_ta_anh'        => $mo_ta_anh,
            ':thu_tu_hien_thi'  => $thu_tu_hien_thi
        ]);
    }

    // Cập nhật ảnh
    public function update(int $id, string $mo_ta_anh, int $thu_tu_hien_thi, ?string $duong_dan_moi = null): bool {
        if ($duong_dan_moi === null) {
            $sql = "UPDATE hinh_anh_tour 
                    SET mo_ta_anh = :mo_ta_anh,
                        thu_tu_hien_thi = :thu_tu_hien_thi
                    WHERE id = :id";
            $params = [
                ':id'               => $id,
                ':mo_ta_anh'        => $mo_ta_anh,
                ':thu_tu_hien_thi'  => $thu_tu_hien_thi
            ];
        } else {
            $sql = "UPDATE hinh_anh_tour 
                    SET mo_ta_anh = :mo_ta_anh,
                        thu_tu_hien_thi = :thu_tu_hien_thi,
                        duong_dan = :duong_dan
                    WHERE id = :id";
            $params = [
                ':id'               => $id,
                ':mo_ta_anh'        => $mo_ta_anh,
                ':thu_tu_hien_thi'  => $thu_tu_hien_thi,
                ':duong_dan'        => $duong_dan_moi
            ];
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Xóa ảnh
    public function delete(int $id): bool {
        $sql = "DELETE FROM hinh_anh_tour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
