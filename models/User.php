<?php
class User {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // 1. Đăng nhập (Dùng mật khẩu thường)
    public function checkLogin($username, $password) {
        try {
            $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['username' => $username, 'email' => $username]);
            $user = $stmt->fetch();

            // [SỬA] So sánh trực tiếp, không dùng password_verify
            if ($user && $user['password_hash'] === $password) {
                return $user;
            }
            
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // 2. Lấy danh sách
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Lấy 1 người dùng
    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Thêm mới (Lưu mật khẩu thường)
    public function insert($data) {
        // [SỬA] Không dùng password_hash nữa
        $password = $data['password']; 

        $sql = "INSERT INTO users (username, password_hash, full_name, email, so_dien_thoai, role, trang_thai) 
                VALUES (:user, :pass, :name, :email, :sdt, :role, 1)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user'  => $data['username'],
            ':pass'  => $password, // Lưu trực tiếp
            ':name'  => $data['full_name'],
            ':email' => $data['email'],
            ':sdt'   => $data['so_dien_thoai'] ?? null,
            ':role'  => $data['role']
        ]);
    }

    // 5. Cập nhật (Lưu mật khẩu thường)
    public function update($id, $data) {
        if (!empty($data['password'])) {
            // [SỬA] Không mã hóa mật khẩu mới
            $password = $data['password'];
            
            $sql = "UPDATE users SET full_name=:name, email=:email, so_dien_thoai=:sdt, role=:role, password_hash=:pass WHERE id=:id";
            $params = [
                ':name' => $data['full_name'], ':email' => $data['email'], 
                ':sdt' => $data['so_dien_thoai'], ':role' => $data['role'], 
                ':pass' => $password, // Lưu trực tiếp
                ':id' => $id
            ];
        } else {
            $sql = "UPDATE users SET full_name=:name, email=:email, so_dien_thoai=:sdt, role=:role WHERE id=:id";
            $params = [
                ':name' => $data['full_name'], ':email' => $data['email'], 
                ':sdt' => $data['so_dien_thoai'], ':role' => $data['role'], 
                ':id' => $id
            ];
        }
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>