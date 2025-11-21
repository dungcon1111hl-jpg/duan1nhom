<?php
class User {
    public $conn;

    public function __construct() {
        // Kết nối CSDL
        $this->conn = connectDB();
    }

    public function checkLogin($username, $password) {
        try {
            // 1. Viết câu lệnh SQL lấy user
            $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
            
            // 2. Chuẩn bị thực thi
            $stmt = $this->conn->prepare($sql);
            
            // 3. Gán giá trị vào tham số
            $stmt->execute([
                'username' => $username,
                'email' => $username
            ]);
            
            // 4. Lấy 1 dòng dữ liệu
            $user = $stmt->fetch();

            // 5. Kiểm tra mật khẩu (Dùng password_verify vì trong DB đã mã hóa)
            // Nếu bạn test tay và lưu pass thô là '123456' thì sửa dòng dưới thành: if ($user && $user['password_hash'] == $password)
if ($user && $user['password_hash'] == $password) {                return $user; // Trả về mảng user nếu đúng
            }
            
            return false; // Trả về false nếu sai
        } catch (PDOException $e) {
            echo "Lỗi truy vấn: " . $e->getMessage();
            return false;
        }
    }
}
?>