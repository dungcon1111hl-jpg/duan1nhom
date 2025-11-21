<?php

class TourModel {

    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function buildData(array $data): array
    {
        $soLuongVe = (int)($data['so_luong_ve'] ?? 0);
        $soVeConLai = (int)($data['so_ve_con_lai'] ?? $soLuongVe);

        return [
            ':ma_tour'        => $data['ma_tour'] ?? null,
            ':ten_tour'       => $data['ten_tour'] ?? null,
            ':mo_ta_ngan'     => $data['mo_ta_ngan'] ?? null,
            ':dia_bd'         => $data['dia_diem_bat_dau'] ?? null,
            ':dia_kt'         => $data['dia_diem_ket_thuc'] ?? null,
            ':ngay_bd'        => $data['ngay_khoi_hanh'] ?? null,
            ':ngay_kt'        => $data['ngay_ket_thuc'] ?? null,
            ':gia_tour'       => $data['gia_tour'] ?? null,
            ':mo_ta_ct'       => $data['mo_ta_chi_tiet'] ?? null,
            ':anh'            => $data['anh_minh_hoa'] ?? null,
            ':sl_ve'          => $soLuongVe,
            ':sl_con'         => $soVeConLai,
            ':trang_thai'     => $data['trang_thai'] ?? 'CON_VE',
            ':chinh_sach'     => $data['chinh_sach'] ?? null,
        ];
    }

    public function getOne($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tour WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(array $filters = []): PDOStatement
    {
        $sql = "SELECT * FROM tour WHERE 1=1";
        $conditions = [];
        $params = [];

        if (!empty($filters['ten_tour'])) {
            $conditions[] = "ten_tour LIKE :ten_tour";
            $params[':ten_tour'] = "%" . $filters['ten_tour'] . "%";
        }

        if (!empty($filters['dia_diem'])) {
            $conditions[] = "(dia_diem_bat_dau LIKE :dd OR dia_diem_ket_thuc LIKE :dd)";
            $params[':dd'] = "%" . $filters['dia_diem'] . "%";
        }

        if (!empty($filters['trang_thai'])) {
            $conditions[] = "trang_thai = :tt";
            $params[':tt'] = $filters['trang_thai'];
        }

        if (!empty($filters['ngay_from'])) {
            $conditions[] = "ngay_khoi_hanh >= :ngay_from";
            $params[':ngay_from'] = $filters['ngay_from'];
        }

        if (!empty($filters['ngay_to'])) {
            $conditions[] = "ngay_khoi_hanh <= :ngay_to";
            $params[':ngay_to'] = $filters['ngay_to'];
        }

        if ($conditions) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY ngay_khoi_hanh DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getById(int $id): ?array {
        return $this->getOne($id);
    }

    public function store(array $data): bool
    {
        $sql = "INSERT INTO tour (
                    ma_tour, ten_tour, mo_ta_ngan,
                    dia_diem_bat_dau, dia_diem_ket_thuc,
                    ngay_khoi_hanh, ngay_ket_thuc,
                    gia_tour, mo_ta_chi_tiet,
                    anh_minh_hoa, so_luong_ve, so_ve_con_lai,
                    trang_thai, chinh_sach
                ) VALUES (
                    :ma_tour, :ten_tour, :mo_ta_ngan,
                    :dia_bd, :dia_kt, :ngay_bd, :ngay_kt,
                    :gia_tour, :mo_ta_ct, :anh,
                    :sl_ve, :sl_con, :trang_thai, :chinh_sach
                )";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($this->buildData($data));
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE tour SET
                    ma_tour = :ma_tour,
                    ten_tour = :ten_tour,
                    mo_ta_ngan = :mo_ta_ngan,
                    dia_diem_bat_dau = :dia_bd,
                    dia_diem_ket_thuc = :dia_kt,
                    ngay_khoi_hanh = :ngay_bd,
                    ngay_ket_thuc = :ngay_kt,
                    gia_tour = :gia_tour,
                    mo_ta_chi_tiet = :mo_ta_ct,
                    anh_minh_hoa = :anh,
                    so_luong_ve = :sl_ve,
                    so_ve_con_lai = :sl_con,
                    trang_thai = :trang_thai,
                    chinh_sach = :chinh_sach
                WHERE id = :id";

        $params = $this->buildData($data);
        $params[':id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tour WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
