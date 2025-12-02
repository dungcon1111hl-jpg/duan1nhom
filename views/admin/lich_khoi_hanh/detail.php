<?php require_once PATH_ROOT . '/views/header.php'; ?>

<?php
    // Xử lý dữ liệu để tránh lỗi
    $lich = $lich ?? [];
    $list_nhansu = $list_nhansu ?? [];
    $list_dichvu = $list_dichvu ?? [];
?>

<main>
    <div class="container-fluid px-4">
        <!-- Header & Nút điều hướng -->
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>Chi Tiết Lịch Khởi Hành
                </h2>
                <div class="text-muted">Mã Lịch: <strong>#<?= $lich['id'] ?></strong></div>
            </div>
            <div>
                <a href="index.php?act=lich-khoi-hanh-edit&id=<?= $lich['id'] ?>" class="btn btn-warning fw-bold shadow-sm me-2">
                    <i class="fas fa-edit"></i> Sửa thông tin
                </a>
                <a href="index.php?act=lich-khoi-hanh" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <!-- KHỐI 1: THÔNG TIN TOUR & LỊCH TRÌNH -->
            <div class="col-12 mb-4">
                <div class="card border-primary shadow-sm">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="fas fa-map-marked-alt me-2"></i> Thông tin Tour & Lịch trình
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($lich['ten_tour']) ?></h4>
                                <span class="badge bg-info text-dark">Mã Tour: <?= htmlspecialchars($lich['ma_tour']) ?></span>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="badge bg-<?= ($lich['trang_thai'] ?? '') == 'HOAN_THANH' ? 'success' : 'warning text-dark' ?> fs-6">
                                    <?= $lich['trang_thai'] ?? 'DU_KIEN' ?>
                                </span>
                            </div>
                        </div>
                        
                        <hr>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <small class="text-muted d-block text-uppercase fw-bold">Ngày đi</small>
                                <div class="fs-5 text-success fw-bold">
                                    <i class="far fa-calendar-check me-1"></i> 
                                    <?= date('d/m/Y H:i', strtotime($lich['ngay_khoi_hanh'])) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block text-uppercase fw-bold">Ngày về</small>
                                <div class="fs-5 text-danger fw-bold">
                                    <i class="far fa-calendar-times me-1"></i> 
                                    <?= date('d/m/Y H:i', strtotime($lich['ngay_ket_thuc'])) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block text-uppercase fw-bold">Điểm tập trung</small>
                                <div class="fs-5">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i> 
                                    <?= htmlspecialchars($lich['diem_tap_trung'] ?? 'Chưa cập nhật') ?>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <small class="text-muted d-block text-uppercase fw-bold">Số chỗ</small>
                                <div>
                                    <span class="fw-bold text-primary"><?= $lich['so_cho_da_dat'] ?></span> / <?= $lich['so_cho_toi_da'] ?>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <small class="text-muted d-block text-uppercase fw-bold">Giá bán (Lịch này)</small>
                                <div>
                                    <span class="me-3">Người lớn: <strong class="text-success"><?= number_format($lich['gia_nguoi_lon']) ?> ₫</strong></span>
                                    <span>Trẻ em: <strong class="text-success"><?= number_format($lich['gia_tre_em']) ?> ₫</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KHỐI 2: NHÂN SỰ -->
            <div class="col-md-5 mb-4">
                <div class="card border-info shadow-sm h-100">
                    <div class="card-header bg-info text-white fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users me-2"></i> Nhân sự phân bổ</span>
                        <a href="index.php?act=lich-phan-cong&id=<?= $lich['id'] ?>" class="btn btn-sm btn-light text-info fw-bold">Quản lý</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Vai trò</th>
                                    <th>Họ tên / Liên hệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($list_nhansu)): foreach ($list_nhansu as $ns): ?>
                                <tr>
                                    <td>
                                        <?php 
                                            $roleClass = match($ns['vai_tro']) {
                                                'HUONG_DAN_VIEN' => 'primary',
                                                'TAI_XE' => 'warning text-dark',
                                                'HAU_CAN' => 'secondary',
                                                default => 'light text-dark'
                                            };
                                            $roleName = match($ns['vai_tro']) {
                                                'HUONG_DAN_VIEN' => 'HDV',
                                                'TAI_XE' => 'Tài xế',
                                                'HAU_CAN' => 'Hậu cần',
                                                default => $ns['vai_tro']
                                            };
                                        ?>
                                        <span class="badge bg-<?= $roleClass ?>"><?= $roleName ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($ns['ho_ten']) ?></div>
                                        <div class="small text-muted"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($ns['so_dien_thoai']) ?></div>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="2" class="text-center text-muted py-3">Chưa phân công nhân sự.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KHỐI 3: DỊCH VỤ ĐÃ ĐẶT -->
            <div class="col-md-7 mb-4">
                <div class="card border-success shadow-sm h-100">
                    <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-concierge-bell me-2"></i> Dịch vụ đã đặt</span>
                        <!-- Nếu có trang quản lý dịch vụ riêng thì link vào đây -->
                        <a href="index.php?act=lich-phan-cong&id=<?= $lich['id'] ?>" class="btn btn-sm btn-light text-success fw-bold">Quản lý</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Loại</th>
                                        <th>Chi tiết & NCC</th>
                                        <th class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($list_dichvu)): foreach ($list_dichvu as $dv): ?>
                                    <tr>
                                        <td><small class="fw-bold text-secondary"><?= $dv['loai_dich_vu'] ?></small></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($dv['chi_tiet']) ?></div>
                                            <div class="small text-muted">NCC: <?= htmlspecialchars($dv['ten_don_vi']) ?></div>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                                $stt = $dv['trang_thai_dat'] ?? 'CHO_XAC_NHAN';
                                                $sttClass = match($stt) {
                                                    'DA_XAC_NHAN' => 'success',
                                                    'CHO_XAC_NHAN' => 'warning text-dark',
                                                    'HUY' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>
                                            <span class="badge bg-<?= $sttClass ?>"><?= $stt ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="3" class="text-center text-muted py-3">Chưa đặt dịch vụ nào.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KHỐI 4: GHI CHÚ -->
            <div class="col-12 mb-4">
                <div class="card border-secondary shadow-sm">
                    <div class="card-header bg-secondary text-white fw-bold">
                        <i class="fas fa-sticky-note me-2"></i> Ghi chú nội bộ
                    </div>
                    <div class="card-body">
                        <?php if (!empty($lich['ghi_chu'])): ?>
                            <p class="mb-0 fst-italic"><?= nl2br(htmlspecialchars($lich['ghi_chu'])) ?></p>
                        <?php else: ?>
                            <p class="mb-0 text-muted">Không có ghi chú đặc biệt.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>