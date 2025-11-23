<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-primary"><i class="fas fa-images me-2"></i>Album Ảnh Tour</h2>
                <div class="text-muted">Tour: <strong><?= htmlspecialchars($tour['ten_tour']) ?></strong> (Mã: <?= $tour['ma_tour'] ?>)</div>
            </div>
            <a href="index.php?act=tours" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold text-success py-3">
                        <i class="fas fa-upload me-2"></i>Tải ảnh lên
                    </div>
                    <div class="card-body">
                        <form action="index.php?act=tour-images-store" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Chọn ảnh (Có thể chọn nhiều)</label>
                                <input type="file" name="files[]" class="form-control" multiple required accept="image/*" onchange="previewMultiple(this)">
                                <div class="form-text">Định dạng: JPG, PNG. Tối đa 5MB/ảnh.</div>
                            </div>

                            <div id="preview-area" class="d-flex flex-wrap gap-2 mb-3"></div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success fw-bold">
                                    <i class="fas fa-cloud-upload-alt me-1"></i> Bắt đầu Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Tổng số ảnh</div>
                            <div class="fs-4 fw-bold"><?= count($list_anh) ?></div>
                        </div>
                        <i class="fas fa-camera fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="fas fa-th me-2"></i>Thư viện ảnh
                    </div>
                    <div class="card-body">
                        <?php if (empty($list_anh)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-images fa-3x mb-3 opacity-25"></i>
                                <p>Chưa có ảnh nào trong thư viện.</p>
                            </div>
                        <?php else: ?>
                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                                <?php foreach ($list_anh as $img): ?>
                                    <div class="col">
                                        <div class="card h-100 border shadow-sm position-relative group-action">
                                            <div class="ratio ratio-1x1">
                                                <img src="<?= BASE_URL . $img['duong_dan'] ?>" 
                                                     class="card-img-top object-fit-cover" 
                                                     alt="Tour Image"
                                                     style="cursor: zoom-in"
                                                     onclick="viewImage('<?= BASE_URL . $img['duong_dan'] ?>')">
                                            </div>
                                            
                                            <a href="index.php?act=tour-images-delete&id=<?= $img['id'] ?>&tour_id=<?= $tour['id'] ?>" 
                                               class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 shadow-sm btn-delete"
                                               onclick="return confirm('Xóa ảnh này?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 text-center">
        <img src="" id="modalImage" class="img-fluid rounded shadow-lg">
      </div>
    </div>
  </div>
</div>

<script>
    // Preview nhiều ảnh trước khi upload
    function previewMultiple(input) {
        const container = document.getElementById('preview-area');
        container.innerHTML = ''; // Clear cũ
        
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded border';
                    img.style.width = '50px';
                    img.style.height = '50px';
                    img.style.objectFit = 'cover';
                    container.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // Xem ảnh lớn
    function viewImage(src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }
</script>

<style>
    /* Hiệu ứng hover nút xóa */
    .btn-delete { opacity: 0; transition: opacity 0.2s; }
    .group-action:hover .btn-delete { opacity: 1; }
</style>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>