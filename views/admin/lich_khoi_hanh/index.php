<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i>Quản lý Lịch Khởi Hành</h2>
            <a href="index.php?act=lich-khoi-hanh-create" class="btn btn-success shadow-sm">
                <i class="fas fa-plus me-1"></i> Tạo Lịch Mới
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Tour</th>
                                <th>Thời gian (Đi - Về)</th>
                                <th>Điểm tập trung</th>
                                <th class="text-center">Chỗ (Đã đặt/Tổng)</th>
                                <th class="text-end">Giá bán</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-end pe-4">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($lichs)): ?>
                                <?php foreach ($lichs as $item): ?>
                                <tr>
                                    <!-- CỘT TOUR - ĐÃ THÊM NÚT XEM CHI TIẾT TOUR -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div>
                                                <div class="fw-bold text-primary text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($item['ten_tour']) ?>">
                                                    <?= htmlspecialchars($item['ten_tour']) ?>
                                                </div>
                                                <small class="text-muted font-monospace">#<?= $item['ma_tour'] ?? '---' ?></small>
                                            </div>

                                            <!-- Nút xem chi tiết tour -->
                                            <a href="index.php?act=tour-detail&id=<?= $item['tour_id'] ?? $item['ma_tour'] ?>" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Xem chi tiết tour">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                    
                                    <!-- Thời gian -->
                                    <td>
                                        <div class="small">
                                            <span class="text-success fw-bold"><i class="fas fa-plane-departure me-1"></i> <?= date('d/m H:i', strtotime($item['ngay_khoi_hanh'])) ?></span>
                                            <br>
                                            <span class="text-danger fw-bold"><i class="fas fa-plane-arrival me-1"></i> <?= date('d/m H:i', strtotime($item['ngay_ket_thuc'])) ?></span>
                                        </div>
                                    </td>
                                    
                                    <!-- Điểm tập trung -->
                                    <td>
                                        <?= htmlspecialchars($item['diem_tap_trung'] ?? '-') ?>
                                        <?php if(!empty($item['ghi_chu'])): ?>
                                            <i class="fas fa-info-circle text-secondary ms-1" title="<?= htmlspecialchars($item['ghi_chu']) ?>"></i>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- Số chỗ -->
                                    <td class="text-center">
                                        <?php 
                                            $da_dat = $item['so_cho_da_dat'] ?? 0;
                                            $tong = $item['so_cho_toi_da'] ?? 0;
                                            $con_lai = $tong - $da_dat;
                                            $percent = ($tong > 0) ? ($da_dat / $tong) * 100 : 0;
                                            $color = $con_lai > 0 ? 'success' : 'danger';
                                        ?>
                                        <div class="progress" style="height: 6px; width: 80px; margin: 0 auto;">
                                            <div class="progress-bar bg-<?= $color ?>" role="progressbar" style="width: <?= $percent ?>%"></div>
                                        </div>
                                        <small class="fw-bold text-<?= $color ?>"><?= $da_dat ?> / <?= $tong ?></small>
                                    </td>

                                    <!-- Giá bán -->
                                    <td class="text-end fw-bold text-success">
                                        <?= number_format($item['gia_nguoi_lon'] > 0 ? $item['gia_nguoi_lon'] : 0) ?> ₫
                                    </td>

                                    <!-- Trạng thái -->
                                    <td class="text-center">
                                        <?php 
                                            $sttColor = match($item['trang_thai']) {
                                                'DU_KIEN'     => 'secondary',
                                                'CHOT_SO'     => 'primary',
                                                'DANG_CHAY'   => 'info',
                                                'HOAN_THANH'  => 'success',
                                                'HUY'         => 'danger',
                                                default       => 'light text-dark'
                                            };
                                        ?>
                                        <span class="badge bg-<?= $sttColor ?>"><?= $item['trang_thai'] ?></span>
                                    </td>
                                    
                                    <!-- Hành động -->
                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Thao tác
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                <li>
                                                    <a class="dropdown-item" href="index.php?act=lich-detail&id=<?= $item['id'] ?>">
                                                        <i class="fas fa-tasks text-primary me-2"></i>Điều hành chi tiết
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index.php?act=lich-baocao&id=<?= $item['id'] ?>">
                                                        <i class="fas fa-chart-pie text-info me-2"></i>Báo cáo doanh thu
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="index.php?act=lich-khoi-hanh-edit&id=<?= $item['id'] ?>">
                                                        <i class="fas fa-edit text-warning me-2"></i>Sửa thông tin
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="index.php?act=lich-khoi-hanh-delete&id=<?= $item['id'] ?>" 
                                                       onclick="return confirm('Xóa lịch khởi hành này?')">
                                                        <i class="fas fa-trash me-2"></i>Xóa lịch
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-calendar-times fa-3x mb-3"></i><br>
                                        Chưa có lịch khởi hành nào.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>