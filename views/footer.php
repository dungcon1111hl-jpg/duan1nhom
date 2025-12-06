<footer class="py-4 bg-light mt-auto d-none d-md-block">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; Tour Manager <?= date('Y') ?></div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    </div> </div> <div class="mobile-bottom-nav">
    <a href="index.php?act=admin" class="nav-item-mobile <?= (!isset($_GET['act']) || $_GET['act']=='admin') ? 'active' : '' ?>">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    
    <a href="index.php?act=tours" class="nav-item-mobile <?= ($_GET['act']??'')=='tours' ? 'active' : '' ?>">
        <i class="fas fa-suitcase-rolling"></i>
        <span>Tour</span>
    </a>

    <a href="index.php?act=booking-create" class="nav-item-mobile nav-item-fab">
        <i class="fas fa-plus"></i>
    </a>

    <a href="index.php?act=lich-khoi-hanh" class="nav-item-mobile <?= ($_GET['act']??'')=='lich-khoi-hanh' ? 'active' : '' ?>">
        <i class="fas fa-calendar-alt"></i>
        <span>Lịch</span>
    </a>

    <a href="index.php?act=bookings" class="nav-item-mobile <?= ($_GET['act']??'')=='bookings' ? 'active' : '' ?>">
        <i class="fas fa-ticket-alt"></i>
        <span>Đơn</span>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?= BASE_URL ?>views/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="<?= BASE_URL ?>views/js/datatables-simple-demo.js"></script>

<script>
    const sidebar = document.getElementById('layoutSidenav_nav');
    const content = document.getElementById('layoutSidenav_content');
    
    content.addEventListener('click', function() {
        if (document.body.classList.contains('sb-sidenav-toggled')) {
            document.body.classList.remove('sb-sidenav-toggled');
        }
    });
</script>

</body>
</html>