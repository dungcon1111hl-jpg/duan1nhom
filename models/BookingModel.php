<?php
class BookingModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy danh sách booking
    public function getAll() {
        $sql = "SELECT b.*, 
                       t.ten_tour AS ten_tour_hien_tai,
                       kh.ho_ten AS ten_khach_hang,
                       kh.so_dien_thoai AS sdt_khach_hang
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.id
                ORDER BY b.ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 booking
    public function getOne($id) {
        $sql = "SELECT b.*, 
                       t.ten_tour AS ten_tour_hien_tai,
                       kh.ho_ten AS ten_khach_hang,
                       kh.so_dien_thoai AS sdt_khach_hang
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.id
                WHERE b.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới booking (Đã bỏ 'so_luong_khach' để sửa lỗi)
    public function insert($data) {
        $sql = "INSERT INTO booking (
                    tour_id, snapshot_ten_tour, khach_hang_id,
                    so_luong_nguoi_lon, so_luong_tre_em, loai_booking, 
                    snapshot_kh_ho_ten, snapshot_kh_email, snapshot_kh_so_dien_thoai, snapshot_kh_dia_chi,
                    ngay_dat, tong_tien, da_thanh_toan, trang_thai
                ) VALUES (
                    :tour_id, :snapshot_ten_tour, :khach_hang_id,
                    :so_luong_nguoi_lon, :so_luong_tre_em, :loai_booking, 
                    :snapshot_kh_ho_ten, :snapshot_kh_email, :snapshot_kh_so_dien_thoai, :snapshot_kh_dia_chi,
                    :ngay_dat, :tong_tien, :da_thanh_toan, :trang_thai
                )";

        $stmt = $this->conn->prepare($sql);
        
        // Lưu ý: Đã xóa dòng so_luong_khach trong mảng params
        $params = [
            ':tour_id' => $data['tour_id'] ?? null,
            ':snapshot_ten_tour' => $data['snapshot_ten_tour'] ?? null,
            ':khach_hang_id' => $data['khach_hang_id'] ?? null,
            ':so_luong_nguoi_lon' => $data['so_luong_nguoi_lon'] ?? 0,
            ':so_luong_tre_em' => $data['so_luong_tre_em'] ?? 0,
            ':loai_booking' => $data['loai_booking'] ?? 'DOAN',
            
            ':snapshot_kh_ho_ten' => $data['snapshot_kh_ho_ten'] ?? '',
            ':snapshot_kh_email' => $data['snapshot_kh_email'] ?? '',
            ':snapshot_kh_so_dien_thoai' => $data['snapshot_kh_so_dien_thoai'] ?? '',
            ':snapshot_kh_dia_chi' => $data['snapshot_kh_dia_chi'] ?? '',
            
            ':ngay_dat' => $data['ngay_dat'] ?? date('Y-m-d H:i:s'),
            ':tong_tien' => $data['tong_tien'] ?? 0,
            ':da_thanh_toan' => $data['da_thanh_toan'] ?? 0,
            ':trang_thai' => $data['trang_thai'] ?? 'CHO_XU_LY'
        ];
        
        return $stmt->execute($params);
    }

    // Cập nhật booking (Đã bỏ 'so_luong_khach' để sửa lỗi)
    public function update($id, $data) {
        $sql = "UPDATE booking SET 
                    tour_id = :tour_id,
                    snapshot_ten_tour = :snapshot_ten_tour,
                    khach_hang_id = :khach_hang_id,
                    so_luong_nguoi_lon = :so_luong_nguoi_lon,
                    so_luong_tre_em = :so_luong_tre_em,
                    loai_booking = :loai_booking,
                    snapshot_kh_ho_ten = :snapshot_kh_ho_ten,
                    snapshot_kh_email = :snapshot_kh_email,
                    snapshot_kh_so_dien_thoai = :snapshot_kh_so_dien_thoai,
                    snapshot_kh_dia_chi = :snapshot_kh_dia_chi,
                    tong_tien = :tong_tien,
                    da_thanh_toan = :da_thanh_toan,
                    trang_thai = :trang_thai
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        
        // Lưu ý: Đã xóa dòng so_luong_khach trong mảng params
        $params = [
            ':tour_id' => $data['tour_id'] ?? null,
            ':snapshot_ten_tour' => $data['snapshot_ten_tour'] ?? 'Chưa xác định',
            ':khach_hang_id' => $data['khach_hang_id'] ?? null,
            ':so_luong_nguoi_lon' => $data['so_luong_nguoi_lon'] ?? 0,
            ':so_luong_tre_em' => $data['so_luong_tre_em'] ?? 0,
            ':loai_booking' => $data['loai_booking'] ?? 'DOAN',
            
            ':snapshot_kh_ho_ten' => $data['snapshot_kh_ho_ten'] ?? '',
            ':snapshot_kh_email' => $data['snapshot_kh_email'] ?? '',
            ':snapshot_kh_so_dien_thoai' => $data['snapshot_kh_so_dien_thoai'] ?? '',
            ':snapshot_kh_dia_chi' => $data['snapshot_kh_dia_chi'] ?? '',
            
            ':tong_tien' => $data['tong_tien'] ?? 0,
            ':da_thanh_toan' => $data['da_thanh_toan'] ?? 0,
            ':trang_thai' => $data['trang_thai'] ?? 'CHO_XU_LY',
            ':id' => $id
        ];
        
        return $stmt->execute($params);
    }

    // Cập nhật trạng thái nhanh
    public function updateStatus($id, $status) {
        $sql = "UPDATE booking SET trang_thai = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    // Xóa booking
    public function delete($id) {
        $sql = "DELETE FROM booking WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>