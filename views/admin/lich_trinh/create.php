<?php require ROOT."/views/header.php"; ?>
<div class="container-fluid px-4">
  <h1 class="mt-4">Thêm ngày lịch trình</h1>

  <form method="POST" action="index.php?act=lich-trinh-store">
    <input type="hidden" name="tour_id" value="<?= $tourId ?>">

    <div class="mb-3">
      <label class="form-label">Thứ tự ngày</label>
      <input type="number" name="thu_tu_ngay" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Tiêu đề</label>
      <input name="tieu_de" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Hoạt động</label>
      <textarea name="hoat_dong" class="form-control" rows="4"></textarea>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Giờ bắt đầu</label>
        <input type="time" name="gio_bat_dau" class="form-control">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Giờ kết thúc</label>
        <input type="time" name="gio_ket_thuc" class="form-control">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Ghi chú</label>
      <textarea name="ghi_chu" class="form-control"></textarea>
    </div>

    <button class="btn btn-success">Lưu</button>
    <a class="btn btn-secondary" href="index.php?act=lich-trinh&tour_id=<?= $tourId ?>">Hủy</a>
  </form>
</div>
<?php require ROOT."/views/footer.php"; ?>
