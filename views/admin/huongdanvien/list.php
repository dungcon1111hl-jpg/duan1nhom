<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-users me-2"></i>Quản lý Hướng dẫn viên</h2>
            <a href="index.php?act=hdv-create" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm mới
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Thông tin HDV</th>
                                <th>Liên hệ</th>
                                <th>Kỹ năng / Kinh nghiệm</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($hdvs)): foreach ($hdvs as $hdv): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($hdv['anh_dai_dien'])): ?>
                                            <img src="<?= BASE_URL . $hdv['anh_dai_dien'] ?>" class="rounded-circle me-3 border" width="50" height="50" style="object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($hdv['ho_ten']) ?></div>
                                            <small class="text-muted">
                                                <?= $hdv['gioi_tinh'] ?> 
                                                <?= !empty($hdv['ngay_sinh']) ? ' - ' . date('d/m/Y', strtotime($hdv['ngay_sinh'])) : '' ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-bold"><i class="fas fa-phone-alt me-1 text-success"></i> <?= $hdv['so_dien_thoai'] ?></div>
                                    <div class="small text-muted"><?= $hdv['email'] ?></div>
                                </td>
                                <td>
                                    <?php if($hdv['ngon_ngu']): ?>
                                        <span class="badge bg-info text-dark mb-1"><?= $hdv['ngon_ngu'] ?></span>
                                    <?php endif; ?>
                                    <div class="small text-muted">Kinh nghiệm: <?= $hdv['kinh_nghiem'] ?? 0 ?> năm</div>
                                </td>
                                <td>
                                    <?php if ($hdv['trang_thai'] == 'DANG_LAM_VIEC'): ?>
                                        <span class="badge bg-success">Đang làm việc</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nghỉ việc</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="index.php?act=hdv-edit&id=<?= $hdv['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?act=hdv-delete&id=<?= $hdv['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa HDV này?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu hướng dẫn viên.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>  