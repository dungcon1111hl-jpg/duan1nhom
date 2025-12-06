<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Phân phòng khách sạn</h1>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL . "?act=phan-phong-store" ?>" method="POST">

        <input type="hidden" name="lich_id" value="<?= htmlspecialchars($lich_id) ?>">

        <div class="mb-3">
            <label class="form-label">Chọn khách để phân phòng</label>
            <select name="khach_id" class="form-select" required>
                <option value="">-- Chọn khách --</option>
                <?php 
                $list_khach = $list_khach ?? []; // Khởi tạo nếu chưa tồn tại
                foreach ($list_khach as $k): 
                ?>
                    <option value="<?= $k['khach_id'] ?>">
                        <?= htmlspecialchars($k['ten_khach']) ?> 
                        (ID: <?= $k['khach_id'] ?>) - Trạng thái: <?= htmlspecialchars($k['trang_thai']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
        </div>

        <div class="mb-3">
            <label class="form-label">Số phòng</label>
            <input type="number" name="so_phong" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Loại phòng</label>
            <select name="loai_phong" class="form-select">
                <option value="Đơn">Phòng Đơn</option>
                <option value="Đôi">Phòng Đôi</option>
                <option value="Ba">Phòng 3 người</option>
                <option value="Gia đình">Phòng Gia đình</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Số người</label>
            <input type="number" name="so_nguoi" class="form-control" min="1" value="1">
        </div>

        <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Lưu phân phòng</button>
        <a href="<?= BASE_URL . "?act=phan-phong-list&lich_id=" . $lich_id ?>" class="btn btn-primary ms-2">
            Xem danh sách phòng
        </a>
    </form>
</div>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>