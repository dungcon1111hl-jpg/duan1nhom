<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Cập nhật Khách Hàng</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.php?act=khachhang-list">Danh sách</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['error']); endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['success']); endif; ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form action="index.php?act=khachhang-update" method="POST">
                        <input type="hidden" name="id" value="<?= $kh['id'] ?? '' ?>"> 

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="ho_ten" id="ho_ten" class="form-control" 
                                           value="<?= htmlspecialchars($kh['ho_ten'] ?? '') ?>" placeholder="Nhập họ tên" required>
                                    <label for="ho_ten">Họ và tên</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" 
                                           value="<?= htmlspecialchars($kh['so_dien_thoai'] ?? '') ?>" placeholder="Nhập số điện thoại" required>
                                    <label for="so_dien_thoai">Số điện thoại</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" name="email" id="email" class="form-control" 
                                           value="<?= htmlspecialchars($kh['email'] ?? '') ?>" placeholder="name@example.com">
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control" 
                                           value="<?= $kh['ngay_sinh'] ?? '' ?>">
                                    <label for="ngay_sinh">Ngày sinh</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Giới tính</label>
                            <select name="gioi_tinh" class="form-select">
                                <option value="1" <?= ($kh['gioi_tinh'] == 1) ? 'selected' : '' ?>>Nam</option>
                                <option value="0" <?= ($kh['gioi_tinh'] == 0) ? 'selected' : '' ?>>Nữ</option>
                                <option value="" <?= ($kh['gioi_tinh'] === null || $kh['gioi_tinh'] === '') ? 'selected' : '' ?>>--- Chọn ---</option>
                            </select>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <textarea name="dia_chi" id="dia_chi" class="form-control" style="height: 100px" placeholder="Nhập địa chỉ"><?= htmlspecialchars($kh['dia_chi'] ?? '') ?></textarea>
                            <label for="dia_chi">Địa chỉ</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?act=khachhang-list" class="btn btn-secondary px-4">Hủy</a>
                            <button type="submit" class="btn btn-warning px-4 fw-bold">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once ROOT . '/views/footer.php'; ?>