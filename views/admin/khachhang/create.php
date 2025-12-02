<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <div>
                    <h1 class="m-0 text-primary font-weight-bold">Thêm Khách Hàng Mới</h1>
                    <ol class="breadcrumb mb-0 mt-1">
                        <li class="breadcrumb-item"><a href="index.php?act=khachhang-list" class="text-decoration-none">Danh sách</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 font-weight-normal"><i class="fas fa-user-plus me-2"></i>Thông tin khách hàng</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="index.php?act=khachhang-store" method="POST">
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="ho_ten" id="ho_ten" class="form-control" placeholder="Nhập họ tên" required>
                                            <label for="ho_ten">Họ và tên <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" placeholder="Nhập số điện thoại" required>
                                            <label for="so_dien_thoai">Số điện thoại <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com">
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control">
                                            <label for="ngay_sinh">Ngày sinh</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-secondary">Giới tính</label>
                                    <div class="d-flex gap-4 border rounded p-3 bg-light">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gioi_tinh" id="genderMale" value="1" checked>
                                            <label class="form-check-label" for="genderMale">Nam</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gioi_tinh" id="genderFemale" value="0">
                                            <label class="form-check-label" for="genderFemale">Nữ</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-4">
                                    <textarea name="dia_chi" id="dia_chi" class="form-control" style="height: 100px" placeholder="Nhập địa chỉ"></textarea>
                                    <label for="dia_chi">Địa chỉ</label>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?act=khachhang-list" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-1"></i> Hủy bỏ
                                    </a>
                                    <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                                        <i class="fas fa-save me-1"></i> Lưu khách hàng
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<style>
    /* CSS Tùy chỉnh */
    .card {
        border-radius: 12px;
    }
    .form-floating > label {
        color: #6c757d;
        font-size: 0.95rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .btn-success {
        background-color: #198754;
    }
</style>
<?php require_once ROOT . '/views/footer.php'; ?>