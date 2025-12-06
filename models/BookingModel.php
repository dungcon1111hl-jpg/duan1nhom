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
    
    // Hàm lấy đầy đủ thông tin (hỗ trợ điểm danh)
    public function getAllFull() {
        $sql = "SELECT b.*, 
                       t.ten_tour AS ten_tour,
                       kh.ho_ten AS khach_ho_ten
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.id
                WHERE b.lich_id IS NOT NULL 
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

    // Thêm mới Booking
    public function insert($data) {
        // Tính tổng số khách
        $sl_nl = isset($data['so_luong_nguoi_lon']) ? (int)$data['so_luong_nguoi_lon'] : 0;
        $sl_te = isset($data['so_luong_tre_em']) ? (int)$data['so_luong_tre_em'] : 0;
        $sl_eb = isset($data['so_luong_em_be']) ? (int)$data['so_luong_em_be'] : 0;
        $tong_khach = $sl_nl + $sl_te + $sl_eb;

        $sql = "INSERT INTO booking (
                    tour_id, snapshot_ten_tour, khach_hang_id,
                    so_luong_nguoi_lon, so_luong_tre_em, so_luong_em_be, so_luong_khach, 
                    loai_booking, 
                    snapshot_kh_ho_ten, snapshot_kh_email, snapshot_kh_so_dien_thoai, snapshot_kh_dia_chi,
                    ngay_dat, tong_tien, da_thanh_toan, trang_thai
                ) VALUES (
                    :tour_id, :snapshot_ten_tour, :khach_hang_id,
                    :so_luong_nguoi_lon, :so_luong_tre_em, :so_luong_em_be, :so_luong_khach,
                    :loai_booking, 
                    :snapshot_kh_ho_ten, :snapshot_kh_email, :snapshot_kh_so_dien_thoai, :snapshot_kh_dia_chi,
                    :ngay_dat, :tong_tien, :da_thanh_toan, :trang_thai
                )";

        $stmt = $this->conn->prepare($sql);
        
        $params = [
            ':tour_id' => $data['tour_id'] ?? null,
            ':snapshot_ten_tour' => $data['snapshot_ten_tour'] ?? null,
            ':khach_hang_id' => $data['khach_hang_id'] ?? null,
            ':so_luong_nguoi_lon' => $sl_nl,
            ':so_luong_tre_em' => $sl_te,
            ':so_luong_em_be' => $sl_eb,
            ':so_luong_khach' => $tong_khach,
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
        
        if ($stmt->execute($params)) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật Booking
    public function update($id, $data) {
        // Tính lại tổng số khách
        $sl_nl = isset($data['so_luong_nguoi_lon']) ? (int)$data['so_luong_nguoi_lon'] : 0;
        $sl_te = isset($data['so_luong_tre_em']) ? (int)$data['so_luong_tre_em'] : 0;
        $sl_eb = isset($data['so_luong_em_be']) ? (int)$data['so_luong_em_be'] : 0;
        $tong_khach = $sl_nl + $sl_te + $sl_eb;

        $sql = "UPDATE booking SET 
                    tour_id = :tour_id,
                    snapshot_ten_tour = :snapshot_ten_tour,
                    khach_hang_id = :khach_hang_id,
                    so_luong_nguoi_lon = :so_luong_nguoi_lon,
                    so_luong_tre_em = :so_luong_tre_em,
                    so_luong_em_be = :so_luong_em_be,
                    so_luong_khach = :so_luong_khach,
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
        
        $params = [
            ':tour_id' => $data['tour_id'] ?? null,
            ':snapshot_ten_tour' => $data['snapshot_ten_tour'] ?? 'Chưa xác định',
            ':khach_hang_id' => $data['khach_hang_id'] ?? null,
            ':so_luong_nguoi_lon' => $sl_nl,
            ':so_luong_tre_em' => $sl_te,
            ':so_luong_em_be' => $sl_eb,
            ':so_luong_khach' => $tong_khach, 
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

    // Xóa
    public function delete($id) {
        $sql = "DELETE FROM booking WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Đồng bộ số lượng khách từ danh sách chi tiết vào bảng Booking
    public function syncBookingStats($booking_id) {
        // 1. Lấy danh sách khách
        $sqlCount = "SELECT * FROM khach_tham_gia WHERE booking_id = :bid";
        $stmt = $this->conn->prepare($sqlCount);
        $stmt->execute([':bid' => $booking_id]);
        $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = count($guests);
        $countTE = 0;
        $countNL = 0;

        // 2. Phân loại theo năm sinh (< 12 tuổi là TE)
        foreach ($guests as $g) {
            $age = !empty($g['ngay_sinh']) ? (date('Y') - date('Y', strtotime($g['ngay_sinh']))) : 13;
            if ($age < 12) {
                $countTE++;
            } else {
                $countNL++;
            }
        }
        
        // 3. Cập nhật lại vào bảng Booking
        $sqlUpdate = "UPDATE booking SET 
                        so_luong_nguoi_lon = :nl, 
                        so_luong_tre_em = :te,
                        so_luong_khach = :tong 
                      WHERE id = :bid";
        
        $stmtUp = $this->conn->prepare($sqlUpdate);
        return $stmtUp->execute([
            ':nl' => $countNL,
            ':te' => $countTE,
            ':tong' => $total,
            ':bid' => $booking_id
        ]);
    }
}
?>  