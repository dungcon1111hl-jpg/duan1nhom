<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h1><i class="fas fa-users me-2"></i> Khách tham gia (Booking #<?= $booking['id'] ?>)</h1>
                <a href="<?= BASE_URL ?>?act=booking-detail&id=<?= $booking['id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại đơn hàng
                </a>
            </div>

            <div class="alert alert-info">
                <strong>Tour:</strong> <?= htmlspecialchars($booking['ten_tour']) ?> | 
                <strong>Người đặt:</strong> <?= htmlspecialchars($booking['snapshot_kh_ho_ten']) ?>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Danh sách người đi cùng (<?= count($guestList) ?>)</span>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal">
                        <i class="fas fa-plus"></i> Thêm Khách
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Họ Tên</th>
                                <th>Giới tính</th>
                                <th>Ngày sinh</th>
                                <th>Giấy tờ</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($guestList)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-3">Chưa có khách nào.</td></tr>
                            <?php else: ?>
                                <?php foreach ($guestList as $key => $guest): ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($guest['ho_ten']) ?></td>
                                        <td><?= $guest['gioi_tinh'] == 1 ? 'Nam' : 'Nữ' ?></td>
                                        <td><?= $guest['ngay_sinh'] ? date('d/m/Y', strtotime($guest['ngay_sinh'])) : '-' ?></td>
                                        <td><?= htmlspecialchars($guest['so_giay_to']) ?></td>
                                        <td>
                                            <?= htmlspecialchars($guest['ghi_chu']) ?>
                                            <?php if($guest['yeu_cau_dac_biet']): ?>
                                                <br><small class="text-danger">Yêu cầu: <?= htmlspecialchars($guest['yeu_cau_dac_biet']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                       <td class="text-center">
    <a href="<?= BASE_URL ?>?act=booking-guest-edit&id=<?= $guest['id'] ?>&booking_id=<?= $booking['id'] ?>" 
       class="btn btn-sm btn-warning me-1" title="Sửa">
        <i class="fas fa-edit text-white"></i>
    </a>

    <a href="<?= BASE_URL ?>?act=booking-guest-delete&id=<?= $guest['id'] ?>&booking_id=<?= $booking['id'] ?>" 
       class="btn btn-sm btn-danger" onclick="return confirm('Xóa khách này?')" title="Xóa">
        <i class="fas fa-trash"></i>
    </a>
</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="addGuestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Khách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?act=booking-guest-store" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                    <div class="mb-3">
                        <label>Họ tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Giới tính</label>
                            <select name="gioi_tinh" class="form-select">
                                <option value="1">Nam</option>
                                <option value="0">Nữ</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Giấy tờ (CCCD/Passport)</label>
                        <input type="text" name="so_giay_to" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Yêu cầu đặc biệt</label>
                        <textarea name="yeu_cau_dac_biet" class="form-control" rows="1"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Ghi chú</label>
                        <textarea name="ghi_chu" class="form-control" rows="1"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/footer.php'; ?>