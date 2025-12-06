<?php require_once PATH_ROOT . '/views/header.php'; ?>

<?php
// Xử lý dữ liệu đầu vào an toàn
$toursData = [];
if (!empty($tours)) {
    if ($tours instanceof PDOStatement) {
        $toursData = $tours->fetchAll(PDO::FETCH_ASSOC);
    } elseif (is_array($tours)) {
        $toursData = $tours;
    }
}
$totalTours = count($toursData);

// Tính toán thống kê
$activeTours = 0;
$soldOutTours = 0;
$departedTours = 0;
$inactiveTours = 0;

foreach ($toursData as $t) {
    switch ($t['trang_thai'] ?? '') {
        case 'CON_VE': $activeTours++; break;
        case 'HET_VE': $soldOutTours++; break;
        case 'DA_KHOI_HANH': $departedTours++; break;
        case 'NGUNG_HOAT_DONG': $inactiveTours++; break;
    }
}
?>

<style>
    :root {
        --primary-color: #4f46e5;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #06b6d4;
        --bg-gray: #f9fafb;
    }

    .main-content-inner {
        padding: 20px;
        background-color: var(--bg-gray);
        min-height: calc(100vh - 60px);
    }

    .custom-page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .custom-page-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .btn-create-new {
        background: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-create-new:hover {
        background: #4338ca;
        color: white;
        transform: translateY(-2px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-box-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .filter-wrapper {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .custom-table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    .custom-table thead th {
        background: #f3f4f6;
        padding: 12px 16px;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
    }

    .custom-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        color: #374151;
    }

    .custom-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .tour-thumbnail {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
    }

    .action-group {
        position: relative;
    }

    .dropdown-menu-custom {
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        min-width: 180px;
        z-index: 999;
        display: none;
        border: 1px solid #e5e7eb;
        padding: 5px 0;
    }
    
    .dropdown-menu-custom.show {
        display: block;
    }

    .dropdown-item-custom {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 15px;
        color: #374151;
        text-decoration: none;
        font-size: 0.875rem;
        transition: background 0.2s;
    }

    .dropdown-item-custom:hover {
        background-color: #f3f4f6;
    }
</style>

<div class="container-fluid main-content-inner">
    
    <div class="custom-page-header">
        <div>
            <h1><i class="fas fa-globe-asia me-2 text-primary"></i>Quản lý Tour Du Lịch</h1>
            <p class="text-muted mb-0 mt-1">Tổng quan danh sách tour trong hệ thống</p>
        </div>
        <a href="index.php?act=tour-create" class="btn-create-new">
            <i class="fas fa-plus"></i> Thêm Tour Mới
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-box" style="border-left: 4px solid var(--primary-color);">
            <div class="stat-box-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary-color);">
                <i class="fas fa-route"></i>
            </div>
            <div>
                <div class="h3 mb-0 fw-bold"><?= $totalTours ?></div>
                <div class="text-muted small">Tổng số tour</div>
            </div>
        </div>
        <div class="stat-box" style="border-left: 4px solid var(--success-color);">
            <div class="stat-box-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="h3 mb-0 fw-bold"><?= $activeTours ?></div>
                <div class="text-muted small">Đang hoạt động</div>
            </div>
        </div>
        <div class="stat-box" style="border-left: 4px solid var(--warning-color);">
            <div class="stat-box-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div>
                <div class="h3 mb-0 fw-bold"><?= $soldOutTours ?></div>
                <div class="text-muted small">Hết vé</div>
            </div>
        </div>
        <div class="stat-box" style="border-left: 4px solid var(--danger-color);">
            <div class="stat-box-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color);">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div>
                <div class="h3 mb-0 fw-bold"><?= $inactiveTours ?></div>
                <div class="text-muted small">Ngừng hoạt động</div>
            </div>
        </div>
    </div>

    <div class="filter-wrapper">
        <form action="index.php" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="act" value="tours">
            
            <div class="col-md-4">
                <label class="form-label fw-bold text-muted small">TÌM KIẾM</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="ten_tour" class="form-control border-start-0" 
                           placeholder="Tên tour, mã tour..." value="<?= htmlspecialchars($_GET['ten_tour'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold text-muted small">LOẠI TOUR</label>
                <select name="loai_tour" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="TRONG_NUOC" <?= ($_GET['loai_tour'] ?? '') == 'TRONG_NUOC' ? 'selected' : '' ?>>🇻🇳 Trong nước</option>
                    <option value="QUOC_TE" <?= ($_GET['loai_tour'] ?? '') == 'QUOC_TE' ? 'selected' : '' ?>>🌍 Quốc tế</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold text-muted small">TRẠNG THÁI</label>
                <select name="trang_thai" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="CON_VE" <?= ($_GET['trang_thai'] ?? '') == 'CON_VE' ? 'selected' : '' ?>>Còn vé</option>
                    <option value="HET_VE" <?= ($_GET['trang_thai'] ?? '') == 'HET_VE' ? 'selected' : '' ?>>Hết vé</option>
                    <option value="NGUNG_HOAT_DONG" <?= ($_GET['trang_thai'] ?? '') == 'NGUNG_HOAT_DONG' ? 'selected' : '' ?>>Ngừng HĐ</option>
                </select>
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-filter"></i> Lọc</button>
                    <a href="index.php?act=tours" class="btn btn-light border"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-container">
        <div class="custom-table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="padding-left: 24px;">Thông tin Tour</th>
                        <th>Hành trình</th>
                        <th>Loại & Ngày</th>
                        <th>Giá & Vé</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end" style="padding-right: 24px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($toursData)): ?>
                        <?php foreach ($toursData as $tour): 
                             $soLuongVe = !empty($tour['so_luong_ve']) ? (int)$tour['so_luong_ve'] : 0;
                             $soVeConLai = !empty($tour['so_ve_con_lai']) ? (int)$tour['so_ve_con_lai'] : 0;
                             $ticketPercent = $soLuongVe > 0 ? round(($soVeConLai / $soLuongVe) * 100) : 0;
                             $progressColor = $ticketPercent > 50 ? '#10b981' : ($ticketPercent > 20 ? '#f59e0b' : '#ef4444');
                        ?>
                        <tr>
                            <td style="padding-left: 24px;">
                                <div class="d-flex align-items-center gap-3">
                                    <?php if (!empty($tour['anh_minh_hoa'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/tours/<?= htmlspecialchars($tour['anh_minh_hoa']) ?>" class="tour-thumbnail">
                                    <?php else: ?>
                                        <div class="tour-thumbnail d-flex align-items-center justify-content-center bg-light text-secondary">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">
                                            <?= htmlspecialchars($tour['ten_tour'] ?? 'Chưa có tên') ?>
                                        </div>
                                        <div class="small text-muted font-monospace">
                                            <i class="fas fa-hashtag me-1"></i><?= htmlspecialchars($tour['ma_tour'] ?? 'N/A') ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center small gap-2">
                                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($tour['dia_diem_bat_dau'] ?? '---') ?></span>
                                    <i class="fas fa-arrow-right text-muted" style="font-size: 10px;"></i>
                                    <span class="badge bg-light text-primary border border-primary"><?= htmlspecialchars($tour['dia_diem_ket_thuc'] ?? '---') ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <?php if(($tour['loai_tour'] ?? '') === 'QUOC_TE'): ?>
                                        <span class="badge bg-warning text-dark bg-opacity-25 w-auto" style="width: fit-content;">Quốc tế</span>
                                    <?php else: ?>
                                        <span class="badge bg-info text-info bg-opacity-10 w-auto" style="width: fit-content;">Trong nước</span>
                                    <?php endif; ?>
                                    
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        <?= !empty($tour['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])) : 'Chưa có lịch' ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-success">
                                    <?= number_format(!empty($tour['gia_tour']) ? $tour['gia_tour'] : ($tour['gia_nguoi_lon'] ?? 0)) ?>₫
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <div class="progress" style="height: 4px; width: 60px;">
                                        <div class="progress-bar" style="width: <?= $ticketPercent ?>%; background-color: <?= $progressColor ?>"></div>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem"><strong><?= $soVeConLai ?></strong>/<?= $soLuongVe ?></small>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $statusMap = [
                                        'CON_VE' => ['bg-success bg-opacity-10 text-success', 'Còn vé'],
                                        'HET_VE' => ['bg-danger bg-opacity-10 text-danger', 'Hết vé'],
                                        'DA_KHOI_HANH' => ['bg-info bg-opacity-10 text-info', 'Đã đi'],
                                        'NGUNG_HOAT_DONG' => ['bg-secondary bg-opacity-10 text-secondary', 'Ngừng']
                                    ];
                                    $stt = $statusMap[$tour['trang_thai'] ?? ''] ?? ['bg-light text-muted', $tour['trang_thai'] ?? 'N/A'];
                                ?>
                                <span class="badge <?= $stt[0] ?> rounded-pill border border-opacity-10"><?= $stt[1] ?></span>
                            </td>
                            <td class="text-end" style="padding-right: 24px;">
                                <div class="action-group">
                                    <button class="btn btn-sm btn-light border" onclick="toggleDropdown(this)">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu-custom">
                                        <a href="index.php?act=tour-detail&id=<?= $tour['id'] ?>" class="dropdown-item-custom text-info">
                                            <i class="fas fa-eye w-25"></i> Chi tiết
                                        </a>
                                        <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>" class="dropdown-item-custom text-warning">
                                            <i class="fas fa-pen w-25"></i> Chỉnh sửa
                                        </a>
                                        <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>#schedule" class="dropdown-item-custom">
                                            <i class="fas fa-calendar-day w-25"></i> Lịch trình
                                        </a>
                                        <a href="index.php?act=lien-ket-list&tour_id=<?= $tour['id'] ?>" class="dropdown-item-custom text-success">
                                            <i class="fas fa-link w-25"></i> Liên kết NCC
                                        </a>
                                        <hr style="margin: 5px 0; border-color: #eee;">
                                        <?php if(($tour['trang_thai'] ?? '') !== 'NGUNG_HOAT_DONG'): ?>
                                            <a href="index.php?act=tour-delete&id=<?= $tour['id'] ?>" 
                                               class="dropdown-item-custom text-danger"
                                               onclick="return confirm('Ngừng hoạt động tour này?')">
                                                <i class="fas fa-ban w-25"></i> Dừng hoạt động
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-light"></i><br>
                                Không tìm thấy tour nào phù hợp
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-3 border-top bg-light text-muted small d-flex justify-content-between">
            <span><i class="far fa-clock me-1"></i> Cập nhật: <?= date('d/m/Y H:i') ?></span>
            <span>Hiển thị <?= count($toursData) ?> bản ghi</span>
        </div>
    </div>
</div>

<script>
function toggleDropdown(btn) {
    // Đóng tất cả dropdown khác trước
    document.querySelectorAll('.dropdown-menu-custom').forEach(el => {
        if(el !== btn.nextElementSibling) el.classList.remove('show');
    });
    
    // Toggle dropdown hiện tại
    const menu = btn.nextElementSibling;
    menu.classList.toggle('show');
    
    // Ngăn chặn sự kiện click lan ra ngoài
    event.stopPropagation();
}

// Đóng dropdown khi click ra ngoài
document.addEventListener('click', function(e) {
    document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
        menu.classList.remove('show');
    });
});
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>