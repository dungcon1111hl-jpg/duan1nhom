<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-users-cog me-2"></i>Quản lý Tài khoản</h2>
            <a href="index.php?act=user-create" class="btn btn-primary shadow-sm">
                <i class="fas fa-user-plus me-1"></i> Thêm Tài khoản
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Username</th>
                                <th>Họ tên / Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-4">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#<?= $u['id'] ?></td>
                                
                                <td class="fw-bold text-primary"><?= htmlspecialchars($u['username']) ?></td>
                                
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($u['full_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($u['email']) ?></small>
                                </td>

                                <td>
                                    <?php 
                                        $roleMap = [
                                            'admin' => ['danger', 'Quản trị viên'],
                                            'guide' => ['success', 'Hướng dẫn viên'],
                                            'staff' => ['info text-dark', 'Nhân viên']
                                        ];
                                        $role = $roleMap[$u['role']] ?? ['secondary', $u['role']];
                                    ?>
                                    <span class="badge bg-<?= $role[0] ?>"><?= $role[1] ?></span>
                                </td>
                                
                                <td>
                                    <?php if($u['trang_thai'] == 1): ?>
                                        <span class="badge bg-success">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Bị khóa</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end pe-4">
                                    <a href="index.php?act=user-edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?act=user-delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Xóa tài khoản <?= htmlspecialchars($u['username']) ?>?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có tài khoản nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>