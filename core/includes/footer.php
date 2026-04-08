<?php
// includes/footer.php
?>
</main>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 para alertas bonitas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Scripts personalizados -->
    <script src="<?php echo url('js/scripts.js'); ?>"></script>
    
    <!-- Scripts específicos de la página -->
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo url('js/' . $script); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inicializar tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-hide alerts después de 5 segundos
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    <script>
        // Inicializar DataTable
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                },
                "order": [[0, "desc"]],
                "pageLength": 10
            });
        });

        // Filtrar tabla por estado
        function filterTable(status) {
            $('#usersTable tbody tr').each(function() {
                if(status === '' || $(this).data('status') === status) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Confirmar eliminación
        function confirmDelete(id) {
            var deleteLink = document.getElementById('deleteLink');
            deleteLink.href = 'users.php?delete_id=' + id;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
    <!-- Footer -->
    <footer class="bg-dark text-white mt-auto py-3 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>PePiuoX Web Editor</h5>
                    <p class="text-muted">Editor web profesional con editor drag-and-drop y soporte PHP.</p>
                </div>
                <div class="col-md-4">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>dashboard.php" class="text-white text-decoration-none">Dashboard</a></li>
                        <li><a href="<?php echo BASE_URL; ?>templates.php" class="text-white text-decoration-none">Plantillas</a></li>
                        <li><a href="<?php echo BASE_URL; ?>help.php" class="text-white text-decoration-none">Ayuda</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contacto</h5>
                    <p class="text-muted">
                        <i class="bi bi-envelope"></i> contact@pepiuox.net<br>
                        <i class="bi bi-telephone"></i> +51 999 063 645
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        &copy; <?php echo date('Y'); ?> PePiuoX Web Editor. Todos los derechos reservados.
                    </p>
                    
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p>
                        PHP <?php echo PHP_VERSION; ?> | 
                        <i class="fas fa-heart text-danger"></i> 
                        Desarrollado con GrapesJS y Bootstrap 5
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>