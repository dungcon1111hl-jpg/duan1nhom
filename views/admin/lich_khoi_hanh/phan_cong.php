<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-primary"><i class="fas fa-users-cog me-2"></i>Phân Công Nhân Sự</h2>
                <div class="text-muted">
                    Lịch trình: <strong>#<?= $lich['id'] ?></strong> 
                    (<?= date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) ?> - <?= date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) ?>)
                </div>
            </div>
            <a href="index.php?act=lich-khoi-hanh" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại Lịch
            </a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="fas fa-user-plus me-1"></i> Thêm nhân sự
                    </div>
                    <div class="card-body">
                        <form action="index.php?act=lich-phan-cong-store" method="POST">
                            <input type="hidden" name="lich_id" value="<?= $lich['id'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Vai trò</label>
                                <select name="vai_tro" class="form-select" required id="roleSelect" onchange="toggleHDVSelect()">
                                    <option value="HUONG_DAN_VIEN">Hướng dẫn viên</option>
                                    <option value="TAI_XE">Tài xế</option>
                                    <option value="DIEU_HANH">Điều hành Tour</option>
                                    <option value="KHAC">Khác</option>
                                </select>
                            </div>

                            <div class="mb-3" id="hdvSelectGroup">
                                <label class="form-label fw-bold">Chọn HDV có sẵn</label>
                                <select class="form-select" onchange="fillHDVInfo(this)">
                                    <option value="">-- Chọn từ danh sách --</option>
                                    <?php foreach ($dsHDV as $hdv): ?>
                                        <option value="<?= $hdv['ho_ten'] ?>" data-phone="<?= $hdv['so_dien_thoai'] ?>">
                                            <?= $hdv['ho_ten'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Chọn để tự điền tên và SĐT bên dưới.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="ho_ten" id="inputTen" class="form-control" required placeholder="Nhập tên nhân sự...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="so_dien_thoai" id="inputSDT" class="form-control" placeholder="09...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày làm việc</label>
                                <input type="date" name="ngay_nhan_viec" class="form-control" value="<?= $lich['ngay_khoi_hanh'] ?>">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success fw-bold">Lưu Phân Công</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-list me-1"></i> Danh sách nhân sự đã phân công
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Vai trò</th>
                                    <th>Họ tên</th>
                                    <th>SĐT</th>
                                    <th>Ngày làm</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($list_nhansu)): foreach ($list_nhansu as $ns): ?>
                                <tr>
                                    <td>
                                        <?php 
                                            $badges = [
                                                'HUONG_DAN_VIEN' => 'bg-info text-dark',
                                                'TAI_XE' => 'bg-warning text-dark',
                                                'DIEU_HANH' => 'bg-primary',
                                                'KHAC' => 'bg-secondary'
                                            ];
                                            $labels = [
                                                'HUONG_DAN_VIEN' => 'HDV',
                                                'TAI_XE' => 'Tài xế',
                                                'DIEU_HANH' => 'Điều hành',
                                                'KHAC' => 'Khác'
                                            ];
                                            $roleCode = $ns['vai_tro'];
                                        ?>
                                        <span class="badge <?= $badges[$roleCode] ?? 'bg-secondary' ?>">
                                            <?= $labels[$roleCode] ?? $roleCode ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold"><?= htmlspecialchars($ns['ho_ten']) ?></td>
                                    <td><?= htmlspecialchars($ns['so_dien_thoai']) ?></td>
                                    <td><?= !empty($ns['ngay_nhan_viec']) ? date('d/m/Y', strtotime($ns['ngay_nhan_viec'])) : '-' ?></td>
                                    <td class="text-end">
                                        <a href="index.php?act=lich-phan-cong-delete&id=<?= $ns['id'] ?>&lich_id=<?= $lich['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa nhân sự này?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có nhân sự nào được phân công.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Tự động điền thông tin khi chọn HDV có sẵn
function fillHDVInfo(select) {
    var option = select.options[select.selectedIndex];
    if (option.value) {
        document.getElementById('inputTen').value = option.value;
        document.getElementById('inputSDT').value = option.getAttribute('data-phone');
    }
}

// Ẩn hiện ô chọn HDV dựa trên vai trò
function toggleHDVSelect() {
    var role = document.getElementById('roleSelect').value;
    var group = document.getElementById('hdvSelectGroup');
    if (role === 'HUONG_DAN_VIEN') {
        group.style.display = 'block';
    } else {
        group.style.display = 'none';
    }
}
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>