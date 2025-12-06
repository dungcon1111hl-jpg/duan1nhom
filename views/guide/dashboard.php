<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>HDV Workspace</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0066FF; /* Màu chủ đạo xanh hiện đại */
            --bg-light: #F5F7FA;
            --card-bg: #FFFFFF;
            --text-dark: #1A1D1F;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            padding-bottom: 80px; /* Để tránh bottom nav che nội dung */
        }

        /* Header Gradient */
        .app-header {
            background: linear-gradient(135deg, #0066FF 0%, #004ECC 100%);
            color: white;
            padding: 20px 15px 50px 15px; /* Padding dưới lớn để lồng card vào */
            border-bottom-left-radius: 25px;
            border-bottom-right-radius: 25px;
            margin-bottom: -30px; /* Đẩy content lên đè vào header */
        }

        .user-info img {
            border: 2px solid rgba(255,255,255,0.5);
        }

        /* Stats Cards Row */
        .stats-container {
            display: flex;
            gap: 10px;
            padding: 0 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 15px;
            flex: 1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            border: 1px solid rgba(0,0,0,0.02);
        }
        .stat-number { font-size: 1.2rem; font-weight: 800; color: var(--primary); }
        .stat-label { font-size: 0.75rem; color: #6c757d; font-weight: 600; }

        /* Tour Card Styling */
        .tour-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 15px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .tour-card:active { transform: scale(0.98); }
        
        .tour-header {
            position: relative;
            height: 120px;
            overflow: hidden;
        }
        .tour-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .tour-status {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.9);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-dark);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .tour-body { padding: 15px; }
        .tour-title { font-weight: 700; font-size: 1rem; color: var(--text-dark); margin-bottom: 8px; line-height: 1.4; }
        
        .info-badge {
            display: inline-flex;
            align-items: center;
            background: #F4F7FE;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            color: #5E6E82;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .info-badge i { margin-right: 5px; color: var(--primary); }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }
        .empty-icon {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 15px;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 12px 0;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
            z-index: 1000;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
        .nav-item-mobile {
            text-align: center;
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .nav-item-mobile i {
            display: block;
            font-size: 1.3rem;
            margin-bottom: 4px;
        }
        .nav-item-mobile.active { color: var(--primary); }

    </style>
</head>
<body>

    <div class="app-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="opacity-75 small">Xin chào,</div>
                <div class="h5 fw-bold mb-0">
                    <?= htmlspecialchars($_SESSION['user_admin']['full_name'] ?? 'Hướng Dẫn Viên') ?> 👋
                </div>
            </div>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=HDV&background=random" width="40" height="40" class="rounded-circle shadow-sm">
            </div>
        </div>
    </div>

    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number"><?= count($myTours) ?></div>
            <div class="stat-label">Tour sắp tới</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php 
                $totalPax = 0;
                foreach($myTours as $t) $totalPax += $t['tong_khach'];
                echo $totalPax;
                ?>
            </div>
            <div class="stat-label">Khách phục vụ</div>
        </div>
    </div>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
            <h6 class="fw-bold text-dark m-0">Lịch trình của bạn</h6>
            <span class="badge bg-light text-secondary border">Tháng <?= date('m') ?></span>
        </div>

        <?php if(empty($myTours)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-mug-hot"></i></div>
                <h6 class="fw-bold">Bạn đang rảnh rỗi</h6>
                <p class="small text-muted">Chưa có tour nào được phân công. Hãy nghỉ ngơi và nạp năng lượng nhé!</p>
            </div>
        <?php else: ?>
            <?php foreach($myTours as $tour): 
                // Logic màu sắc trạng thái
                $statusColor = match($tour['trang_thai']) {
                    'DANG_CHAY' => 'text-success',
                    'HOAN_THANH' => 'text-secondary',
                    'HUY' => 'text-danger',
                    default => 'text-primary'
                };
                $statusText = match($tour['trang_thai']) {
                    'DANG_CHAY' => 'Đang diễn ra',
                    'NHAN_KHACH' => 'Sắp khởi hành',
                    default => $tour['trang_thai']
                };
            ?>
            <a href="index.php?act=guide-detail&id=<?= $tour['id'] ?>" class="text-decoration-none">
                <div class="tour-card">
                    <div class="tour-header">
                        <img src="<?= BASE_URL ?>uploads/tours/<?= $tour['anh_minh_hoa'] ?>" class="tour-img" onerror="this.src='https://placehold.co/600x400?text=Tour+Image'">
                        <div class="tour-status <?= $statusColor ?>">
                            <i class="fas fa-circle fa-2xs me-1"></i> <?= $statusText ?>
                        </div>
                    </div>
                    <div class="tour-body">
                        <h6 class="tour-title text-truncate"><?= htmlspecialchars($tour['ten_tour']) ?></h6>
                        
                        <div class="mb-2">
                            <span class="info-badge">
                                <i class="far fa-calendar-alt"></i> 
                                <?= date('d/m', strtotime($tour['ngay_khoi_hanh'])) ?> - <?= date('d/m', strtotime($tour['ngay_ket_thuc'])) ?>
                            </span>
                            <span class="info-badge">
                                <i class="fas fa-user-group"></i> <?= $tour['tong_khach'] ?> khách
                            </span>
                        </div>
                        
                        <div class="small text-muted d-flex align-items-center">
                            <i class="fas fa-location-dot me-2 text-danger"></i> 
                            <span class="text-truncate"><?= htmlspecialchars($tour['diem_tap_trung']) ?></span>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="bottom-nav">
        <a href="index.php?act=guide-dashboard" class="nav-item-mobile active">
            <i class="fas fa-home"></i> Trang chủ
        </a>
        <a href="#" class="nav-item-mobile">
            <i class="fas fa-bell"></i> Thông báo
        </a>
        <a href="index.php?act=logout" class="nav-item-mobile">
            <i class="fas fa-arrow-right-from-bracket"></i> Đăng xuất
        </a>
    </div>

</body>
</html>