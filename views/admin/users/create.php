<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-primary text-white fw-bold py-3">
                    <i class="fas fa-user-plus me-2"></i> Thêm Tài khoản Mới
                </div>
                <div class="card-body p-4">
                    <form action="index.php?act=user-store" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required placeholder="VD: admin">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required placeholder="******">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" required placeholder="VD: Nguyễn Văn A">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="so_dien_thoai" class="form-control" placeholder="09xxxxxxxx">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Phân quyền</label>
                            <select name="role" class="form-select">
                                <option value="staff">Nhân viên (Staff)</option>
                                <option value="guide">Hướng dẫn viên (Guide)</option>
                                <option value="admin">Quản trị viên (Admin)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?act=user-list" class="btn btn-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">Tạo Tài khoản</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>