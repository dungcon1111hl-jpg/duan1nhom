<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HDV Panel - Lịch trình của tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .tour-card { border-radius: 15px; overflow: hidden; transition: 0.2s; border:none; margin-bottom: 15px; }
        .tour-card:active { transform: scale(0.98); }
        .status-tag { position: absolute; top: 10px; right: 10px; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-success sticky-top">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1"><i class="fas fa-map-signs me-2"></i>Lịch Của Tôi</span>
            <a href="index.php?act=logout" class="text-white"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </nav>

    <div class="container py-3">
        <?php if(empty($myTours)): ?>
            <div class="text-center text-muted mt-5">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <p>Bạn chưa có lịch tour nào.</p>
            </div>
        <?php else: foreach($myTours as $tour): ?>
            <a href="index.php?act=guide-detail&id=<?= $tour['id'] ?>" class="text-decoration-none text-dark">
                <div class="card tour-card shadow-sm">
                    <div class="position-relative">
                        <img src="<?= BASE_URL ?>uploads/tours/<?= $tour['anh_minh_hoa'] ?>" class="card-img-top" style="height: 150px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x150'">
                        <span class="badge bg-warning text-dark status-tag shadow"><?= $tour['trang_thai'] ?></span>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($tour['ten_tour']) ?></h5>
                        <p class="card-text small text-muted mb-2">
                            <i class="far fa-clock me-1"></i> <?= date('d/m H:i', strtotime($tour['ngay_khoi_hanh'])) ?> 
                            - <?= date('d/m', strtotime($tour['ngay_ket_thuc'])) ?>
                        </p>
                        <p class="card-text small text-primary mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($tour['diem_tap_trung']) ?>
                        </p>
                    </div>
                </div>
            </a>
        <?php endforeach; endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>