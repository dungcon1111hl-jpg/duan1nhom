<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h1>Lịch sử điểm danh</h1>
        <a href="<?= BASE_URL . "?act=diem-danh" ?>" class="btn btn-primary">
            <i class="fas fa-clipboard-check"></i> Quay lại Điểm danh
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Dữ liệu đã lưu trong hệ thống
        </div>
        <div class="card-body">
          <table class="table table-bordered table-striped" id="datatablesSimple">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tour</th>
                        <th>Lịch ID</th>
                        <th>Trạng thái</th>
                        <th>Thời gian ghi nhận</th>
                        <th>Ảnh minh chứng</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($diemDanh as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['ten_khach'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['ten_tour'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['lich_id']) ?></td>
                            <td class="text-center">
                                <?php
                                $status = $row['trang_thai'] ?? '';
                                $badgeClass = match($status) {
                                    'CO_MAT' => 'bg-success',
                                    'VANG_MAT' => 'bg-danger',
                                    'DEN_TRE' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                                $statusText = match($status) {
                                    'CO_MAT' => 'Có Mặt',
                                    'VANG_MAT' => 'Vắng Mặt',
                                    'DEN_TRE' => 'Đến Trễ',
                                    default => 'Chưa Rõ'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                            </td>
                            <td><?= date('H:i d/m/Y', strtotime($row['thoi_gian'])) ?></td>
                            
                            <td class="text-center">
                                <?php 
                                // ⭐ SỬA LỖI: Dùng tên cột 'hinh_anh' thay vì 'image_url' ⭐
                                $imageUrl = $row['hinh_anh'] ?? null; 
                                ?>
                                <?php if (!empty($imageUrl)): ?>
                                    <a href="<?= BASE_URL . $imageUrl ?>" target="_blank">
                                        <img src="<?= BASE_URL . $imageUrl ?>" 
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" 
                                            alt="Ảnh minh chứng">
                                    </a>
                                <?php else: ?>
                                    (Không có)
                                <?php endif; ?>
                            </td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>