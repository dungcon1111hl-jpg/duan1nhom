<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4 fw-bold text-success">Thêm Danh mục mới</h2>
        
        <div class="card shadow-sm mt-3" style="max-width: 600px;">
            <div class="card-body">
                <form action="index.php?act=danhmuc-store" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên danh mục</label>
                        <input type="text" name="ten_danh_muc" class="form-control" required placeholder="VD: Tour Quốc Tế">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mã danh mục (Viết liền, không dấu) <span class="text-danger">*</span></label>
                        <input type="text" name="ma_danh_muc" class="form-control" required placeholder="VD: QUOC_TE">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea name="mo_ta" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success fw-bold">Lưu Danh Mục</button>
                    <a href="index.php?act=danhmuc-list" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>