<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h1 class="text-primary fw-bold h3"><i class="fas fa-users me-2"></i> Khách tham gia (Booking #<?= $booking['id'] ?>)</h1>
                <a href="<?= BASE_URL ?>?act=booking-detail&id=<?= $booking['id'] ?>" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại đơn hàng
                </a>
            </div>

            <div class="alert alert-info border-info shadow-sm">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Tour:</strong> <?= htmlspecialchars($booking['snapshot_ten_tour'] ?? $booking['ten_tour_hien_tai'] ?? 'Chưa xác định') ?> 
                <span class="mx-2">|</span> 
                <strong>Người đặt:</strong> <?= htmlspecialchars($booking['snapshot_kh_ho_ten'] ?? 'Khách vãng lai') ?>
            </div>

            <div class="card mb-4 shadow border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
                    <span class="text-primary"><i class="fas fa-list me-1"></i> Danh sách người đi cùng (<?= !empty($guestList) ? count($guestList) : 0 ?>)</span>
                    
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-success fw-bold me-2" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                            <i class="fas fa-file-csv me-1"></i> Nhập từ Excel (CSV)
                        </button>
                        <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#addGuestModal">
                            <i class="fas fa-plus me-1"></i> Thêm Khách
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="50">#</th>
                                    <th>Họ Tên</th>
                                    <th class="text-center">Giới tính</th>
                                    <th class="text-center">Ngày sinh</th>
                                    <th>Giấy tờ (CCCD)</th>
                                    <th>Ghi chú / Yêu cầu</th>
                                    <th class="text-center" width="120">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($guestList)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i><br>
                                            Chưa có khách nào trong danh sách.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($guestList as $key => $guest): ?>
                                        <tr>
                                            <td class="text-center fw-bold text-secondary"><?= $key + 1 ?></td>
                                            
                                            <td class="fw-bold text-dark">
                                                <?= htmlspecialchars($guest['ho_ten'] ?? '') ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?php 
                                                    $gt = $guest['gioi_tinh'] ?? '';
                                                    if($gt == '1' || $gt == 'NAM') echo '<span class="badge bg-info bg-opacity-10 text-info">Nam</span>';
                                                    elseif($gt == '0' || $gt == 'NU') echo '<span class="badge bg-danger bg-opacity-10 text-danger">Nữ</span>';
                                                    else echo '<span class="text-muted small">--</span>';
                                                ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?= !empty($guest['ngay_sinh']) ? date('d/m/Y', strtotime($guest['ngay_sinh'])) : '-' ?>
                                            </td>
                                            
                                            <td>
                                                <?= htmlspecialchars($guest['so_giay_to'] ?? '-') ?>
                                            </td>
                                            
                                            <td class="small">
                                                <?= htmlspecialchars($guest['ghi_chu'] ?? '') ?>
                                                <?php if(!empty($guest['yeu_cau_dac_biet'])): ?>
                                                    <div class="text-danger mt-1 fw-bold">
                                                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($guest['yeu_cau_dac_biet']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="<?= BASE_URL ?>?act=booking-guest-edit&id=<?= $guest['id'] ?>&booking_id=<?= $booking['id'] ?>" 
                                                       class="btn btn-sm btn-outline-warning" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>?act=booking-guest-delete&id=<?= $guest['id'] ?>&booking_id=<?= $booking['id'] ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Xóa khách này khỏi danh sách?')" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
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

<div class="modal fade" id="addGuestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i>Thêm Khách Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?act=booking-guest-store" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" class="form-control" required placeholder="Nhập họ tên khách...">
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Giới tính</label>
                            <select name="gioi_tinh" class="form-select">
                                <option value="1">Nam</option>
                                <option value="0">Nữ</option>
                                <option value="2">Khác</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giấy tờ tùy thân</label>
                        <input type="text" name="so_giay_to" class="form-control" placeholder="CCCD / Hộ chiếu...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Yêu cầu đặc biệt</label>
                        <textarea name="yeu_cau_dac_biet" class="form-control" rows="2" placeholder="Ăn chay, dị ứng..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú</label>
                        <textarea name="ghi_chu" class="form-control" rows="1"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">Lưu Thông Tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="importExcelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-csv me-2"></i>Nhập từ file CSV</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?act=booking-guest-import" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                    
                    <div class="alert alert-info small">
                        <strong>Hướng dẫn:</strong><br>
                        1. Tạo file Excel và <b>Lưu dưới dạng CSV (Comma delimited)</b>.<br>
                        2. Cấu trúc cột: <b>Họ tên | Giới tính (Nam/Nu) | Ngày sinh | CCCD | Ghi chú</b><br>
                        3. Dòng đầu tiên là tiêu đề (sẽ bị bỏ qua).
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn file CSV</label>
                        <input type="file" name="file_excel" class="form-control" required accept=".csv">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success fw-bold">Tải lên</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/footer.php'; ?>