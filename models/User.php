<?php
class User {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // 1. Kiểm tra đăng nhập
    public function checkLogin($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username, ':email' => $username]);
        $user = $stmt->fetch();

        // So sánh mật khẩu trực tiếp (không mã hóa)
        if ($user && $user['password_hash'] === $password) {
            if ($user['trang_thai'] == 0) {
                return "LOCKED"; // Tài khoản bị khóa
            }
            return $user;
        }
        return false;
    }

    // 2. Lấy danh sách tất cả user
    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Lấy thông tin 1 user
    public function getOne($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Thêm mới user
    public function insert($data) {
        $sql = "INSERT INTO users (username, password_hash, full_name, email, so_dien_thoai, role, trang_thai) 
                VALUES (:user, :pass, :name, :email, :sdt, :role, 1)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user'  => $data['username'],
            ':pass'  => $data['password'], // Lưu password thường
            ':name'  => $data['full_name'],
            ':email' => $data['email'],
            ':sdt'   => $data['so_dien_thoai'] ?? '',
            ':role'  => $data['role']
        ]);
    }

    // 5. Cập nhật user
    public function update($id, $data) {
        if (!empty($data['password'])) {
            // Nếu có đổi mật khẩu
            $sql = "UPDATE users SET full_name=:name, email=:email, so_dien_thoai=:sdt, role=:role, password_hash=:pass WHERE id=:id";
            $params = [
                ':name' => $data['full_name'], ':email' => $data['email'], 
                ':sdt' => $data['so_dien_thoai'], ':role' => $data['role'], 
                ':pass' => $data['password'],
                ':id' => $id
            ];
        } else {
            // Giữ nguyên mật khẩu cũ
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

    // 6. Xóa user
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>