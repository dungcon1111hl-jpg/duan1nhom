<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-warning text-white fw-bold py-3">
                    <i class="fas fa-user-edit me-2"></i> Cập nhật Tài khoản
                </div>
                <div class="card-body">
                    <form action="index.php?act=user-update" method="POST">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên đăng nhập</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu mới (Để trống nếu không đổi)</label>
                            <input type="password" name="password" class="form-control" placeholder="******">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="so_dien_thoai" class="form-control" value="<?= htmlspecialchars($user['so_dien_thoai']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Phân quyền</label>
                            <select name="role" class="form-select">
                                <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Nhân viên</option>
                                <option value="guide" <?= $user['role'] == 'guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white">Cập nhật</button>
                            <a href="index.php?act=user-list" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>