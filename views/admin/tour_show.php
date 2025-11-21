<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Chi tiết Tour</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?act=admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?controller=tour&action=index">Danh sách Tour</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($tour['ma_tour']) ?></li>
    </ol>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold mb-0">
            <i class="fas fa-route me-2"></i> 
            <?= htmlspecialchars($tour['ten_tour']) ?>
        </h2>
        <a href="<?= BASE_URL ?>?act=tours" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h4 class="card-title text-success fw-bold mb-3">
                        <i class="fas fa-info-circle me-2"></i> Thông tin chung
                    </h4>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-barcode text-muted me-1"></i> Mã tour:</strong><br>
                                <span class="badge bg-light text-dark fs-6"><?= htmlspecialchars($tour['ma_tour']) ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-coins text-warning me-1"></i> Giá tour:</strong><br>
                                <span class="fs-5 fw-bold text-success">
                                    <?= number_format($tour['gia_tour']) ?> đ
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <p class="mb-3">
                        <strong><i class="fas fa-align-left text-info me-1"></i> Mô tả ngắn:</strong><br>
                        <span class="text-muted"><?= nl2br(htmlspecialchars($tour['mo_ta_ngan'] ?? 'Chưa có mô tả')) ?></span>
                    </p>

                    <p class="mb-3">
                        <strong><i class="fas fa-map-marked-alt text-primary me-1"></i> Hành trình:</strong><br>
                        <span class="fs-6">
                            <span class="badge bg-primary">
                                <?= htmlspecialchars($tour['dia_diem_bat_dau']) ?>
                            </span>
                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                            <span class="badge bg-success">
                                <?= htmlspecialchars($tour['dia_diem_ket_thuc']) ?>
                            </span>
                        </span>
                    </p>

                    <p class="mb-4">
                        <strong><i class="far fa-calendar-alt text-danger me-1"></i> Thời gian:</strong><br>
                        <span class="badge bg-light text-dark">
                            <?= date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])) ?>
                        </span>
                        <span class="text-muted mx-1">→</span>
                        <span class="badge bg-light text-dark">
                            <?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?>
                        </span>
                    </p>

                    <h5 class="mt-4 text-success fw-bold">
                        <i class="fas fa-file-alt me-2"></i> Mô tả chi tiết
                    </h5>
                    <div class="bg-light p-3 rounded">
                        <?= nl2br(htmlspecialchars($tour['mo_ta_chi_tiet'] ?? 'Chưa có thông tin chi tiết.')) ?>
                    </div>

                    <h5 class="mt-4 text-danger fw-bold">
                        <i class="fas fa-shield-alt me-2"></i> Chính sách hủy/đổi/hoàn
                    </h5>
                    <div class="bg-danger-subtle p-3 rounded text-danger-emphasis">
                        <?= nl2br(htmlspecialchars($tour['chinh_sach'] ?? 'Chưa có chính sách.')) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="card-title text-info fw-bold mb-3">
                        <i class="fas fa-ticket-alt me-2"></i> Tình trạng vé
                    </h4>

                    <?php
                    $total = (int)$tour['so_luong_ve'];
                    $remain = (int)$tour['so_ve_con_lai'];
                    $percent = $total > 0 ? ($remain / $total) * 100 : 0;
                    $statusColor = match($tour['trang_thai']) {
                        'CON_VE' => 'success',
                        'HET_VE' => 'danger',
                        'HUY' => 'secondary',
                        'DA_KHOI_HANH' => 'info',
                        'DA_KET_THUC' => 'dark',
                        default => 'light'
                    };
                    $statusLabel = match($tour['trang_thai']) {
                        'CON_VE' => 'Còn vé',
                        'HET_VE' => 'Hết vé',
                        'HUY' => 'Đã hủy',
                        'DA_KHOI_HANH' => 'Đã khởi hành',
                        'DA_KET_THUC' => 'Đã kết thúc',
                        default => $tour['trang_thai']
                    };
                    ?>

                    <div class="text-center mb-3">
                        <span class="badge bg-<?= $statusColor ?> fs-6 px-3 py-2">
                            <i class="fas fa-circle me-1"></i> <?= $statusLabel ?>
                        </span>
                    </div>

                    <p class="mb-2 text-center">
                        <strong class="fs-4 text-primary"><?= $remain ?></strong>
                        <small class="text-muted"> / <?= $total ?> vé</small>
                    </p>

                    <div class="progress mb-3" style="height: 12px;">
                        <div class="progress-bar bg-<?= $percent > 50 ? 'success' : ($percent > 20 ? 'warning' : 'danger') ?>"
                             role="progressbar"
                             style="width: <?= $percent ?>%"
                             aria-valuenow="<?= $remain ?>" aria-valuemin="0" aria-valuemax="<?= $total ?>">
                        </div>
                    </div>
                    <small class="text-muted d-block text-center">
                        <?= round($percent, 1) ?>% vé còn lại
                    </small>
                </div>
            </div>

            <?php if (!empty($tour['anh_minh_hoa'])): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <a href="uploads/tours/<?= htmlspecialchars($tour['anh_minh_hoa']) ?>" 
                           data-lightbox="tour-image" 
                           data-title="<?= htmlspecialchars($tour['ten_tour']) ?>">
                            <img src="uploads/tours/<?= htmlspecialchars($tour['anh_minh_hoa']) ?>" 
                                 alt="Ảnh tour" 
                                 class="img-fluid rounded w-100" 
                                 style="max-height: 300px; object-fit: cover; cursor: zoom-in;">
                        </a>
                        <div class="p-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-image me-1"></i> Nhấn để phóng to
                            </small>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-image text-muted fa-3x mb-3"></i>
                        <p class="text-muted">Chưa có ảnh minh họa</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4 text-end">
        <a href="index.php?controller=tour&action=edit&id=<?= $tour['id'] ?>" 
           class="btn btn-warning shadow-sm me-2">
            <i class="fas fa-edit me-1"></i> Sửa Tour
        </a>
        <a href="index.php?controller=tour&action=delete&id=<?= $tour['id'] ?>" 
           class="btn btn-danger shadow-sm"
           onclick="return confirm('Xóa tour này? Dữ liệu sẽ không thể khôi phục.')">
            <i class="fas fa-trash-alt me-1"></i> Xóa Tour
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<style>
    .card {
        border-radius: 14px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .progress {
        border-radius: 6px;
    }
    .badge {
        font-weight: 600;
    }
    img[alt="Ảnh tour"] {
        transition: transform 0.3s;
    }
    img[alt="Ảnh tour"]:hover {
        transform: scale(1.02);
    }
</style>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>