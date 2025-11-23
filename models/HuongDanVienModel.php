<?php
class HuongDanVienModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách tất cả hướng dẫn viên
    public function getAll() { 
        $sql = "SELECT * FROM huong_dan_vien ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;  // Trả về PDOStatement hoặc array
    }

    // Thêm phương thức all() để giữ cách gọi của bạn
    public function all() {
        return $this->getAll();  // Gọi phương thức getAll() trong all()
    }

    // Lấy 1 người hướng dẫn viên
    public function getOne($id) {
        $sql = "SELECT * FROM huong_dan_vien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới hướng dẫn viên
    public function insert($data) {
        $sql = "INSERT INTO huong_dan_vien (
                    ho_ten, ngay_sinh, gioi_tinh, so_dien_thoai, email, dia_chi,
                    ngon_ngu, chung_chi, kinh_nghiem, anh_dai_dien, trang_thai
                ) VALUES (
                    :ho_ten, :ngay_sinh, :gioi_tinh, :sdt, :email, :dia_chi,
                    :ngon_ngu, :chung_chi, :kinh_nghiem, :anh, :trang_thai
                )";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([ 
            ':ho_ten'      => $data['ho_ten'],
            ':ngay_sinh'   => !empty($data['ngay_sinh']) ? $data['ngay_sinh'] : null,
            ':gioi_tinh'   => $data['gioi_tinh'] ?? 'Nam',
            ':sdt'         => $data['so_dien_thoai'],
            ':email'       => $data['email'] ?? null,
            ':dia_chi'     => $data['dia_chi'] ?? null,
            ':ngon_ngu'    => $data['ngon_ngu'] ?? null,
            ':chung_chi'   => $data['chung_chi'] ?? null,
            ':kinh_nghiem' => $data['kinh_nghiem'] ?? null,
            ':anh'         => $data['anh_dai_dien'] ?? null,
            ':trang_thai'  => $data['trang_thai'] ?? 'DANG_LAM_VIEC'
        ]);
    }

    // Cập nhật thông tin hướng dẫn viên
    public function update($id, $data) {
        $sql = "UPDATE huong_dan_vien SET 
                    ho_ten = :ho_ten,
                    ngay_sinh = :ngay_sinh,
                    gioi_tinh = :gioi_tinh,
                    so_dien_thoai = :sdt,
                    email = :email,
                    dia_chi = :dia_chi,
                    ngon_ngu = :ngon_ngu,
                    chung_chi = :chung_chi,
                    kinh_nghiem = :kinh_nghiem,
                    anh_dai_dien = :anh,
                    trang_thai = :trang_thai
                WHERE id = :id";
        
        $params = [
            ':id'          => $id,
            ':ho_ten'      => $data['ho_ten'],
            ':ngay_sinh'   => !empty($data['ngay_sinh']) ? $data['ngay_sinh'] : null,
            ':gioi_tinh'   => $data['gioi_tinh'],
            ':sdt'         => $data['so_dien_thoai'],
            ':email'       => $data['email'],
            ':dia_chi'     => $data['dia_chi'],
            ':ngon_ngu'    => $data['ngon_ngu'],
            ':chung_chi'   => $data['chung_chi'],
            ':kinh_nghiem' => $data['kinh_nghiem'],
            ':anh'         => $data['anh_dai_dien'],
            ':trang_thai'  => $data['trang_thai']
        ];
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Xóa hướng dẫn viên
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM huong_dan_vien WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
