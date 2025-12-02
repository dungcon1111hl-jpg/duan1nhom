<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Điểm danh khách</h1>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL . "?act=diem-danh-store" ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Chọn booking (khách đã đặt)</label>
            <select name="booking_id" class="form-select" required>
                <option value="">-- Chọn booking --</option>
                <?php foreach ($bookings as $b): 
                    $labelName = $b['khach_ho_ten'] ?? ($b['snapshot_kh_ho_ten'] ?? ('Khách #' . ($b['khach_hang_id'] ?? '')));
                    $labelTour = $b['ten_tour'] ?? ('Tour #' . ($b['tour_id'] ?? ''));
                ?>
                    <option value="<?= htmlspecialchars($b['id']) ?>">
                        <?= htmlspecialchars($labelName) ?> — <?= htmlspecialchars($labelTour) ?>
                        (Lịch: <?= htmlspecialchars($b['lich_id']) ?>, SL: <?= htmlspecialchars($b['so_luong_khach']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="trang_thai" class="form-select">
                <option value="CO_MAT">Có mặt</option>
<option value="VANG_MAT">Vắng</option>
<option value="DEN_TRE">Đến trễ</option>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh Check-in</label>
            <input type="file" name="hinh_anh" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Điểm danh</button>
        <a href="<?= BASE_URL . "?act=diem-danh-list" ?>" class="btn btn-primary ms-2">Xem danh sách điểm danh</a>
    </form>
</div>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>
