<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-danger"><i class="fas fa-coins me-2"></i>Quản lý Chi Phí</h2>
                <div class="text-muted">
                    Tour: <strong><?= htmlspecialchars($lich['ten_tour']) ?></strong> 
                    (#<?= $lich_id ?>)
                </div>
            </div>
            <div>
                <a href="index.php?act=lich-khoi-hanh" class="btn btn-secondary me-2">Quay lại Lịch</a>
                <a href="index.php?act=chiphi-create&lich_id=<?= $lich_id ?>" class="btn btn-danger shadow-sm">
                    <i class="fas fa-plus me-1"></i> Thêm khoản chi
                </a>
            </div>
        </div>

        <!-- Tổng chi phí tự động -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-danger text-white h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-bold">Tổng chi phí thực tế</div>
                            <div class="fs-2 fw-bold"><?= number_format($tong_chi) ?> ₫</div>
                        </div>
                        <i class="fas fa-wallet fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Nội dung</th>
                            <th>Loại</th>
                            <th>Số tiền</th>
                            <th>Ảnh</th>
                            <th>Ngày chi</th>
                            <th class="text-end pe-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($chiphis)): foreach($chiphis as $cp): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold"><?= htmlspecialchars($cp['ten_chi_phi']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($cp['ghi_chu']) ?></small>
                            </td>
                            <td><span class="badge bg-secondary"><?= $cp['loai_chi_phi'] ?></span></td>
                            <td class="fw-bold text-danger">- <?= number_format($cp['so_tien']) ?> ₫</td>
                            <td>
                                <?php if($cp['hinh_anh']): ?>
                                    <a href="<?= BASE_URL . $cp['hinh_anh'] ?>" target="_blank"><i class="fas fa-image"></i> Xem</a>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($cp['ngay_chi'])) ?></td>
                            <td class="text-end pe-3">
                                <a href="index.php?act=chiphi-edit&id=<?= $cp['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="index.php?act=chiphi-delete&id=<?= $cp['id'] ?>&lich_id=<?= $lich_id ?>" 
                                   class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa khoản này?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>