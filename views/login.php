<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - VietTravel Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f2f2f2; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 400px; border: none; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .card-header { background: #2c3e50; color: white; text-align: center; padding: 20px; font-weight: bold; font-size: 20px; }
        .btn-primary { background: #2c3e50; border: none; }
        .btn-primary:hover { background: #1a252f; }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="card-header">
        VIETTRAVEL PRO ADMIN
    </div>
    <div class="card-body p-4">
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); // Xóa lỗi sau khi hiện ?>
            </div>
        <?php endif; ?>

<form action="<?= BASE_URL ?>?act=check-login" method="POST">
                <div class="mb-3">
                <label class="form-label">Tên đăng nhập / Email</label>
                <input type="text" name="username" class="form-control" required placeholder="admin">
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required placeholder="123456">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">ĐĂNG NHẬP</button>
        </form>
    </div>
    <div class="card-footer text-center text-muted small">
        &copy; 2025 VietTravel System
    </div>
</div>

</body>
</html>