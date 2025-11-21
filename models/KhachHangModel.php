<?php

class KhachHangModel 
{
    private $db; // PDO instance

    public function __construct($db) {
        $this->db = $db;
    }

    /* =============================
        LẤY TẤT CẢ KHÁCH HÀNG
    ============================== */
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM khach_hang ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =============================
        LẤY THEO ID
    ============================== */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM khach_hang WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =============================
        CHECK TRÙNG PHONE
    ============================== */
    public function existsByPhone($phone) {
        $stmt = $this->db->prepare(
            "SELECT id FROM khach_hang WHERE so_dien_thoai = ?"
        );
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =============================
        CHECK TRÙNG EMAIL
    ============================== */
    public function existsByEmail($email) {
        $stmt = $this->db->prepare(
            "SELECT id FROM khach_hang WHERE email = ?"
        );
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =============================
        CREATE
    ============================== */
    public function create($data) {

        // Validate cơ bản
        if (empty($data['ho_ten']) || empty($data['so_dien_thoai'])) {
            throw new Exception("Họ tên và số điện thoại là bắt buộc.");
        }

        // Check trùng SĐT
        if ($this->existsByPhone($data['so_dien_thoai'])) {
            throw new Exception("Số điện thoại đã tồn tại!");
        }

        // Check trùng email
        if (!empty($data['email']) && $this->existsByEmail($data['email'])) {
            throw new Exception("Email đã tồn tại!");
        }

        $stmt = $this->db->prepare("
            INSERT INTO khach_hang 
                (ho_ten, gioi_tinh, ngay_sinh, email, so_dien_thoai, dia_chi)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['ho_ten'],
            $data['gioi_tinh'] ?? null,
            $data['ngay_sinh'] ?? null,
            $data['email'] ?? null,
            $data['so_dien_thoai'],
            $data['dia_chi'] ?? null
        ]);
    }

    /* =============================
        UPDATE
    ============================== */
    public function update($id, $data) {

        if (!$this->getById($id)) {
            throw new Exception("Khách hàng không tồn tại!");
        }

        $stmt = $this->db->prepare("
            UPDATE khach_hang SET
                ho_ten = ?, 
                gioi_tinh = ?, 
                ngay_sinh = ?, 
                email = ?, 
                so_dien_thoai = ?, 
                dia_chi = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['ho_ten'],
            $data['gioi_tinh'],
            $data['ngay_sinh'],
            $data['email'],
            $data['so_dien_thoai'],
            $data['dia_chi'],
            $id
        ]);
    }

    /* =============================
        DELETE THẬT (không soft delete)
    ============================== */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM khach_hang WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /* =============================
        SEARCH
    ============================== */
    public function search($keyword) {
        $keyword = "%$keyword%";

        $stmt = $this->db->prepare("
            SELECT * FROM khach_hang
            WHERE ho_ten LIKE ? 
               OR so_dien_thoai LIKE ?
               OR email LIKE ?
        ");

        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
