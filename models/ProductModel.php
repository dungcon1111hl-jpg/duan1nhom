<?php 
// Có class chứa các function thực thi tương tác với cơ sở dữ liệu 
class ProductModel 
{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Viết truy vấn danh sách sản phẩm 
    public function getAllProduct()
    {
        
    }

    // Lấy danh sách tất cả tour
    public function getAllTours()
    {
        $sql = "SELECT * FROM tour ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Thêm tour mới
    public function createTour($data)
    {
        $sql = "INSERT INTO tour (ma_tour, ten_tour, loai_tour, mo_ta, chinh_sach, ngay_tao, ngay_cap_nhat)
                VALUES (:ma_tour, :ten_tour, :loai_tour, :mo_ta, :chinh_sach, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':ma_tour'    => $data['ma_tour'] ?? null,
            ':ten_tour'   => $data['ten_tour'] ?? null,
            ':loai_tour'  => $data['loai_tour'] ?? null,
            ':mo_ta'      => $data['mo_ta'] ?? null,
            ':chinh_sach' => $data['chinh_sach'] ?? null,
        ]);
        return (int)$this->conn->lastInsertId();
    }

    // Xóa tour theo id
    public function deleteTour($id)
    {
        $sql = "DELETE FROM tour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
