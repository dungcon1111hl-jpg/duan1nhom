<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-users-cog me-2"></i>Quản lý Tài khoản</h2>
            <a href="index.php?act=user-create" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm Tài khoản
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Username</th>
                                <th>Họ tên / Email</th>
                                <th>Vai trò</th>
                                <th>Liên hệ</th>
                                <th class="text-end pe-4">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?= htmlspecialchars($u['username']) ?></td>
                                
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($u['full_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($u['email']) ?></small>
                                </td>

                                <td>
                                    <?php 
                                        $roleLabel = match($u['role']) {
                                            'admin' => ['danger', 'Quản trị viên'],
                                            'guide' => ['info text-dark', 'Hướng dẫn viên'],
                                            'staff' => ['success', 'Nhân viên'],
                                            default => ['secondary', $u['role']]
                                        };
                                    ?>
                                    <span class="badge bg-<?= $roleLabel[0] ?>"><?= $roleLabel[1] ?></span>
                                </td>
                                
                                <td><?= htmlspecialchars($u['so_dien_thoai'] ?? '-') ?></td>

                                <td class="text-end pe-4">
                                    <a href="index.php?act=user-edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?act=user-delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Xóa tài khoản này?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>