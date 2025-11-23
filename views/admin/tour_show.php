<?php require_once PATH_ROOT . '/views/header.php'; ?>
<?php
    // Xử lý dữ liệu mặc định tránh lỗi
    $list_anh = $list_anh ?? [];
    $list_lich_trinh = $list_lich_trinh ?? [];
    $tour = $tour ?? [];
    $hdv = $hdv ?? null; // Biến chứa thông tin HDV đã được Controller fetch
?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-info"><i class="fas fa-info-circle me-2"></i>Chi tiết Tour</h2>
            <a href="index.php?act=tours" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white fw-bold py-3">
                        <i class="fas fa-file-alt me-2"></i> Thông tin chung
                    </div>
                    <div class="card-body">
                        <h3 class="fw-bold text-dark mb-3"><?= htmlspecialchars($tour['ten_tour'] ?? 'Chưa có tên') ?></h3>
                        
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Mã tour</small>
                                <span class="fs-5 text-primary fw-bold"><?= $tour['ma_tour'] ?? '---' ?></span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Loại Tour</small>
                                <?php
                                    $types = ['TRONG_NUOC' => 'Trong nước', 'QUOC_TE' => 'Quốc tế', 'THEO_YEU_CAU' => 'Theo yêu cầu'];
                                    echo "<span class='badge bg-info text-dark'>" . ($types[$tour['loai_tour'] ?? ''] ?? 'Khác') . "</span>";
                                ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Phân loại</small>
                                <strong><?= $tour['loai_tour_nang_cao'] ?? 'Trọn gói' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-4 p-3 bg-light rounded mx-1">
                            <div class="col-md-4">
                                <small class="text-muted d-block"><i class="fas fa-plane-departure me-1"></i> Điểm đi</small>
                                <strong><?= $tour['dia_diem_bat_dau'] ?></strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block"><i class="fas fa-exchange-alt me-1"></i> Trung chuyển</small>
                                <strong><?= $tour['diem_trung_chuyen'] ?? '-' ?></strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block"><i class="fas fa-map-marker-alt me-1"></i> Điểm đến</small>
                                <strong><?= $tour['dia_diem_ket_thuc'] ?></strong>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary"><i class="fas fa-align-left me-2"></i>Mô tả ngắn</h6>
                            <p class="text-secondary fst-italic border-start border-3 ps-3 border-primary">
                                <?= nl2br(htmlspecialchars($tour['mo_ta_ngan'] ?? 'Chưa có mô tả')) ?>
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold text-primary"><i class="fas fa-list me-2"></i>Chi tiết chương trình</h6>
                            <div class="p-3 border rounded bg-white" style="max-height: 300px; overflow-y: auto;">
                                <?= nl2br(htmlspecialchars($tour['mo_ta_chi_tiet'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark fw-bold py-3">
                        <i class="fas fa-calendar-alt me-2"></i> Lịch trình chi tiết
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="20%">Thời gian</th>
                                        <th width="30%">Tiêu đề</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($list_lich_trinh)): ?>
                                        <?php foreach ($list_lich_trinh as $lt): ?>
                                        <tr>
                                            <td class="fw-bold text-primary">
                                                Ngày <?= $lt['ngay_thu'] ?? $lt['thu_tu_ngay'] ?><br>
                                                <small class="text-muted fw-normal">
                                                    <?= isset($lt['gio_bat_dau']) ? date('H:i', strtotime($lt['gio_bat_dau'])) : '' ?> - 
                                                    <?= isset($lt['gio_ket_thuc']) ? date('H:i', strtotime($lt['gio_ket_thuc'])) : '' ?>
                                                </small>
                                            </td>
                                            <td>
                                                <strong><?= $lt['tieu_de'] ?></strong><br>
                                                <small class="text-muted"><i class="fas fa-map-pin me-1"></i><?= $lt['dia_diem'] ?? '' ?></small>
                                            </td>
                                            <td><?= nl2br($lt['noi_dung'] ?? $lt['hoat_dong'] ?? '') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-center text-muted py-4">Chưa cập nhật lịch trình.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white fw-bold py-3">
                        <i class="fas fa-images me-2"></i> Thư viện ảnh (<?= count($list_anh) ?>)
                    </div>
                    <div class="card-body">
                        <?php if (!empty($list_anh)): ?>
                            <div class="row g-2">
                                <?php foreach ($list_anh as $img): ?>
                                    <div class="col-6 col-md-3">
                                        <div class="ratio ratio-1x1 border rounded overflow-hidden">
                                            <a href="<?= BASE_URL . $img['duong_dan'] ?>" target="_blank">
                                                <img src="<?= BASE_URL . $img['duong_dan'] ?>" class="img-fluid object-fit-cover h-100 w-100" alt="Tour Image">
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center text-muted py-3">Chưa có ảnh nào trong thư viện.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0 position-relative">
                        <?php if (!empty($tour['anh_minh_hoa'])): ?>
                            <img src="<?= BASE_URL . $tour['anh_minh_hoa'] ?>" class="img-fluid rounded-top w-100" style="height: 250px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center rounded-top" style="height: 200px;">
                                <span class="text-muted"><i class="fas fa-image fa-3x"></i></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-<?= ($tour['trang_thai'] ?? '') == 'CON_VE' ? 'success' : 'danger' ?> fs-6 shadow">
                                <?= $tour['trang_thai'] ?? 'UNK' ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Ngày khởi hành:</span>
                                <span class="fw-bold"><?= !empty($tour['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])) : '-' ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Ngày kết thúc:</span>
                                <span class="fw-bold"><?= !empty($tour['ngay_ket_thuc']) ? date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) : '-' ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Số lượng khách:</span>
                                <span class="fw-bold"><?= $tour['so_khach_toithieu'] ?? 1 ?> - <?= $tour['so_luong_ve'] ?? 0 ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Vé còn lại:</span>
                                <span class="badge bg-primary rounded-pill"><?= $tour['so_ve_con_lai'] ?? 0 ?></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="fas fa-tags me-2"></i> Bảng giá chi tiết
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <tr>
                                <td>Người lớn (>12t)</td>
                                <td class="text-end fw-bold text-success"><?= number_format($tour['gia_nguoi_lon'] ?? 0) ?> ₫</td>
                            </tr>
                            <tr>
                                <td>Trẻ em (5-11t)</td>
                                <td class="text-end fw-bold"><?= number_format($tour['gia_tre_em'] ?? 0) ?> ₫</td>
                            </tr>
                            <tr>
                                <td>Em bé (<5t)</td>
                                <td class="text-end fw-bold"><?= number_format($tour['gia_em_be'] ?? 0) ?> ₫</td>
                            </tr>
                            <tr>
                                <td>Phụ thu / Lễ</td>
                                <td class="text-end fw-bold text-danger">+ <?= number_format($tour['phu_thu'] ?? 0) ?> ₫</td>
                            </tr>
                            <tr class="table-primary">
                                <td class="fw-bold">GIÁ CHUNG</td>
                                <td class="text-end fw-bold fs-5"><?= number_format($tour['gia_tour'] ?? 0) ?> ₫</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white fw-bold">
                        <i class="fas fa-user-tie me-2"></i> Hướng dẫn viên
                    </div>
                    <div class="card-body">
                        <?php if (!empty($hdv)): ?>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <?php if (!empty($hdv['anh_dai_dien'])): ?>
                                        <img src="<?= BASE_URL . $hdv['anh_dai_dien'] ?>" class="rounded-circle border" width="50" height="50" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-user text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 fw-bold text-primary"><?= htmlspecialchars($hdv['ho_ten']) ?></h6>
                                    <small class="text-muted d-block"><i class="fas fa-phone-alt me-1"></i> <?= htmlspecialchars($hdv['so_dien_thoai']) ?></small>
                                    <small class="text-muted d-block"><i class="fas fa-language me-1"></i> <?= htmlspecialchars($hdv['ngon_ngu']) ?></small>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3 text-muted">
                                <i class="fas fa-user-slash fa-2x mb-2 opacity-25"></i>
                                <p class="mb-0 small">Chưa phân công HDV cho tour này.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>" class="btn btn-warning fw-bold text-white py-2">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa thông tin
                    </a>
                    <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>#schedule" class="btn btn-outline-primary fw-bold py-2">
                        <i class="fas fa-calendar-plus me-1"></i> Cập nhật lịch trình
                    </a>
                </div>

            </div>
        </div>
    </div>
</main>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>