<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - VietTravel Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background-color: #e9ecef; 
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container { width: 100%; max-width: 450px; padding: 15px; }
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            overflow: hidden; 
        }
        .card-header { 
            background: #2c3e50; 
            color: white; 
            text-align: center; 
            padding: 30px 20px; 
            border-bottom: none; 
        }
        .card-header h3 { 
            margin: 0; 
            font-size: 24px; 
            font-weight: 700; 
            letter-spacing: 1px; 
            text-transform: uppercase;
        }
        .card-header .small { 
            color: #aab7c4; 
            font-size: 13px; 
            margin-top: 5px; 
            display: block; 
            font-weight: 400;
        }
        .btn-primary { 
            background-color: #2c3e50; 
            border-color: #2c3e50; 
            padding: 12px; 
            font-weight: 600; 
            font-size: 16px;
            transition: all 0.3s; 
        }
        .btn-primary:hover { 
            background-color: #34495e; 
            border-color: #34495e; 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.3);
        }
        .role-link {
            font-size: 0.85rem;
            color: #6c757d;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.2s;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .role-link:hover {
            background: #e9ecef;
            color: #2c3e50;
            border-color: #adb5bd;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.25rem rgba(44, 62, 80, 0.25);
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-plane-departure me-2"></i>VietTravel Pro</h3>
            <span class="small">Hệ thống quản lý du lịch toàn diện</span>
        </div>
        <div class="card-body p-4 px-5">
            
            <div class="text-center mb-4">
                <h5 class="text-secondary fw-bold">Đăng Nhập</h5>
                <p class="text-muted small">Vui lòng nhập thông tin tài khoản của bạn</p>
            </div>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger d-flex align-items-center p-2 mb-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div style="font-size: 0.9rem;">
                        <?= $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>?act=check-login" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Tài khoản / Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-secondary"></i></span>
                        <input type="text" name="username" class="form-control border-start-0 ps-0" required placeholder="Nhập username...">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-secondary"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0" required placeholder="Nhập mật khẩu...">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-4 shadow-sm">
                    ĐĂNG NHẬP NGAY
                </button>
            </form>

            <div class="position-relative mb-4">
                <hr class="text-muted">
                <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted small">
                    Cổng đăng nhập
                </span>
            </div>

            <div class="d-flex justify-content-center gap-2">
                <a href="<?= BASE_URL ?>?act=login" class="role-link" title="Dành cho Quản trị viên">
                    <i class="fas fa-user-shield text-danger"></i> Admin
                </a>
                <a href="<?= BASE_URL ?>?act=login-guide" class="role-link" title="Dành cho Hướng dẫn viên">
                    <i class="fas fa-map-marked-alt text-success"></i> Hướng dẫn viên
                </a>
            </div>

        </div>
    </div>
    <div class="text-center mt-3 text-muted small">
        &copy; 2025 VietTravel System. All rights reserved.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>