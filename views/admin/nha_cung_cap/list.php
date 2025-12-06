<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-handshake me-2"></i>Quản lý Nhà Cung Cấp</h2>
            <a href="index.php?act=ncc-create" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm mới
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Tên đơn vị / Dịch vụ</th>
                                <th>Người liên hệ</th>
                                <th>Thông tin liên lạc</th>
                                <th>Ghi chú</th>
                                <th class="text-end pe-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($nccs)): foreach ($nccs as $ncc): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($ncc['ten_don_vi']) ?></div>
                                    <span class="badge bg-info text-dark"><?= $ncc['loai_dich_vu'] ?></span>
                                </td>
                                <td>
                                    <?php if(!empty($ncc['nguoi_lien_he'])): ?>
                                        <i class="fas fa-user-tie text-secondary me-1"></i> <?= htmlspecialchars($ncc['nguoi_lien_he']) ?>
                                    <?php else: ?>
                                        <span class="text-muted small">---</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="small"><i class="fas fa-phone me-1 text-success"></i> <?= htmlspecialchars($ncc['so_dien_thoai']) ?></div>
                                    <?php if(!empty($ncc['email'])): ?>
                                        <div class="small text-muted"><i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($ncc['email']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?= htmlspecialchars($ncc['ghi_chu']) ?></td>
                                <td class="text-end pe-3">
                                    <a href="index.php?act=ncc-edit&id=<?= $ncc['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?act=ncc-delete&id=<?= $ncc['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa nhà cung cấp này?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có nhà cung cấp nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>