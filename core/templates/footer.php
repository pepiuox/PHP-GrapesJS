<?php
// templates/footer.php
?>
    </main>
    
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
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
     <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($custom_js)): ?>
        <script>
            <?php echo $custom_js; ?>
        </script>
    <?php endif; ?>
    
    <!-- Toast para notificaciones -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notificación</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>
    
    <script>
        // Función para mostrar notificaciones toast
        function showToast(message, type = 'info') {
            const toastEl = document.getElementById('liveToast');
            const toastBody = document.getElementById('toastMessage');
            
            // Cambiar color según tipo
            const header = toastEl.querySelector('.toast-header');
            header.className = 'toast-header bg-' + type + ' text-white';
            
            toastBody.textContent = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
        
        // Mostrar toast automático si hay mensajes en sesión
        <?php if (isset($_SESSION['toast_message'])): ?>
            showToast('<?php echo $_SESSION['toast_message']['text']; ?>', '<?php echo $_SESSION['toast_message']['type']; ?>');
            <?php unset($_SESSION['toast_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>