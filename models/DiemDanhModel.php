<?php
// models/DiemDanhModel.php
// Đã loại bỏ require_once "BaseModel.php" do file này bị thiếu

class DiemDanhModel 
{
    protected $conn; 
    protected $table = "diem_danh_khach"; 

    public function __construct()
    {
        // Khởi tạo kết nối DB trực tiếp (giả định hàm connectDB() có sẵn)
        $this->conn = connectDB(); 
    }

    /**
     * Hàm Upsert (Insert/Update) trạng thái điểm danh.
     * Kiểm tra bản ghi cuối cùng của khách/lịch, nếu có thì Update, nếu không thì Insert.
     */
    public function saveOrUpdateCheckin($lich_id, $khach_id, $trang_thai, $ten_khach, $ten_tour, $hinh_anh)
    {
        // 1. Kiểm tra xem đã có bản ghi nào chưa (Dùng ID của khách và ID lịch)
        $sql_check = "SELECT id FROM {$this->table} 
                      WHERE lich_id = :lich_id AND khach_id = :khach_id 
                      ORDER BY thoi_gian DESC LIMIT 1";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->execute([':lich_id' => $lich_id, ':khach_id' => $khach_id]);
        $existing_id = $stmt_check->fetchColumn();

        // Định nghĩa các tham số cơ bản
        $base_params = [
            ':lich_id' => $lich_id,
            ':khach_id' => $khach_id,
            ':trang_thai' => $trang_thai,
            ':hinh_anh' => $hinh_anh, 
            ':ten_khach' => $ten_khach,
            ':ten_tour' => $ten_tour
        ];
        
        if ($existing_id) {
            // 2. Cập nhật bản ghi hiện tại
            $sql = "UPDATE {$this->table} SET
                    trang_thai = :trang_thai,
                    hinh_anh = IFNULL(:hinh_anh, hinh_anh), -- Giữ ảnh cũ nếu không có ảnh mới
                    ten_khach = :ten_khach,
                    ten_tour = :ten_tour,
                    thoi_gian = NOW()
                    WHERE id = :id";
            
            // Chỉ truyền các tham số cần thiết cho UPDATE
            $execute_params = [
                ':trang_thai' => $base_params[':trang_thai'],
                ':hinh_anh' => $base_params[':hinh_anh'],
                ':ten_khach' => $base_params[':ten_khach'],
                ':ten_tour' => $base_params[':ten_tour'],
                ':id' => $existing_id
            ];
            
        } else {
            // 3. Thêm bản ghi mới
            $sql = "INSERT INTO {$this->table}
                    (lich_id, khach_id, trang_thai, hinh_anh, thoi_gian, ten_khach, ten_tour)
                    VALUES (:lich_id, :khach_id, :trang_thai, :hinh_anh, NOW(), :ten_khach, :ten_tour)";

            // Sử dụng toàn bộ tham số cho INSERT
            $execute_params = $base_params;
        }
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($execute_params); 
    }
    
    public function getAllDetailed()
    {
        // Khai báo rõ ràng các cột đã được thêm (hinh_anh, ten_khach, ten_tour)
        $sql = "SELECT id, lich_id, khach_id, trang_thai, thoi_gian,
                       COALESCE(hinh_anh, NULL) as hinh_anh,
                       COALESCE(ten_khach, NULL) as ten_khach,
                       COALESCE(ten_tour, NULL) as ten_tour
                FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>