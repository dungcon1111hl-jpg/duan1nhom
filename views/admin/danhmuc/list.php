<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-list-alt me-2"></i>Quản lý Danh mục Tour</h2>
            <a href="index.php?act=danhmuc-create" class="btn btn-success shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm Danh mục
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tên danh mục</th>
                            <th>Mã danh mục</th>
                            <th>Mô tả</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($danh_mucs)): foreach ($danh_mucs as $dm): ?>
                        <tr>
                            <td><?= htmlspecialchars($dm['ten_danh_muc']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($dm['ma_danh_muc']) ?></span></td>
                            <td class="text-muted small"><?= htmlspecialchars($dm['mo_ta']) ?></td>
                            <td class="text-end">
                                <a href="index.php?act=danhmuc-edit&id=<?= $dm['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="index.php?act=danhmuc-delete&id=<?= $dm['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa danh mục này?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="4" class="text-center py-4">Chưa có danh mục nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>