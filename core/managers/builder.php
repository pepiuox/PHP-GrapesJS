<?php
$login = new UsersClass();
$check = new CheckValidUser();
$level = new AccessLevel();

if ($login->isLoggedIn() === true && $level->levels() === 9) {

    $build = '';
    if (defined('PAG')) {
        $build = PAG;
    }
    $id = '';
    if (defined('IDP')) {
        $id = IDP;
    }

    $pcontent = '';
    $pstyle = '';

    if (isset($id) && !empty($id)) {

        $targetDir = URL . "/core/managers/uploads/";

        if ($build === 'pages') {
            $linkp = 'list_pages';
            $linkn = 'add_page';
        } elseif ($build === 'blog_posts') {
            $linkp = 'list_posts';
            $linkn = 'add_post';
        }

        function Get_ImagesToFolder($targetDir) {
            $ImagesArray = [];
            $file_display = [
                'jpg',
                'jpeg',
                'png',
                'gif'
            ];

            if (file_exists($targetDir) == false) {
                return [
                    "Directory \'', $targetDir, '\' not found!"
                ];
            } else {
                $dir_contents = scandir($targetDir);
                foreach ($dir_contents as $file) {
                    $file_type = pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array($file_type, $file_display) === true) {
                        $ImagesArray[] = "'" . $targetDir . $file . "'";
                    }
                }
                return $ImagesArray;
            }
        }

        $ImagesA = Get_ImagesToFolder($targetDir);

        $storeImage = "[" . implode(',', $ImagesA) . "]";

        if (isset($build) && !empty($build)) {

            $erow = $conn->prepare("SELECT id, title, link, content, style, parent FROM $build WHERE id=?");
            $erow->bind_param('i', $id);
            $erow->execute();
            $result = $erow->get_result();
            $row = $result->fetch_assoc();
            
            $pcontent = $row['content'];
            $pstyle = $row['style'];
            if($row['parent']===0){
                $plink = $row['link'];
            }else{
            $prow = $conn->prepare("SELECT id, link FROM $build WHERE id=?");
            $prow->bind_param('i', $row['parent']);
            $prow->execute();
            $presult = $prow->get_result();
            $lrow = $presult->fetch_assoc();
            $plink = $lrow['link'].'/'.$row['link'];
            
            }
        } else {
            header('Location: ' . SITE_PATH . 'admin/dashboard/');
            die();
        }
?>
        <div class="app-wrap">
        <!-- Side-Nav -->
        <div class="panel-wrp">
        <div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar">
        <nav class="component">
        <ul class="nav flex-column text-white w-100">
        <?php

        function getListcomponent($directory) {
            $results_array = array();
            $list = URL . '/core/managers/' . $directory;
            if (is_dir($list)) {
                if ($handle = opendir($list)) {

                    while (($file = readdir($handle)) !== FALSE) {
                        $results_array[] = $file;
                    }
                    closedir($handle);
                }
            }

            foreach ($results_array as $value) {
                $ext = pathinfo($value, PATHINFO_EXTENSION);
                if ($ext != 'php') {
                    continue;
                }
                $file = basename($value, "." . $ext);
                echo '<li class="nav-item">
<a tabindex="-1" href="#">' . ucfirst($file) . '</a>
<ul>
';
                include $directory . $value;
                echo '
</ul>
</li>';
            }
        }

        getListcomponent('components/');
        getListcomponent('sections/');
        ?>
        </ul>
        </nav>
        </div>
        </div>
        <div class="editor-wrap active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-dark bg-dark px-5">
        <a class="btn border-0" id="menu-btn"><i class="fa fa-bars"></i></a>
        <div class="container-fluid">
        <div class="menu-logo">
        <div class="navbar-brand">
        <a href="index.php" class="navbar-brand">
        <?php
        $logo = SITE_BRAND_IMG;
        if (file_exists($logo)) {
        ?>
            <img src="<?php echo $logo; ?>" alt="<?php echo SITE_NAME; ?>" height="36" style="opacity: .8">
            <span class="brand-text font-weight-light"><?php echo SITE_NAME; ?></span>
            <?php
        } else {
            echo SITE_NAME;
        }
            ?>
        </a>
        </div>
        </div>
        <div class="dropdown dropstart">
        <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1"
        data-bs-toggle="dropdown" aria-expanded="false">
        <?php
        if (!empty(USERS_AVATARS)) {
            echo '<img src=' . SITE_PATH . 'uploads/' . USERS_AVATARS . '" class="user-image img-circle elevation-2" alt="' . USERS_NAMES . '">';
        }
        ?>
        <i class="fa fa-user"></i>
        <span class="d-none d-md-inline"><?php echo USERS_FULLNAMES; ?></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <!-- User image -->
        <li class="dropdown-item user-header">
        <?php
        if (!empty(USERS_AVATARS)) {
            echo '<img src=' . ITE_PATH . 'uploads/' . USERS_AVATARS . '" class="img-circle elevation-2" alt="' . USERS_NAMES . '">';
        }
        ?>
        <p>
        <?php echo USERS_NAMES . ' - ' . USERS_SKILLS; ?>
        <small>Member since Nov. 2012</small>
        </p>
        </li>
        <!-- Menu Body -->
        <li class="dropdown-item user-body">
        <div class="row">
        <div class="col-4 text-center">
        <a href="#">Followers</a>
        </div>
        <div class="col-4 text-center">
        <a href="#">Sales</a>
        </div>
        <div class="col-4 text-center">
        <a href="#">Friends</a>
        </div>
        </div>
        <!-- /.row -->
        </li>
        <!-- Menu Footer-->
        <li class="dropdown-item user-footer">
        <form method="post">
        <button class="btn btn-default btn-flat" type="submit" name="profile">Profile</button>
        <button class="btn btn-default btn-flat float-right" type="submit" name="logout">
        Sign out
        </button>
        </form>

        </li>
        </ul>
        </div>

        </div>
        </nav>
        <script type="text/javascript">
        $("#gjs div").removeAttr("id");
        </script>
        <div id="gjs" style="height:0px; overflow:hidden" class='box' ondragenter="return dragEnter(event)"
        ondrop="return dragDrop(event)" ondragover="return dragOver(event)">
        <?php
        echo decodeContent($pcontent) . "\n";
        echo '<style>' . "\n";
        echo decodeContent($pstyle) . "\n";
        echo '</style>' . "\n";
        ?>
        </div>
        </div>
        </div>
        <?php

        function Savedata($tbl, $content, $style, $idp) {
            $sql = "UPDATE $tbl SET  content = ?, style = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", protect($content), protect($style), $idp);
            $stmt->execute();
            $save = $stmt->affected_rows;
            $stmt->close();

            if ($save === 1) {
                echo "Save Data";
            } else {
                echo "Failed";
            }
        }

        ?>
        <script type="text/javascript">
        var images = <?php echo $storeImage; ?>;
        var editor = grapesjs.init({
        height: '100%',
        container: '#gjs',
        fromElement: true,
        showOffsets: true,
        storageType: '',
        storeOnChange: true,
        storeAfterUpload: true,
        storageManager: false,
        assetManager: {
        upload: 'uploads', //for temporary storage
        uploadName: 'files',
        multiUpload: true,
        assets: images,
        uploadFile: function(e) {
        var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
        var formData = new FormData();
        for (var i in files) {
        formData.append('file-' + i, files[i]); //containing all the selected images from local
        }
        $.ajax({
        url: 'upImage.php',
        type: 'POST',
        data: formData,
        contentType: false,
        crossDomain: true,
        dataType: 'json',
        mimeType: "multipart/form-data",
        processData: false,
        success: function(result) {
        var myJSON = [];
        $.each(result['data'], function(key, value) {
        myJSON[key] = value;
        });
        var images = myJSON;
        editor.AssetManager.add(
        images); //adding images to asset manager of GrapesJS
        }
        });
        }
        },
        selectorManager: {
        componentFirst: true
        },
        styleManager: {
        sectors: [{
        name: 'General',
        properties: [{
        extend: 'float',
        type: 'radio',
        default: 'none',
        options: [{
        value: 'none',
        className: 'fa fa-times'
        },
        {
        value: 'left',
        className: 'fa fa-align-left'
        },
        {
        value: 'right',
        className: 'fa fa-align-right'
        }
        ]
        },
        'display',
        {
        extend: 'position',
        type: 'select'
        },
        'top',
        'right',
        'left',
        'bottom'
        ]
        }, {
        name: 'Dimension',
        open: false,
        properties: [
        'width',
        {
        id: 'flex-width',
        type: 'integer',
        name: 'Width',
        units: ['px', '%'],
        property: 'flex-basis',
        toRequire: 1
        },
        'height',
        'max-width',
        'min-height',
        'margin',
        'padding'
        ]
        }, {
        name: 'Typography',
        open: false,
        properties: [
        'font-family',
        'font-size',
        'font-weight',
        'letter-spacing',
        'color',
        'line-height',
        {
        extend: 'text-align',
        options: [{
        id: 'left',
        label: 'Left',
        className: 'fa fa-align-left'
        },
        {
        id: 'center',
        label: 'Center',
        className: 'fa fa-align-center'
        },
        {
        id: 'right',
        label: 'Right',
        className: 'fa fa-align-right'
        },
        {
        id: 'justify',
        label: 'Justify',
        className: 'fa fa-align-justify'
        }
        ],
        },
        {
        property: 'text-decoration',
        type: 'radio',
        default: 'none',
        options: [{
        id: 'none',
        label: 'None',
        className: 'fa fa-times'
        },
        {
        id: 'underline',
        label: 'underline',
        className: 'fa fa-underline'
        },
        {
        id: 'line-through',
        label: 'Line-through',
        className: 'fa fa-strikethrough'
        }
        ]
        },
        'text-shadow'
        ]
        }, {
        name: 'Decorations',
        open: false,
        properties: [
        'opacity',
        'border-radius',
        'border',
        'box-shadow',
        'background' // { id: 'background-bg', property: 'background', type: 'bg' }
        ]
        }, {
        name: 'Extra',
        open: false,
        buildProps: [
        'transition',
        'perspective',
        'transform'
        ]
        }, {
        name: 'Flex',
        open: false,
        properties: [{
        name: 'Flex Container',
        property: 'display',
        type: 'select',
        defaults: 'block',
        list: [{
        value: 'block',
        name: 'Disable'
        },
        {
        value: 'flex',
        name: 'Enable'
        }
        ]
        }, {
        name: 'Flex Parent',
        property: 'label-parent-flex',
        type: 'integer'
        }, {
        name: 'Direction',
        property: 'flex-direction',
        type: 'radio',
        defaults: 'row',
        list: [{
        value: 'row',
        name: 'Row',
        className: 'icons-flex icon-dir-row',
        title: 'Row'
        }, {
        value: 'row-reverse',
        name: 'Row reverse',
        className: 'icons-flex icon-dir-row-rev',
        title: 'Row reverse'
        }, {
        value: 'column',
        name: 'Column',
        title: 'Column',
        className: 'icons-flex icon-dir-col'
        }, {
        value: 'column-reverse',
        name: 'Column reverse',
        title: 'Column reverse',
        className: 'icons-flex icon-dir-col-rev'
        }],
        }, {
        name: 'Justify',
        property: 'justify-content',
        type: 'radio',
        defaults: 'flex-start',
        list: [{
        value: 'flex-start',
        className: 'icons-flex icon-just-start',
        title: 'Start'
        }, {
        value: 'flex-end',
        title: 'End',
        className: 'icons-flex icon-just-end'
        }, {
        value: 'space-between',
        title: 'Space between',
        className: 'icons-flex icon-just-sp-bet'
        }, {
        value: 'space-around',
        title: 'Space around',
        className: 'icons-flex icon-just-sp-ar'
        }, {
        value: 'center',
        title: 'Center',
        className: 'icons-flex icon-just-sp-cent'
        }],
        }, {
        name: 'Align',
        property: 'align-items',
        type: 'radio',
        defaults: 'center',
        list: [{
        value: 'flex-start',
        title: 'Start',
        className: 'icons-flex icon-al-start'
        }, {
        value: 'flex-end',
        title: 'End',
        className: 'icons-flex icon-al-end'
        }, {
        value: 'stretch',
        title: 'Stretch',
        className: 'icons-flex icon-al-str'
        }, {
        value: 'center',
        title: 'Center',
        className: 'icons-flex icon-al-center'
        }],
        }, {
        name: 'Flex Children',
        property: 'label-parent-flex',
        type: 'integer',
        }, {
        name: 'Order',
        property: 'order',
        type: 'integer',
        defaults: 0,
        min: 0
        }, {
        name: 'Flex',
        property: 'flex',
        type: 'composite',
        properties: [{
        name: 'Grow',
        property: 'flex-grow',
        type: 'integer',
        defaults: 0,
        min: 0
        }, {
        name: 'Shrink',
        property: 'flex-shrink',
        type: 'integer',
        defaults: 0,
        min: 0
        }, {
        name: 'Basis',
        property: 'flex-basis',
        type: 'integer',
        units: ['px', '%', ''],
        unit: '',
        defaults: 'auto'
        }]
        }, {
        name: 'Align',
        property: 'align-self',
        type: 'radio',
        defaults: 'auto',
        list: [{
        value: 'auto',
        name: 'Auto'
        }, {
        value: 'flex-start',
        title: 'Start',
        className: 'icons-flex icon-al-start'
        }, {
        value: 'flex-end',
        title: 'End',
        className: 'icons-flex icon-al-end'
        }, {
        value: 'stretch',
        title: 'Stretch',
        className: 'icons-flex icon-al-str'
        }, {
        value: 'center',
        title: 'Center',
        className: 'icons-flex icon-al-center'
        }]
        }]
        }]
        },
        plugins: [
        'gjs-blocks-basic',
        'grapesjs-plugin-forms',
        'grapesjs-component-countdown',
        'grapesjs-plugin-export',
        'grapesjs-tabs',
        'grapesjs-custom-code',
        'grapesjs-touch',
        'grapesjs-parser-postcss',
        'grapesjs-tooltip',
        'grapesjs-tui-image-editor',
        'grapesjs-typed',
        'grapesjs-style-bg',
        'grapesjs-preset-webpage',
        'grapesjs-bootstrap-elements',
        'grapesjs-project-manager',
        'grapesjs-component-code-editor'
        ],
        pluginsOpts: {
        'gjs-blocks-basic': {
        flexGrid: true
        },
        'grapesjs-tui-image-editor': {
        script: [
        // 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.min.js',
        '<?php echo SITE_PATH; ?>assets/plugins/grapesjs/js/tui-code-snippet.min.js',
        '<?php echo SITE_PATH; ?>assets/plugins/grapesjs/js/tui-color-picker.min.js',
        '<?php echo SITE_PATH; ?>assets/plugins/grapesjs/js/tui-image-editor.min.js'
        ],
        style: [
        '<?php echo SITE_PATH; ?>assets/plugins/grapesjs/css/tui-color-picker.min.css',
        '<?php echo SITE_PATH; ?>assets/plugins/grapesjs/css/tui-image-editor.min.css'
        ]
        },
        'grapesjs-tabs': {
        tabsBlock: {
        category: 'Extra'
        }
        },
        'grapesjs-typed': {
        block: {
        category: 'Extra',
        content: {
        type: 'typed',
        'type-speed': 40,
        strings: [
        'Text row one',
        'Text row two',
        'Text row three'
        ]
        }
        }
        },
        'grapesjs-preset-webpage': {
        modalImportTitle: 'Import Template',
        modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
        modalImportContent: function(editor) {
        return editor.getHtml() + '<style>' + editor.getCss() + '</style>';
        }
        },
        'grapesjs-project-manager': {
        objectStoreName: 'templates',
        mdlTitle: 'Project Manager'
        },
        'grapesjs-plugin-export': {
        addExportBtn: true
        },
        'grapesjs-custom-code': {},
        'grapesjs-component-code-editor': {},
        'grapesjs-plugin-forms': {
        blocks: ['form', 'input', 'textarea', 'select', 'button', 'label', 'checkbox', 'radio'],
        category: 'Forms'
        },
        'grapesjs-bootstrap-elements': {
        blocks: {},
        blockCategories: {},
        labels: {},
        gridDevicesPanel: true,
        formPredefinedActions: [{
        name: 'Contact',
        value: '/contact'
        },
        {
        name: 'landing',
        value: '/landing'
        }
        ]
        }
        },
        canvas: {
        styles: [
        '<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css'
        ],
        scripts: [
        '<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js',
        '<?php echo SITE_PATH; ?>assets/plugin/popper/popper.min.js',
        '<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.min.js'
        ]
        }
        });

        editor.I18n.addMessages({
        en: {
        styleManager: {
        properties: {
        'background-repeat': 'Repeat',
        'background-position': 'Position',
        'background-attachment': 'Attachment',
        'background-size': 'Size'
        }
        }
        }
        });

        var pn = editor.Panels;
        var modal = editor.Modal;
        var cmdm = editor.Commands;
        var blockManager = editor.BlockManager;

        // Update canvas-clear command
        cmdm.add('canvas-clear', function() {
        if (confirm('Are you sure to clean the canvas?')) {
        editor.runCommand('core:canvas-clear');
        setTimeout(function() {
        localStorage.clear();
        }, 0);
        }
        });

        cmdm.add('set-device-desktop', {
        run: function(ed) {
        ed.setDevice('Desktop');
        },
        stop: function() {}
        });

        cmdm.add('set-device-tabvar ', {
        run: function(ed) {
        ed.setDevice('Tabvar ');
        },
        stop: function() {}
        });

        cmdm.add('set-device-mobile', {
        run: function(ed) {
        ed.setDevice('Mobile portrait');
        },
        stop: function() {}
        });

        cmdm.add('dashboard', {
        run: function(em, sender) {
        sender.set('active', true);
        dashboardPage();
        }
        });
        cmdm.add('save-changes', {
        run: function(em, sender) {
        sender.set('active', true);
        saveContent();
        }
        });

        cmdm.add('view-list', {
        run: function(em, sender) {
        sender.set('active', true);
        pageList();
        }
        });

        cmdm.add('refresh-content', {
        run: function(em, sender) {
        sender.set('active', true);
        refreshContent();
        }
        });

        cmdm.add('new-content', {
        run: function(em, sender) {
        sender.set('active', true);
        newContent();
        }
        });
        cmdm.add('view-content', {
        run: function(em, sender) {
        sender.set('active', true); //get full HTML structure after design
        viewContent();
        }
        });

        // Add info command
        var mdlClass = 'gjs-mdl-dialog-sm';
        var infoContainer = document.getElementById('info-panel');

        cmdm.add('open-info', function() {
        var mdlDialog = document.querySelector('.gjs-mdl-dialog');
        mdlDialog.className += ' ' + mdlClass;
        infoContainer.style.display = 'block';
        modal.setTitle('About this demo');
        modal.setContent(infoContainer);
        modal.open();
        modal.getModel().once('change:open', function() {
        mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
        });
        });

        // Add info command

        var panelViews = pn.addPanel({
        id: "views"
        });
        panelViews.get("buttons").add([{
        attributes: {
        title: "Open Code"
        },
        className: "fa fa-file-code-o",
        command: "open-code",
        togglable: false, //do not close when button is clicked again
        id: "open-code"
        }]);

        pn.addButton('options', {
        id: 'open-info',
        className: 'fa fa-question-circle',
        command: function() {
        editor.runCommand('open-info');
        },
        attributes: {
        'title': 'About',
        'data-tooltip-pos': 'bottom'
        }
        });

        pn.addButton('options', {
        id: 'open-templates',
        className: 'fa fa-folder-o',
        attributes: {
        title: 'Open projects and templates'
        },
        command: 'open-templates' //Open modal 
        });

        pn.addButton('views', {
        id: 'open-pages',
        className: 'fa fa-file-o',
        attributes: {
        title: 'Take Screenshot'
        },
        command: 'open-pages',
        togglable: false
        });

        //More Buttom
        pn.addButton('options', {
        id: 'open-templates',
        className: 'fa fa-folder-o',
        attributes: {
        title: 'Open projects and templates'
        },
        command: 'open-templates' //Open modal 
        });

        pn.addButton('options', [{
        id: 'dashboard',
        className: 'fa fa-tachometer',
        command: 'dashboard',
        attributes: {
        title: 'Dashboard',
        'data-tooltip-pos': 'bottom'
        }
        }]);

        pn.addButton('options', [{
        id: 'save-changes',
        className: 'fa fa-floppy-o',
        command: 'save-changes',
        attributes: {
        title: 'Save changes',
        'data-tooltip-pos': 'bottom'
        }
        }]);

        pn.addButton('options', [{
        id: 'view-list',
        className: 'fa fa-list',
        command: 'view-list',
        attributes: {
        title: 'Content list',
        'data-tooltip-pos': 'bottom'
        }
        }]);

        pn.addButton('options', [{
        id: 'view-content',
        className: 'fa fa-file-text-o',
        command: 'view-content',
        attributes: {
        title: 'View content',
        'data-tooltip-pos': 'bottom'
        }
        }]);

        pn.addButton('options', [{
        id: 'refresh-content',
        className: 'fa fa-refresh',
        command: 'refresh-content',
        attributes: {
        title: 'Refresh content',
        'data-tooltip-pos': 'bottom'
        }
        }]);

        pn.addButton('options', [{
        id: 'new-content',
        className: 'fa fa-file-o',
        command: 'new-content',
        attributes: {
        title: 'New content',
        'data-tooltip-pos': 'bottom'
        }
        }]);

        // Simple warn notifier
        var origWarn = console.warn;
        toastr.options = {
        closeButton: true,
        preventDuplicates: true,
        showDuration: 250,
        hideDuration: 150
        };

        console.warn = function(msg) {
        if (msg.indexOf('[undefined]') == -1) {
        toastr.warning(msg);
        }
        origWarn(msg);
        };

        // Add and beautify tooltips
        [
        ['sw-visibility', 'Show Borders'],
        ['preview', 'Preview'],
        ['fullscreen', 'Fullscreen'],
        ['export-template', 'Export'],
        ['undo', 'Undo'],
        ['redo', 'Redo'],
        ['gjs-open-import-webpage', 'Import'],
        ['canvas-clear', 'Clear canvas']
        ]
        .forEach(function(item) {
        pn.getButton('options', item[0]).set('attributes', {
        title: item[1],
        'data-tooltip-pos': 'bottom'
        });
        });
        [
        ['open-sm', 'Style Manager'],
        ['open-layers', 'Layers'],
        ['open-blocks', 'Blocks']
        ]
        .forEach(function(item) {
        pn.getButton('views', item[0]).set('attributes', {
        title: item[1],
        'data-tooltip-pos': 'bottom'
        });
        });
        var titles = document.querySelectorAll('*[title]');

        for (var i = 0; i < titles.length; i++) {
        var el = titles[i];
        var title = el.getAttribute('title');
        title = title ? title.trim() : '';
        if (!title)
        break;
        el.setAttribute('data-tooltip', title);
        el.setAttribute('title', '');
        }

        // editor.runCommand('gjs-export-zip');
        // Store and load events
        editor.on('storage:load', function(e) {
        console.log('Loaded ', e);
        });

        editor.on('storage:store', function(e) {
        console.log('Stored ', e);
        });
        editor.getHtml({
        cleanId: false
        });

        editor.getInnerHTML({
        attributes(component, attributes) {
        if (component.get('tagName')) {
        attributes.id = false;
        }
        return attributes;
        }
        });
        editor.toHTML({
        attributes(component, attributes) {
        if (component.get('tagName')) {
        attributes.id = false;
        }
        return attributes;
        }
        });

        //editor.getHtml();
        // Do stuff on load
        editor.on('load', function() {
        var $ = grapesjs.$;

        // Show borders by default
        pn.getButton('options', 'sw-visibility').set('active', 1);

        // Show logo with the version
        var logoCont = document.querySelector('.gjs-logo-cont');
        document.querySelector('.gjs-logo-version').innerHTML = 'v' + grapesjs.version;
        var logoPanel = document.querySelector('.gjs-pn-commands');
        logoPanel.appendChild(logoCont);


        // Load and show settings and style manager
        var openTmBtn = pn.getButton('views', 'open-tm');
        openTmBtn && openTmBtn.set('active', 1);
        var openSm = pn.getButton('views', 'open-sm');
        openSm && openSm.set('active', 1);

        // Remove trait view
        pn.removeButton('views', 'open-tm');

        // Add Settings Sector
        var traitsSector = $('<div class="gjs-sm-sector no-select">' +
        '<div class="gjs-sm-sector-title"><span class="icon-settings fa fa-cog"></span> <span class="gjs-sm-sector-label">Settings</span></div>' +
        '<div class="gjs-sm-properties" style="display: none;"></div></div>');
        var traitsProps = traitsSector.find('.gjs-sm-properties');
        traitsProps.append($('.gjs-trt-traits'));
        $('.gjs-sm-sectors').before(traitsSector);
        traitsSector.find('.gjs-sm-sector-title').on('click', function() {
        var traitStyle = traitsProps.get(0).style;
        var hidden = traitStyle.display == 'none';
        if (hidden) {
        traitStyle.display = 'block';
        } else {
        traitStyle.display = 'none';
        }
        });

        // Open block manager
        var openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
        openBlocksBtn && openBlocksBtn.set('active', 1);

        // Move Ad

        });
        // function buttom
        function viewContent() {
        var id = '<?php echo $id; ?>';
        var tbl = '<?php echo $build; ?>';
        var url = "<?php echo SITE_PATH. $plink; ?>";
        window.open(url);
        }

        function saveContent() {
        var idp = '<?php echo $id; ?>';
        var tbl = '<?php echo $build; ?>';
        var content = editor.getHtml(); //get html content of document
        var style = editor.getCss(); //get css content of document
        // Get edit field value
        $.ajax({
        method: "POST",
        url: "<?php echo SITE_PATH; ?>core/managers/save.php",
        data: { idp: idp,
        tbl: tbl,
        content: content,
        style: style }
        }).done(function(data) {
        alert("Saved: "+data);
        }).fail(function() {
        alert("Error");
        });
        }

        function pageList() {
        var url = '<?php echo SITE_PATH; ?>admin/dashboard/<?php echo $linkp; ?>';
        location.replace(url);
        }

        function dashboardPage() {
        var url = '<?php echo SITE_PATH; ?>admin/dashboard/';
        location.replace(url);
        }

        function refreshContent() {
        location.reload();
        }

        function newContent() {

        var url = '<?php echo SITE_PATH; ?>admin/dashboard/<?php echo $linkn; ?>';
        location.replace(url);
        }

        function clearContent() {
        var clear = 'clear';
        $.ajax({
        url: '<?php echo SITE_PATH; ?>core/managers/clearcontent.php',
        type: 'post',
        data: {
        clear: clear
        }
        }).done(function(rsp) {
        $('#result').html(rsp);
        });
        }

        function getContent() {}

        function uploadImages() {
        var files = $('#gjs-am-uploadFile')[0].files[0];
        formData.append('file', files);
        aler(files);
        /*
        var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
        var formData = new FormData();
        for(var i in files){
        formData.append('file-'+i, files[i]) //containing all the selected images from local
        }*/
        $.ajax({
        url: '<?php echo SITE_PATH; ?>core/managers/upload_image.php',
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false
        }).done(function(result) {
        var myJSON = [];
        $.each(result['data'], function(key, value) {
        myJSON[key] = value;
        });
        var images = myJSON;
        editor.AssetManager.add(images); //adding images to asset manager of GrapesJS
        });
        }
        </script>
        <script>
        var btn = document.querySelector('.add');
        var remove = document.querySelector('.pi-draggable');

        function dragStart(e) {
        this.style.opacity = '0.4';
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
        };

        function dragEnter(e) {
        this.classList.add('over');
        }

        function dragLeave(e) {
        e.stopPropagation();
        this.classList.remove('over');
        }

        function dragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        return false;
        }

        function dragDrop(e) {
        if (dragSrcEl != this) {
        dragSrcEl.innerHTML = this.innerHTML;
        this.innerHTML = e.dataTransfer.getData('text/html');
        }
        return false;
        }

        function dragEnd(e) {
        var listItens = document.querySelectorAll('.pi-draggable');
        [].forEach.call(listItens, function(item) {
        item.classList.remove('over');
        });
        this.style.opacity = '1';
        }

        function addEventsDragAndDrop(el) {
        el.addEventListener('dragstart', dragStart, false);
        el.addEventListener('dragenter', dragEnter, false);
        el.addEventListener('dragover', dragOver, false);
        el.addEventListener('dragleave', dragLeave, false);
        el.addEventListener('drop', dragDrop, false);
        el.addEventListener('dragend', dragEnd, false);
        }

        var listItens = document.querySelectorAll('.pi-draggable');
        [].forEach.call(listItens, function(item) {
        addEventsDragAndDrop(item);
        });
        </script>
        <script>
        var menu_btn = document.querySelector("#menu-btn");
        var sidebar = document.querySelector("#sidebar");
        var container = document.querySelector(".editor-wrap");
        menu_btn.addEventListener("click", () => {
        sidebar.classList.toggle("active-nav");
        container.classList.toggle("active-cont");
        });
        </script>
        <script>
        var $el = $(".pi-draggable");
        var elHeight = $el.outerHeight();
        var elWidth = $el.outerWidth();

        function doResize(event, ui) {

        var scale, origin;

        scale = Math.min(
        ui.size.width / elWidth,
        ui.size.height / elHeight
        );

        $el.css({
        transform: "translate(-50%, -50%) " + "scale(" + scale + ")"
        });

        }

        var starterData = {
        size: {
        width: $el.width(),
        height: $el.height()
        }
        };

        doResize(null, starterData);
        </script>

        <?php
    } else {
        echo '<script>
window.location.replace("' . SITE_PATH . 'signin/login");
</script>';
    }
}
        ?>
