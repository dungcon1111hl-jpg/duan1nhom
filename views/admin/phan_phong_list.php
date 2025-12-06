<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Danh sách phòng đã phân</h1>

    <!-- Nút quay lại phân phòng -->
    <a href="<?= BASE_URL . "?act=phan-phong-create&lich_id=" . htmlspecialchars($_GET['lich_id']) ?>" 
       class="btn btn-primary mb-3">
        + Phân phòng mới
    </a>

    <!-- FLASH MESSAGE -->
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-hotel me-1"></i>
            Phòng khách sạn đã phân
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Số phòng</th>
                        <th>Khách</th>
                        <th>Loại phòng</th>
                        <th>Số người</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list)): ?>
                        <?php foreach ($list as $row): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['so_phong']) ?></strong></td>
                                <td><?= htmlspecialchars($row['ten_khach']) ?></td>
                                <td><?= htmlspecialchars($row['loai_phong']) ?></td>
                                <td><?= htmlspecialchars($row['so_nguoi']) ?></td>
                                <td><?= htmlspecialchars($row['ghi_chu']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i>Chưa có dữ liệu phân phòng cho lịch này.</i>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>
