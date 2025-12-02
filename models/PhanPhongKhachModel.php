<?php
require_once "BaseModel.php";

class PhanPhongModel extends BaseModel
{
    protected $table = "phan_phong_khach";

    public function getAll() {
        return $this->queryAll("SELECT * FROM {$this->table}");
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
            (lich_id, khach_id, loai_phong, so_nguoi, so_phong, ghi_chu)
            VALUES
            (:lich_id, :khach_id, :loai_phong, :so_nguoi, :so_phong, :ghi_chu)";
        return $this->insert($sql, $data);
    }
}
