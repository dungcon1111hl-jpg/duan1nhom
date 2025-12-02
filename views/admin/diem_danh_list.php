<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Danh sách điểm danh</h1>

    <a href="<?= BASE_URL . "?act=diem-danh" ?>" class="btn btn-success mb-3">+ Điểm danh mới</a>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên khách</th>
                <th>Tên tour</th>
                <th>Lịch ID</th>
                <th>Khách ID</th>
                <th>Trạng thái</th>
                <th>Ảnh</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($diemDanh as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['ten_khach'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['ten_tour'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['lich_id']) ?></td>
                    <td><?= htmlspecialchars($row['khach_id']) ?></td>

                    <td>
                        <?php
                        $st = $row['trang_thai'] ?? 'VANG_MAT';

                        if ($st === 'CO_MAT') {
                            echo '<span class="badge bg-success">Có mặt</span>';
                        } elseif ($st === 'DEN_TRE') {
                            echo '<span class="badge bg-warning text-dark">Đến trễ</span>';
                        } else {
                            echo '<span class="badge bg-danger">Vắng</span>';
                        }
                        ?>
                    </td>

                    <td>
                        <?php if (!empty($row['hinh_anh'])): ?>
                            <img src="<?= BASE_URL . "uploads/diem_danh/" . htmlspecialchars($row['hinh_anh']) ?>" width="80" alt="ảnh">
                        <?php else: ?>
                            <i>Không có ảnh</i>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($row['thoi_gian']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>
