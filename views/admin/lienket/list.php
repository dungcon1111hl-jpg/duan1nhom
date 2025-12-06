<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-primary"><i class="fas fa-link me-2"></i>Liên Kết Nhà Cung Cấp</h2>
                <div class="text-muted">
                    Cấu hình NCC cho Tour: <strong><?= htmlspecialchars($tour['ten_tour']) ?></strong>
                </div>
            </div>
            <a href="index.php?act=tours" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại DS Tour
            </a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-success border-bottom-0">
                        <i class="fas fa-plus-circle me-1"></i> Thêm/Cập nhật NCC
                    </div>
                    <div class="card-body bg-light rounded-bottom">
                        <form action="index.php?act=lien-ket-store" method="POST">
                            <input type="hidden" name="tour_id" value="<?= $tour_id ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn Nhà Cung Cấp</label>
                                <select name="ncc_id" class="form-select" required>
                                    <option value="">-- Chọn đơn vị --</option>
                                    <?php foreach($allNCC as $ncc): ?>
                                        <option value="<?= $ncc['id'] ?>">
                                            <?= htmlspecialchars($ncc['ten_don_vi']) ?> (<?= $ncc['loai_dich_vu'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text"><a href="index.php?act=nha-cung-cap" target="_blank">Quản lý NCC</a></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá Net (Vốn) <span class="text-danger">*</span></label>
                                <input type="number" name="gia_net" class="form-control fw-bold text-danger" required placeholder="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá Bán (Dự kiến)</label>
                                <input type="number" name="gia_ban" class="form-control fw-bold text-success" required placeholder="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success fw-bold">Lưu Cấu Hình</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-list-ul me-1"></i> Danh sách NCC đã liên kết
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Nhà Cung Cấp</th>
                                    <th>Loại</th>
                                    <th class="text-end">Giá Vốn</th>
                                    <th class="text-end">Giá Bán</th>
                                    <th class="text-end">Lợi nhuận</th>
                                    <th class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($links)): foreach($links as $lk): 
                                    $loi_nhuan = $lk['gia_ban'] - $lk['gia_net'];
                                ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold"><?= htmlspecialchars($lk['ten_don_vi']) ?></div>
                                        <small class="text-muted"><?= $lk['so_dien_thoai'] ?></small>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= $lk['loai_dich_vu'] ?></span></td>
                                    <td class="text-end text-danger fw-bold"><?= number_format($lk['gia_net']) ?></td>
                                    <td class="text-end text-success fw-bold"><?= number_format($lk['gia_ban']) ?></td>
                                    <td class="text-end fw-bold <?= $loi_nhuan>=0 ? 'text-primary' : 'text-danger' ?>">
                                        <?= number_format($loi_nhuan) ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="index.php?act=lien-ket-delete&id=<?= $lk['id'] ?>&tour_id=<?= $tour_id ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Gỡ bỏ NCC này khỏi tour?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có NCC nào được liên kết.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>