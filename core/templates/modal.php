<!-- Modal para crear página -->
    <div class="modal fade" id="createPageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Página</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="pageTitle" class="form-label">Título de la Página</label>
                            <input type="text" class="form-control" id="pageTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="pageTemplate" class="form-label">Plantilla (opcional)</label>
                            <select class="form-select" id="pageTemplate" name="template_id">
                                <option value="">Crear desde cero</option>
<?php
$templates = $pageManager->getTemplates();
foreach ($templates as $template):
?>
                                        <option value="<?php echo $template['id']; ?>">
    <?php echo htmlspecialchars($template['name']); ?>
                                        </option>
<?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="create_page" class="btn btn-primary">Crear Página</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
