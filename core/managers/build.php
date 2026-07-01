<!-- CodeMirror CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">

<!-- CodeMirror JS y Modos de lenguaje -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/php/php.min.js"></script>
<script>
// 1. Inicializar instancias de CodeMirror
var cmHtml = CodeMirror(document.getElementById('cm-html'), {
    mode: 'xml', lineNumbers: true, autoCloseTags: true, matchBrackets: true, theme: 'dracula', tabSize: 2
});
var cmCss = CodeMirror(document.getElementById('cm-css'), {
    mode: 'css', lineNumbers: true, theme: 'dracula', tabSize: 2
});
var cmJs = CodeMirror(document.getElementById('cm-js'), {
    mode: 'javascript', lineNumbers: true, theme: 'dracula', tabSize: 2
});
var cmPhp = CodeMirror(document.getElementById('cm-php'), {
    mode: 'php', lineNumbers: true, theme: 'dracula', tabSize: 2
});

// 2. Comando para abrir el modal y cargar contenido
cmdm.add('open-codemirror', {
    run: function(em) {
        // Cargar HTML y CSS desde GrapesJS
        cmHtml.setValue(em.getHtml());
        cmCss.setValue(em.getCss());

        // Extraer JS del canvas (GrapesJS no tiene getJs() nativo)
        var canvasDoc = em.Canvas.getDocument();
        var scripts = canvasDoc.querySelectorAll('script');
        var jsCode = '';
scripts.forEach(s => jsCode += s.outerHTML + '\n');
cmJs.setValue(jsCode);

// PHP viene de la BD (asegúrate de pasarlo vía PHP al cargar la página)
cmPhp.setValue(<?php echo json_encode($row['php_content'] ?? ''); ?>);

// Forzar refresh de CodeMirror (evita problemas de render en modales)
setTimeout(() => {
    cmHtml.refresh(); cmCss.refresh(); cmJs.refresh(); cmPhp.refresh();
}, 150);

new bootstrap.Modal(document.getElementById('codeMirrorModal')).show();
    }
});

// 3. Botón "Aplicar al Canvas"
document.getElementById('applyCodeBtn').addEventListener('click', function() {
    editor.setComponents(cmHtml.getValue());
    editor.setStyle(cmCss.getValue());

    // Inyectar JS de forma segura en el canvas
    var canvasWin = editor.Canvas.getWindow();
    canvasWin.document.querySelectorAll('script').forEach(s => s.remove());
    var newScript = canvasWin.document.createElement('script');
    newScript.textContent = cmJs.getValue().replace(/<script[^>]*>|<\/script>/gi, '');
    canvasWin.document.body.appendChild(newScript);

    editor.refresh();
    editor.runCommand('core:canvas-clear'); // Limpia caché interna
    editor.setComponents(cmHtml.getValue());
    editor.setStyle(cmCss.getValue());

    bootstrap.Modal.getInstance(document.getElementById('codeMirrorModal')).hide();
    toastr.success('Código aplicado correctamente');
});

// 4. Agregar botón a la barra de herramientas de GrapesJS
pn.addButton('options', {
    id: 'open-code-mirror',
    className: 'fa-solid fa-code',
    command: 'open-codemirror',
    attributes: { title: 'Editar Código (CodeMirror)', 'data-tooltip-pos': 'bottom' }
});

function saveContent() {
    var idp = '<?php echo $id; ?>';
    var tbl = '<?php echo $build; ?>';

    $.ajax({
        method: "POST",
        url: "<?php echo SITE_PATH; ?>core/managers/save.php",
        data: {
            idp: idp,
            tbl: tbl,
            content: editor.getHtml(),
           style: editor.getCss(),
           js_content: cmJs.getValue(), // <-- Nuevo campo
           php_content: cmPhp.getValue() // <-- Nuevo campo
        }
    }).done(function(data) {
        toastr.success('Guardado: ' + data);
    }).fail(function() {
        toastr.error('Error al guardar');
    });
}

</script>
<?php
$pcontent = $row['html_content'] ?? '';
$pstyle = $row['css_content'] ?? '';
$pjs = addslashes($row['js_content'] ?? ''); // addslashes evita que rompa el JS
$pphp = addslashes($row['php_content'] ?? '');
// Busca esta parte en tu código:
$pcontent = $row['html_content'];
$pstyle = $row['css_content'];

// Agrega estas dos líneas justo debajo:
$pjs = isset($row['js_content']) ? addslashes($row['js_content']) : '';
$pphp = isset($row['php_content']) ? addslashes($row['php_content']) : '';
?>
<!-- Modal Editor de Código -->
<div class="modal fade" id="codeEditorModal" tabindex="-1" aria-labelledby="codeEditorModalLabel" aria-hidden="true">
<div class="modal-dialog modal-xl modal-dialog-scrollable">
<div class="modal-content">
<div class="modal-header bg-dark text-white">
<h5 class="modal-title" id="codeEditorModalLabel"><i class="fa fa-code"></i> Editor de Código Global</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-0">
<ul class="nav nav-tabs nav-fill bg-light" id="codeTabs" role="tablist">
<li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-html" type="button">HTML</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-css" type="button">CSS</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-js" type="button">JavaScript</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-php" type="button">PHP</button></li>
</ul>
<div class="tab-content p-3" style="height: 60vh;">
<div class="tab-pane fade show active" id="tab-html"><textarea id="code-html"></textarea></div>
<div class="tab-pane fade" id="tab-css"><textarea id="code-css"></textarea></div>
<div class="tab-pane fade" id="tab-js"><textarea id="code-js"></textarea></div>
<div class="tab-pane fade" id="tab-php"><textarea id="code-php"></textarea></div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
<button type="button" class="btn btn-primary" onclick="applyCodeToCanvas()">
<i class="fa fa-sync"></i> Aplicar al Canvas
</button>
</div>
</div>
</div>
</div>
<script>
// 1. Inicializar instancias de CodeMirror
var codeEditors = {};
document.addEventListener("DOMContentLoaded", function() {
    var cmConfig = {
        lineNumbers: true,
        theme: 'dracula',
        lineWrapping: true,
        extraKeys: {"Ctrl-Space": "autocomplete"}
    };

    codeEditors.html = CodeMirror.fromTextArea(document.getElementById("code-html"), Object.assign({}, cmConfig, { mode: "htmlmixed" }));
    codeEditors.css = CodeMirror.fromTextArea(document.getElementById("code-css"), Object.assign({}, cmConfig, { mode: "css" }));
    codeEditors.js = CodeMirror.fromTextArea(document.getElementById("code-js"), Object.assign({}, cmConfig, { mode: "javascript" }));
    codeEditors.php = CodeMirror.fromTextArea(document.getElementById("code-php"), Object.assign({}, cmConfig, { mode: "application/x-httpd-php" }));
});

// 2. Agregar botón a la barra de opciones de GrapesJS
var pn = editor.Panels;
pn.addButton('options', {
    id: 'open-code-editor',
    className: 'fa fa-file-code-o', // Asegúrate de tener FontAwesome cargado
    command: 'open-code-editor',
    attributes: { title: 'Editar Código Fuente' }
});

// 3. Comando para abrir el modal y cargar datos
editor.Commands.add('open-code-editor', {
    run: function(editor, sender) {
        // Cargar contenido actual de GrapesJS
        codeEditors.html.setValue(editor.getHtml());
        codeEditors.css.setValue(editor.getCss());

        // Cargar JS y PHP desde las variables PHP inyectadas
        codeEditors.js.setValue(`<?php echo $pjs; ?>`);
        codeEditors.php.setValue(`<?php echo $pphp; ?>`);

        // Abrir modal de Bootstrap
        var myModal = new bootstrap.Modal(document.getElementById('codeEditorModal'));
        myModal.show();

        // IMPORTANTE: Refrescar CodeMirror después de que el modal sea visible para evitar errores de renderizado
        setTimeout(function() {
            Object.values(codeEditors).forEach(ed => ed.refresh());
        }, 200);
    }
});

// 4. Función para aplicar los cambios del código al canvas de GrapesJS
function applyCodeToCanvas() {
    // Actualizar GrapesJS con el nuevo HTML y CSS
    editor.setComponents(codeEditors.html.getValue());
    editor.setStyle(codeEditors.css.getValue());

    // Cerrar modal
    var modalEl = document.getElementById('codeEditorModal');
    var modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();

    // Notificación visual (usando toastr si lo tienes, o alert)
    if(typeof toastr !== 'undefined') {
        toastr.success('Código aplicado al canvas. Recuerda guardar los cambios.');
    } else {
        alert('Código aplicado. Recuerda guardar los cambios.');
    }
}

// 5. ACTUALIZAR tu función saveContent existente para incluir JS y PHP
function saveContent() {
    var idp = '<?php echo $id; ?>';
    var tbl = '<?php echo $build; ?>';

    // Obtenemos el contenido de GrapesJS y de CodeMirror
    var content = editor.getHtml();
    var style = editor.getCss();
    var jsCode = codeEditors.js ? codeEditors.js.getValue() : '';
    var phpCode = codeEditors.php ? codeEditors.php.getValue() : '';

    $.ajax({
        method: "POST",
        url: "<?php echo SITE_PATH; ?>core/managers/save.php",
        data: {
            idp: idp,
            tbl: tbl,
            content: content,
            style: style,
            js_content: jsCode,
            php_content: phpCode
        }
    }).done(function(data) {
        if(typeof toastr !== 'undefined') {
            toastr.success("Guardado: " + data);
        } else {
            alert("Guardado: " + data);
        }
    }).fail(function() {
        if(typeof toastr !== 'undefined') {
            toastr.error("Error al guardar los cambios");
        } else {
            alert("Error al guardar");
        }
    });
}
</script>
<?php
// core/managers/save.php
require_once 'tu_archivo_de_conexion_pdo.php'; // Ajusta según tu estructura

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idp = $_POST['idp'] ?? null;
    $tbl = $_POST['tbl'] ?? null;
    $html = $_POST['content'] ?? '';
    $css = $_POST['style'] ?? '';
    $js = $_POST['js_content'] ?? '';
    $php = $_POST['php_content'] ?? '';

    if (!$idp || !$tbl) {
        echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
        exit;
    }

    // Validación básica de tabla permitida (seguridad)
    $allowed_tables = ['pages', 'blog_posts'];
    if (!in_array($tbl, $allowed_tables)) {
        echo json_encode(["status" => "error", "message" => "Tabla no permitida"]);
        exit;
    }

    try {
        $sql = "UPDATE $tbl SET
        html_content = :html,
        css_content = :css,
        js_content = :js,
        php_content = :php,
        updated_at = CURRENT_TIMESTAMP
        WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':html' => $html,
            ':css'  => $css,
            ':js'   => $js,
            ':php'  => $php,
            ':id'   => $idp
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Cambios guardados correctamente"]);
        } else {
            echo json_encode(["status" => "info", "message" => "No se realizaron cambios"]);
        }
    } catch (PDOException $e) {
        error_log("Error al guardar: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Error en la base de datos"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>
