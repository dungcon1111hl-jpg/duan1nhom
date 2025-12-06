<?php require_once ROOT . '/views/header.php'; ?> 
<div class="container-fluid px-4">
    <h1 class="mt-4">Nhật Ký Tour</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
        <li class="breadcrumb-item active">Chọn Tour</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Danh sách Tour đang hoạt động
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên Tour</th>
                        <th>Mã Tour</th>
                        <th>Thời gian</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ds_tour)): ?>
                        <?php foreach ($ds_tour as $tour): ?>
                        <tr>
                            <td><?= $tour['id'] ?></td>
                            <td class="fw-bold text-primary"><?= $tour['ten_tour'] ?></td>
                            <td><?= $tour['ma_tour'] ?? '---' ?></td>
                            <td>
                                <?= $tour['ngay_bat_dau'] ?? '...' ?> 
                                <br> 
                                <small class="text-muted">Đến: <?= $tour['ngay_ket_thuc'] ?? '...' ?></small>
                            </td>
                            <td class="text-center">
                                <a href="?act=nhat-ky-tour&id=<?= $tour['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-book-open"></i> Xem Nhật Ký
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Chưa có dữ liệu Tour nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once ROOT . '/views/footer.php'; ?>