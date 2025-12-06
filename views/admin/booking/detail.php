<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h1 class="text-primary fw-bold h3"><i class="fas fa-info-circle me-2"></i>Chi tiết Booking #<?= $booking['id'] ?></h1>
                <a href="<?= BASE_URL ?>?act=booking-list" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-primary text-white fw-bold">Thông tin Đặt Tour</div>
                        <div class="card-body">
                            <p><strong>Tour:</strong> <span class="text-primary fw-bold"><?= htmlspecialchars($booking['snapshot_ten_tour'] ?? '') ?></span></p>
                            <p><strong>Người đặt:</strong> <?= htmlspecialchars($booking['snapshot_kh_ho_ten'] ?? 'Khách vãng lai') ?></p>
                            <p><strong>SĐT:</strong> <?= htmlspecialchars($booking['snapshot_kh_so_dien_thoai'] ?? 'Không có') ?></p>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($booking['snapshot_kh_dia_chi'] ?? 'Chưa cập nhật') ?></p>
                            <p><strong>Ngày đặt:</strong> <?= !empty($booking['ngay_dat']) ? date('d/m/Y H:i', strtotime($booking['ngay_dat'])) : '-' ?></p>
                            <p><strong>Số lượng:</strong> <span class="badge bg-info text-dark"><?= $booking['so_luong_nguoi_lon'] ?> NL</span> - <span class="badge bg-warning text-dark"><?= $booking['so_luong_tre_em'] ?> TE</span></p>
                            
                            <form action="<?= BASE_URL ?>?act=booking-update-status" method="POST" class="mt-3 p-3 bg-light rounded border d-flex gap-2 align-items-center">
                                <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                <strong class="text-nowrap">Trạng thái:</strong>
                                <select name="trang_thai" class="form-select form-select-sm">
                                    <?php 
                                    $statuses = [
                                        'CHO_XU_LY' => 'Chờ xử lý', 
                                        'DA_XAC_NHAN' => 'Đã xác nhận', 
                                        'DA_THANH_TOAN' => 'Đã thanh toán', 
                                        'HOAN_THANH' => 'Hoàn thành', 
                                        'HUY' => 'Hủy'
                                    ];
                                    $currentStatus = $booking['trang_thai'] ?? 'CHO_XU_LY';
                                    foreach($statuses as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= $currentStatus == $key ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-success btn-sm text-nowrap"><i class="fas fa-check"></i> Lưu</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-success text-white fw-bold">Chi tiết Thanh toán</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                <span class="text-muted">Tổng giá trị đơn hàng:</span>
                                <span class="fs-5 fw-bold text-primary"><?= number_format($booking['tong_tien'] ?? 0) ?> ₫</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                <span class="text-muted">Đã thanh toán / Đặt cọc:</span>
                                <span class="fs-5 fw-bold text-success"><?= number_format($booking['da_thanh_toan'] ?? 0) ?> ₫</span>
                            </div>

                            <?php 
                                $tong_tien = $booking['tong_tien'] ?? 0;
                                $da_thanh_toan = $booking['da_thanh_toan'] ?? 0;
                                $con_lai = $tong_tien - $da_thanh_toan; 
                            ?>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Số tiền còn lại:</span>
                                <span class="fs-4 fw-bold <?= $con_lai > 0 ? 'text-danger' : 'text-secondary' ?>">
                                    <?= number_format($con_lai) ?> ₫
                                </span>
                            </div>

                            <?php if ($con_lai > 0): ?>
                                <div class="alert alert-warning small mb-0 py-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Khách cần thanh toán thêm <strong><?= number_format($con_lai) ?> ₫</strong>.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success small mb-0 py-2">
                                    <i class="fas fa-check-circle me-1"></i> Đơn hàng đã thanh toán đủ.
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-3 text-end">
                                <a href="index.php?act=thanhtoan-list&booking_id=<?= $booking['id'] ?>" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-history"></i> Lịch sử giao dịch
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users me-2 text-primary"></i>Danh sách hành khách đi cùng (<?= !empty($guestList) ? count($guestList) : 0 ?>)</span>
                    <a href="index.php?act=booking-guest-list&id=<?= $booking['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i> Quản lý khách
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th width="20%">Họ tên</th>
                                    <th class="text-center" width="10%">Giới tính</th>
                                    <th class="text-center" width="15%">Ngày sinh</th>
                                    <th width="15%">Giấy tờ</th>
                                    <th>Ghi chú / Yêu cầu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($guestList)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-user-slash fa-2x mb-2 opacity-25"></i><br>
                                            Chưa có danh sách khách tham gia.<br>
                                            <small>(Hãy bấm "Quản lý khách" để thêm)</small>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($guestList as $k => $g): ?>
                                        <tr>
                                            <td class="text-center"><?= $k + 1 ?></td>
                                            
                                            <td class="fw-bold text-dark">
                                                <?= htmlspecialchars($g['ho_ten'] ?? 'Không tên') ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?php 
                                                    // Xử lý hiển thị giới tính (Hỗ trợ cả NAM/NU và 1/0)
                                                    $gt = strtoupper($g['gioi_tinh'] ?? '');
                                                    if ($gt == 'NAM' || $gt == '1') echo '<span class="badge bg-info bg-opacity-10 text-info">Nam</span>';
                                                    elseif ($gt == 'NU' || $gt == 'NỮ' || $gt == '0') echo '<span class="badge bg-danger bg-opacity-10 text-danger">Nữ</span>';
                                                    else echo '<span class="text-muted">-</span>';
                                                ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?= !empty($g['ngay_sinh']) ? date('d/m/Y', strtotime($g['ngay_sinh'])) : '-' ?>
                                            </td>
                                            
                                            <td>
                                                <?= htmlspecialchars($g['so_giay_to'] ?? '-') ?>
                                            </td>
                                            
                                            <td class="small">
                                                <?php if(!empty($g['ghi_chu'])): ?>
                                                    <div><i class="fas fa-sticky-note me-1 text-muted"></i> <?= htmlspecialchars($g['ghi_chu']) ?></div>
                                                <?php endif; ?>
                                                
                                                <?php if(!empty($g['yeu_cau_dac_biet'])): ?>
                                                    <div class="text-danger fw-bold mt-1">
                                                        <i class="fas fa-star me-1"></i> <?= htmlspecialchars($g['yeu_cau_dac_biet']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<?php require_once ROOT . '/views/footer.php'; ?>