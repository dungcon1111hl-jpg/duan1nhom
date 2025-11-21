<footer class="bg-white shadow-sm mt-5 py-3">
    <div class="container-fluid d-flex justify-content-between small">
        <div class="text-muted">
            © <?= date('Y') ?> Your Website · All rights reserved
        </div>
        <div>
            <a href="#" class="text-decoration-none me-2">Privacy Policy</a>
            •
            <a href="#" class="text-decoration-none ms-2">Terms</a>
        </div>
    </div>
</footer>

        </div> <!-- end content -->
    </div> <!-- end wrapper -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle -->
    <script>
        document.getElementById("sidebarToggle").onclick = function () {
            document.getElementById("sidebar").classList.toggle("d-none");
        };
    </script>

</body>
</html>
