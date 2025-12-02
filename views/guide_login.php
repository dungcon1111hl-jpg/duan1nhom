<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Guide Login - Traveloka Style</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Font: Inter (Traveloka dùng font tương tự) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --traveloka-blue: #0A8FFF;
            --traveloka-dark: #003d70;
        }

        body {
            font-family: "Inter", sans-serif;
            background: #f4faff;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Animation nhẹ kiểu Traveloka */
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(25px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .login-box {
            width: 100%;
            max-width: 410px;
            background: white;
            border-radius: 16px;
            padding: 35px 32px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            animation: fadeUp .6s ease-out;
        }

        .login-title {
            font-weight: 700;
            color: var(--traveloka-blue);
        }

        .login-sub {
            font-size: 0.88rem;
            margin-top: -5px;
            color: #6b6b6b;
        }

        .form-control {
            height: 52px;
            border-radius: 10px;
            border: 1px solid #d4dce4;
        }

        .form-control:focus {
            border-color: var(--traveloka-blue);
            box-shadow: 0 0 0 0.15rem rgba(10, 143, 255, 0.25);
        }

        .btn-login {
            height: 50px;
            border-radius: 10px;
            background: var(--traveloka-blue);
            border: none;
            color: white;
            font-size: 1.05rem;
            font-weight: 600;
            transition: .25s;
        }

        .btn-login:hover {
            background: #0072d8;
        }

        .back-link {
            color: #6b6b6b;
            text-decoration: none;
        }

        .back-link:hover {
            color: var(--traveloka-blue);
        }

        .logo-icon {
            font-size: 38px;
            color: var(--traveloka-blue);
            margin-bottom: 12px;
        }
    </style>
</head>

<body>

    <div class="login-box">

        <!-- Logo style Traveloka -->
        <div class="text-center">
            <i class="fas fa-paper-plane logo-icon"></i>
            <h3 class="login-title">Tour Guide Portal</h3>
            <div class="login-sub">Đăng nhập dành cho Hướng Dẫn Viên</div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center mt-3">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

  <form action="<?= BASE_URL ?>?act=guide-dashboard" method="POST" class="mt-4">

            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="fa fa-user me-2"></i>Tài khoản / Email</label>
                <input type="text" class="form-control" name="username" required placeholder="Nhập username...">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold"><i class="fa fa-lock me-2"></i>Mật khẩu</label>
                <input type="password" class="form-control" name="password" required placeholder="Nhập mật khẩu...">
            </div>

            <button class="btn btn-login w-100">
                Đăng nhập <i class="fas fa-arrow-right ms-2"></i>
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="<?= BASE_URL ?>?act=login" class="back-link">
                <i class="fas fa-arrow-left me-1"></i> Quay lại trang quản trị
            </a>
        </div>

    </div>

</body>
</html>
