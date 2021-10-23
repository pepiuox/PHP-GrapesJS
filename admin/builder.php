<?php
if (!isset($_SESSION)) {
    session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
    $login = new UserClass();
    $check = new CheckValidUser();
    $level = new AccessLevel();
} else {
    header('Location: ../installer/install.php');
    exit();
}
if ($login->isLoggedIn() === true && $level->levels() === 9) {

    $id = '';
    $pcontent = '';
    $pstyle = '';

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        ?>
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <title><?php echo SITE_NAME; ?> | Builder</title>
                <link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $base; ?>css/fontawesome.min.css" rel="stylesheet" type="text/css"/>
                <link rel="stylesheet" href="<?php echo $base; ?>css/font-awesome.css">
                <link rel="stylesheet" href="<?php echo $base; ?>css/toastr.min.css">
                <link rel="stylesheet" href="<?php echo $base; ?>css/grapes.min.css">
                <link rel="stylesheet" href="<?php echo $base; ?>css/grapesjs-preset-webpage.min.css">
                <link href="<?php echo $base; ?>css/grapesjs-component-code-editor.min.css" rel="stylesheet" type="text/css"/>
                <link rel="stylesheet" href="<?php echo $base; ?>css/tooltip.css">
                <link rel="stylesheet" href="<?php echo $base; ?>css/grapesjs-plugin-filestack.css">
                <link rel="stylesheet" href="<?php echo $base; ?>css/demos.css">
                <link href="<?php echo $base; ?>css/grapesjs-project-manager.min.css" rel="stylesheet">
                <script src="<?php echo $base; ?>js/jquery.min.js"></script>
                <script src="<?php echo $base; ?>js/bootstrap.bundle.js" type="text/javascript"></script>
                <!--  <script src="<?php echo $base; ?>js/backbone-min.js"></script> -->
                <script src="<?php echo $base; ?>js/toastr.min.js"></script>
                <script src="<?php echo $base; ?>js/grapes.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-preset-webpage.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-bootstrap-elements.js"></script>
                <script src="<?php echo $base; ?>ckeditor/ckeditor.js"></script>                
                <script src="<?php echo $base; ?>js/grapesjs-plugin-ckeditor.min.js"></script>                 
                <script src="<?php echo $base; ?>js/grapesjs-lory-slider.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-tabs.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-component-code-editor.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-custom-code.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-touch.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-parser-postcss.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-tooltip.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-tui-image-editor.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-navbar.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-blocks-bootstrap4.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-code-editor.min.js"></script>                                           
                <script src="<?php echo $base; ?>js/grapesjs-script-editor.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-typed.js"></script>             
                <script src="<?php echo $base; ?>js/grapesjs-page-break.min.js"></script>  
                <script src="<?php echo $base; ?>js/grapesjs-project-manager.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-parser-postcss.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-swiper-slider.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-style-bg.min.js"></script>
                <script>
                    $(".gjs-pn-buttons").click(function () {
                        let imp = $("span").find("[data-tooltip='Import']");
                        alert();
                    });
                </script>
                <?php
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $erow = $conn->query("SELECT id, title, content, style FROM page WHERE id='$id'")->fetch_assoc();
                    $pcontent = $erow['content'];
                    $pstyle = $erow['style'];
                } else {
                    $pcontent = "";
                    $pstyle = "";
                }
                ?>
            </head>
            <body>
                <!-- start menu -->                     
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <div class="container-fluid">
                        <div class="menu-logo">
                            <div class="navbar-brand">
                                <a href="index.php" class="brand-link">
                                    <?php
                                    $logo = IMG_PAGE;
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
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span> 
                        </button>
                        <div id="navbarNavDropdown" class="navbar-collapse collapse
                             justify-content-end">
                            <ul class="navbar-nav nav-pills nav-fill">
                                <li class="nav-item">
                                    <a class="nav-link" href="dashboard.php?cms=pagelist"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="dashboard.php?cms=addpage"><i class="fa fa-file-o" aria-hidden="true"></i> Add New Page</a>
                                </li> 
                                <li class="nav-item">
                                    <a class="nav-link" href="dashboard.php"><i class="fa fa-gear" aria-hidden="true"></i> Dashboard</a> 
                                </li>
                            </ul>   
                        </div>
                    </div>
                </nav>
                <!-- end menu -->
                <div style="display: none">
                    <div class="gjs-logo-cont">
                        <a href="//grapesjs.com"><img class="gjs-logo" src="<?php echo $base; ?>img/grapesjs-logo-cl.png"></a>
                        <div class="gjs-logo-version"></div>
                    </div>
                </div>
                <!-- start content editor -->
                <div id="gjs" class="gjs-editor-cont"
                     style="height: 100%; min-height: 700px; overflow: hidden">
                         <?php
                         echo decodeContent($pcontent) . "\n";
                         echo '<style>' . "\n";
                         echo decodeContent($pstyle) . "\n";
                         echo '</style>' . "\n";
                         ?>

                </div>
                <!-- end content editor -->
                <div id="info-panel" style="display: none">
                    <br />
                    <svg class="info-panel-logo" xmlns="//www.w3.org/2000/svg" version="1">
                    <g id="gjs-logo">
                    <path
                        d="M40 5l-12.9 7.4 -12.9 7.4c-1.4 0.8-2.7 2.3-3.7 3.9 -0.9 1.6-1.5 3.5-1.5 5.1v14.9 14.9c0 1.7 0.6 3.5 1.5 5.1 0.9 1.6 2.2 3.1 3.7 3.9l12.9 7.4 12.9 7.4c1.4 0.8 3.3 1.2 5.2 1.2 1.9 0 3.8-0.4 5.2-1.2l12.9-7.4 12.9-7.4c1.4-0.8 2.7-2.2 3.7-3.9 0.9-1.6 1.5-3.5 1.5-5.1v-14.9 -12.7c0-4.6-3.8-6-6.8-4.2l-28 16.2"
                        style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-width:10;stroke:#fff" />
                    </g></svg>
                    <br />
                    <div class="info-panel-label">
                        <b>GrapesJS Webpage Builder</b> is a simple showcase of what is
                        possible to achieve with the <a class="info-panel-link gjs-four-color"
                                                        target="_blank" href="https://github.com/artf/grapesjs">GrapesJS</a>
                        core library <br /> <br /> For any hint about the demo check the <a
                            class="info-panel-link gjs-four-color" target="_blank"
                            href="https://github.com/artf/grapesjs-preset-webpage">Webpage Preset
                            repository</a> and open an issue. For problems with the builder
                        itself, open an issue on the main <a
                            class="info-panel-link gjs-four-color" target="_blank"
                            href="https://github.com/artf/grapesjs">GrapesJS repository</a> <br />
                        <br /> Being a free and open source project contributors and
                        supporters are extremely welcome. If you like the project support it
                        with a donation of your choice or become a backer/sponsor via <a
                            class="info-panel-link gjs-four-color" target="_blank"
                            href="https://opencollective.com/grapesjs">Open Collective</a>
                    </div>
                </div>
                <div id="blocks"></div>
                <div id="result"></div>
                <?php
                $targetDir = "../uploads/";

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
                ?>

                <script type="text/javascript">

                    let images = <?php echo $storeImage; ?>;
                    let editor = grapesjs.init({
        avoidInlineStyle: 1,
        height: '100%',
        container: '#gjs',
        fromElement: 1, // fromElement: true,
        //pageManager: true,
        showOffsets: 1,
        storageType: '',
        storeOnChange: true,
        storeAfterUpload: true,
        assetManager: {
        storageType: '',
        storeOnChange: true,
        storeAfterUpload: true,
        upload: 'uploads', //for temporary storage
        uploadName: 'files',
        multiUpload: true,
        assets: images,
        uploadFile: function(e) {
            let files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
            let formData = new FormData();
            for (let i in files) {
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
                    let myJSON = [];
                    $.each(result['data'], function(key, value) {
                        myJSON[key] = value;
                    });
                    let images = myJSON;
                    editor.AssetManager.add(images); //adding images to asset manager of GrapesJS
                }
            });
        }
        },
        components: '<div class="txt-red">Hello world!</div>',
        style: '.txt-red{color: red}',
        // Default configurations
        storageManager: {
        id: 'gjs-', // Prefix identifier that will be used on parameters
        type: 'remote', //type: 'local', type: 'remote',Type of the storage
        autosave: false, // Store data automatically
        autoload: true, // Autoload stored data on init
        urlStore: 'store.php?id=<?php echo $id; ?>',
        urlLoad: 'load.php?id=<?php echo $id; ?>',
        contentTypeJson: false,
        storeComponents: true,
        storeStyles: true,
        storeHtml: true,
        storeCss: true

        //stepsBeforeSave: 1 // If autosave enabled, indicates how many changes are necessary before store method is triggered
        },
        commands: {
        defaults: [
            window['@truenorthtechnology/grapesjs-code-editor'].codeCommandFactory()
        ]
        },
        panels: {
        defaults: [{
            buttons: [{
                attributes: {
                    title: 'Open Code'
                },
                className: 'fa fa-code',
                command: 'open-code',
                id: 'open-code'
            }],
            id: 'views'
        }]
        },
        styleManager: {
        clearProperties: 1
        },
        plugins: [
        'gjs-preset-webpage',
        'grapesjs-bootstrap-elements',
        'gjs-plugin-ckeditor',
        'grapesjs-style-bg',
        'grapesjs-lory-slider',
        'grapesjs-tabs',
        'grapesjs-custom-code',
        'grapesjs-typed',
        'grapesjs-indexeddb',
        'grapesjs-echarts',
        'grapesjs-touch',
        'grapesjs-parser-postcss',
        'grapesjs-tooltip',
        'grapesjs-tui-image-editor',
        'gjs-navbar',
        'grapesjs-component-code-editor',
        'grapesjs-script-editor',
        'grapesjs-uikit',
        'grapesjs-page-break',
        'grapesjs-project-manager',
        'grapesjs-ga',
        'grapesjs-swiper-slider'

        ],
        pluginsOpts: {
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
        },
        'gjs-plugin-ckeditor': {
            position: 'center',
            options: {
                startupFocus: true,
                extraAllowedContent: '*(*);*{*}', // Allows any class and any inline style
                allowedContent: true, // Disable auto-formatting, class removing, etc.
                enterMode: CKEDITOR.ENTER_BR,
                extraPlugins: 'sharedspace,justify,colorbutton,panelbutton,font',
                toolbar: [{
                        name: 'styles',
                        items: ['Font', 'FontSize']
                    },
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList']
                    },
                   /* {
                        name: 'headings',
                        items: ['H1', 'H2', 'H3', 'H4', 'H5', 'H6']
                    },*/
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                    {
                        name: 'colors',
                        items: ['TextColor', 'BGColor']
                    }
                ]
            }
        },
        'grapesjs-swiper-slider': {},
        'grapesjs-component-code-editor': {
            panelId: 'views-container'
        },
        'grapesjs-script-editor': {
            toolbarIcon: '<i class="fa fa-puzzle-piece"></i>'
        },
        'grapesjs-lory-slider': {
            sliderBlock: {
                category: 'Extra'
            }
        },
        'grapesjs-tabs': {
            tabsBlock: {
                category: 'Extra'
            }
        },
        'grapesjs-custom-code': {
            blockLabel: 'Custom code',
            category: 'Extra',
            droppable: false,
            modalTitle: 'Insert your code',
            buttonLabel: 'Save'
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
                    ],
                }
            }
        },
        'grapesjs-page-break': {},
        'grapesjs-indexeddb': {},
        'grapesjs-echarts': {
            intl: {
                locale: "en",
                messages: {
                    en: {
                        category: 'Awesome Charts',
                        components: {
                            bars: {
                                name: "Awesome Bars"
                            }
                        }
                    }
                }
            }
        },

        'gjs-navbar': {},
        'gjs-preset-webpage': {
            modalImportTitle: 'Import Template',
            modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
            modalImportContent: function(editor) {
                return editor.getHtml() + '<style>' + editor.getCss() + '</style>';
            },
            filestackOpts: null, //{ key: 'AYmqZc2e8RLGLE7TGkX3Hz' },
            aviaryOpts: false,
            blocksBasicOpts: {
                flexGrid: 1
            },
            customStyleManager: [{
                name: 'General',
                buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom'],
                properties: [{
                        name: 'Alignment',
                        property: 'float',
                        type: 'radio',
                        defaults: 'none',
                        list: [{
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
                    {
                        property: 'position',
                        type: 'select'
                    }
                ]
            }, {
                name: 'Dimension',
                open: false,
                buildProps: ['width', 'flex-width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
                properties: [{
                    id: 'flex-width',
                    type: 'integer',
                    name: 'Width',
                    units: ['px', '%'],
                    property: 'flex-basis',
                    toRequire: 1
                }, {
                    property: 'margin',
                    properties: [{
                            name: 'Top',
                            property: 'margin-top'
                        },
                        {
                            name: 'Right',
                            property: 'margin-right'
                        },
                        {
                            name: 'Bottom',
                            property: 'margin-bottom'
                        },
                        {
                            name: 'Left',
                            property: 'margin-left'
                        }
                    ]
                }, {
                    property: 'padding',
                    properties: [{
                            name: 'Top',
                            property: 'padding-top'
                        },
                        {
                            name: 'Right',
                            property: 'padding-right'
                        },
                        {
                            name: 'Bottom',
                            property: 'padding-bottom'
                        },
                        {
                            name: 'Left',
                            property: 'padding-left'
                        }
                    ]
                }]
            }, {
                name: 'Typography',
                open: false,
                buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-decoration', 'text-shadow'],
                properties: [{
                        name: 'Font',
                        property: 'font-family'
                    },
                    {
                        name: 'Weight',
                        property: 'font-weight'
                    },
                    {
                        name: 'Font color',
                        property: 'color'
                    },
                    {
                        property: 'text-align',
                        type: 'radio',
                        defaults: 'left',
                        list: [{
                                value: 'left',
                                name: 'Left',
                                className: 'fa fa-align-left'
                            },
                            {
                                value: 'center',
                                name: 'Center',
                                className: 'fa fa-align-center'
                            },
                            {
                                value: 'right',
                                name: 'Right',
                                className: 'fa fa-align-right'
                            },
                            {
                                value: 'justify',
                                name: 'Justify',
                                className: 'fa fa-align-justify'
                            }
                        ]
                    }, {
                        property: 'text-decoration',
                        type: 'radio',
                        defaults: 'none',
                        list: [{
                                value: 'none',
                                name: 'None',
                                className: 'fa fa-times'
                            },
                            {
                                value: 'underline',
                                name: 'underline',
                                className: 'fa fa-underline'
                            },
                            {
                                value: 'line-through',
                                name: 'Line-through',
                                className: 'fa fa-strikethrough'
                            }
                        ]
                    }, {
                        property: 'text-shadow',
                        properties: [{
                                name: 'X position',
                                property: 'text-shadow-h'
                            },
                            {
                                name: 'Y position',
                                property: 'text-shadow-v'
                            },
                            {
                                name: 'Blur',
                                property: 'text-shadow-blur'
                            },
                            {
                                name: 'Color',
                                property: 'text-shadow-color'
                            }
                        ]
                    }
                ]
            }, {
                name: 'Decorations',
                open: false,
                buildProps: ['opacity', 'background-color', 'border-radius', 'border', 'box-shadow', 'background'],
                properties: [{
                    type: 'slider',
                    property: 'opacity',
                    defaults: 1,
                    step: 0.01,
                    max: 1,
                    min: 0
                }, {
                    property: 'border-radius',
                    properties: [{
                            name: 'Top',
                            property: 'border-top-left-radius'
                        },
                        {
                            name: 'Right',
                            property: 'border-top-right-radius'
                        },
                        {
                            name: 'Bottom',
                            property: 'border-bottom-left-radius'
                        },
                        {
                            name: 'Left',
                            property: 'border-bottom-right-radius'
                        }
                    ]
                }, {
                    property: 'box-shadow',
                    properties: [{
                            name: 'X position',
                            property: 'box-shadow-h'
                        },
                        {
                            name: 'Y position',
                            property: 'box-shadow-v'
                        },
                        {
                            name: 'Blur',
                            property: 'box-shadow-blur'
                        },
                        {
                            name: 'Spread',
                            property: 'box-shadow-spread'
                        },
                        {
                            name: 'Color',
                            property: 'box-shadow-color'
                        },
                        {
                            name: 'Shadow type',
                            property: 'box-shadow-type'
                        }
                    ]
                }, {
                    property: 'background',
                    properties: [{
                            name: 'Image',
                            property: 'background-image'
                        },
                        {
                            name: 'Repeat',
                            property: 'background-repeat'
                        },
                        {
                            name: 'Position',
                            property: 'background-position'
                        },
                        {
                            name: 'Attachment',
                            property: 'background-attachment'
                        },
                        {
                            name: 'Size',
                            property: 'background-size'
                        }
                    ]
                }]
            }, {
                name: 'Extra',
                open: false,
                buildProps: ['transition', 'perspective', 'transform'],
                properties: [{
                    property: 'transition',
                    properties: [{
                            name: 'Property',
                            property: 'transition-property'
                        },
                        {
                            name: 'Duration',
                            property: 'transition-duration'
                        },
                        {
                            name: 'Easing',
                            property: 'transition-timing-function'
                        }
                    ]
                }, {
                    property: 'transform',
                    properties: [{
                            name: 'Rotate X',
                            property: 'transform-rotate-x'
                        },
                        {
                            name: 'Rotate Y',
                            property: 'transform-rotate-y'
                        },
                        {
                            name: 'Rotate Z',
                            property: 'transform-rotate-z'
                        },
                        {
                            name: 'Scale X',
                            property: 'transform-scale-x'
                        },
                        {
                            name: 'Scale Y',
                            property: 'transform-scale-y'
                        },
                        {
                            name: 'Scale Z',
                            property: 'transform-scale-z'
                        }
                    ]
                }]
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
                        title: 'Row',
                    }, {
                        value: 'row-reverse',
                        name: 'Row reverse',
                        className: 'icons-flex icon-dir-row-rev',
                        title: 'Row reverse',
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
                    }]
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
                        className: 'icons-flex icon-just-end',
                        title: 'End'
                    }, {
                        value: 'space-between',
                        className: 'icons-flex icon-just-sp-bet',
                        title: 'Space between'
                    }, {
                        value: 'space-around',
                        className: 'icons-flex icon-just-sp-ar',
                        title: 'Space around'
                    }, {
                        value: 'center',
                        className: 'icons-flex icon-just-sp-cent',
                        title: 'Center'
                    }]
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
                    }]
                }, {
                    name: 'Flex Children',
                    property: 'label-parent-flex',
                    type: 'integer'
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
        }
        },
        canvas: {
            styles: [
                '../css/bootstrap.min.css'
            ],
            scripts: [
                '../js/jquery.min.js',
                '../js/popper.min.js',
                '../js/bootstrap.min.js'
            ]
                        }
        });


window.editor = editor;
let pn = editor.Panels;
let modal = editor.Modal;
let cmdm = editor.Commands;
let blockManager = editor.BlockManager;

cmdm.add('canvas-clear', function() {
    if (confirm('Are you sure to clean the canvas?')) {
        let comps = editor.DomComponents.clear();
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
cmdm.add('set-device-tablet', {
    run: function(ed) {
        ed.setDevice('Tablet');
    },
    stop: function() {}
});
cmdm.add('set-device-mobile', {
    run: function(ed) {
        ed.setDevice('Mobile portrait');
    },
    stop: function() {}
});
// Store DB
cmdm.add('dashboard', {
    run: function(em, sender) {
        sender.set('active', true);
        dashboardPage();
    }
});
cmdm.add('save-page', {
    run: function(em, sender) {
        sender.set('active', true);
        saveContent();
    }
});
cmdm.add('view-page', {
    run: function(em, sender) {
        sender.set('active', true);
        viewContent();
    }
});
cmdm.add('page-list', {
    run: function(em, sender) {
        sender.set('active', true);
        pageList();
    }
});
cmdm.add('refresh-page', {
    run: function(em, sender) {
        sender.set('active', true);
        refreshContent();
    }
});
cmdm.add('new-page', {
    run: function(em, sender) {
        sender.set('active', true);
        newContent();
    }
});
cmdm.add('view-page', {
    run: function(em, sender) {
        sender.set('active', true); //get full HTML structure after design
        viewContent();
    }
});

// Add info command

let mdlClass = 'gjs-mdl-dialog-sm';
let infoContainer = document.getElementById('info-panel');
cmdm.add('open-info', function() {
    let mdlDialog = document.querySelector('.gjs-mdl-dialog');
    mdlDialog.className += ' ' + mdlClass;
    infoContainer.style.display = 'block';
    modal.setTitle('About this demo');
    modal.setContent(infoContainer);
    modal.open();
    modal.getModel().once('change:open', function() {
        mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
    });
});

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
//More Buttom
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
    id: 'save-page',
    className: 'fa fa-floppy-o',
    command: 'save-page',
    attributes: {
        title: 'Save page',
        'data-tooltip-pos': 'bottom'
    }
}]);
pn.addButton('options', [{
    id: 'page-list',
    className: 'fa fa-list',
    command: 'page-list',
    attributes: {
        title: 'Page list',
        'data-tooltip-pos': 'bottom'
    }
}]);
pn.addButton('options', [{
    id: 'view-page',
    className: 'fa fa-file-text-o',
    command: 'view-page',
    attributes: {
        title: 'View Page',
        'data-tooltip-pos': 'bottom'
    }
}]);
pn.addButton('options', [{
    id: 'refresh-page',
    className: 'fa fa-refresh',
    command: 'refresh-page',
    attributes: {
        title: 'Refresh page',
        'data-tooltip-pos': 'bottom'
    }
}]);
pn.addButton('options', [{
    id: 'new-page',
    className: 'fa fa-file-o',
    command: 'new-page',
    attributes: {
        title: 'New page',
        'data-tooltip-pos': 'bottom'
    }
}]);


blockManager.add('testBlock', {
    label: 'Block',
    attributes: {
        class: 'gjs-fonts gjs-f-b1'
    },
    content: `<div style="padding-top:50px; padding-bottom:50px; text-align:center">Test block</div>`
});
blockManager.add('covers1', {
    label: '<div class="gjs-block-label">Covers 1</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Covers",
    activate: 1,
    content: '<div class="py-5 text-center h-100 align-items-center d-flex">' +
        '<div class="container py-5">' +
        '<div class="row">' +
        '<div class="mx-auto col-lg-8 col-md-10">' +
        '<h1 class="display-3 mb-4">A wonderful serenity</h1>' +
        '<p class="lead mb-5">Has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence.</p> <a href="#" class="btn btn-lg btn-primary mx-1">Take me there</a> <a class="btn btn-lg mx-1 btn-outline-primary" href="#">Go</a>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'
});

blockManager.add('covers2', {
    label: '<div class="gjs-block-label">Covers 2</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Covers",
    activate: 1,
    content: '<div class="py-5 text-center">' +
        '<div class="container">' +
        '<div class="row">' +
        '<div class="bg-white p-5 mx-auto col-md-8 col-10">' +
        '<h3 class="display-3">I feel the charm</h3>' +
        '<p class="mb-3 lead">Of existence in this spot</p>' +
        '<p class="mb-4">Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.</p> <a class="btn btn-outline-primary" href="#">Read more</a>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'
});

blockManager.add("card", {
    label: '<div class="gjs-block-label">Card</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Cards",
    activate: 1,
    content: '<div class="card">' +
        '<img class="card-img-top" src="..." alt="..." >' +
        '<div class="card-body">' +
        '<h4 class="card-title">Card title</h4>' +
        '<p class="card-text">Some quick example text to build on the card title content.</p>' +
        '<a href="#" class="btn btn-primary">Go somewhere</a>' +
        '</div>' +
        '</div>'
});

blockManager.add("card", {
    label: '<div class="gjs-block-label">Card</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Cards"
});

blockManager.add("input", {
    label: '<div class="gjs-block-label">Input</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Inputs"
});

blockManager.add("form", {
    label: '<div class="gjs-block-label">Form</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Forms"
});

blockManager.add("grid", {
    label: '<div class="gjs-block-label">Grid</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Grids"
});

blockManager.add("nav", {
    label: '<div class="gjs-block-label">Nav</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Navs"
});

blockManager.add("navbar", {
    label: '<div class="gjs-block-label">Navbars</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Navbars"
});

blockManager.add("list", {
    label: '<div class="gjs-block-label">List</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "List"
});

blockManager.add("media", {
    label: '<div class="gjs-block-label">Media</div>',
    attributes: {
        class: "fa fa-id-card-o"
    },
    category: "Media"
});


// Simple warn notifier
let origWarn = console.warn;
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
let titles = document.querySelectorAll('*[title]');
for (let i = 0; i < titles.length; i++) {
    let el = titles[i];
    let title = el.getAttribute('title');
    title = title ? title.trim() : '';
    if (!title)
        break;
    el.setAttribute('data-tooltip', title);
    el.setAttribute('title', '');
}

// Show borders by default
pn.getButton('options', 'sw-visibility').set('active', 1);


// Do stuff on load
editor.on('load', function() {
    let $ = grapesjs.$;
    // Show logo with the version
    let logoCont = document.querySelector('.gjs-logo-cont');
    document.querySelector('.gjs-logo-version').innerHTML = 'v' + grapesjs.version;
    let logoPanel = document.querySelector('.gjs-pn-commands');
    logoPanel.appendChild(logoCont);
    // Load and show settings and style manager
    let openTmBtn = pn.getButton('views', 'open-tm');
    openTmBtn && openTmBtn.set('active', 1);
    let openSm = pn.getButton('views', 'open-sm');
    openSm && openSm.set('active', 1);
    // Add Settings Sector
    let traitsSector = $('<div class="gjs-sm-sector no-select">' +
        '<div class="gjs-sm-title"><span class="icon-settings fa fa-cog"></span> Settings</div>' +
        '<div class="gjs-sm-properties" style="display: none;"></div></div>');
    let traitsProps = traitsSector.find('.gjs-sm-properties');
    traitsProps.append($('.gjs-trt-traits'));
    $('.gjs-sm-sectors').before(traitsSector);
    traitsSector.find('.gjs-sm-title').on('click', function() {
        let traitStyle = traitsProps.get(0).style;
        let hidden = traitStyle.display == 'none';
        if (hidden) {
            traitStyle.display = 'block';
        } else {
            traitStyle.display = 'none';
        }
    });
    // Open block manager
    let openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
    openBlocksBtn && openBlocksBtn.set('active', 1);
});

// function buttom


$(function() {
    let $menu = $('#menu'),
        $target = $('#target');

    $menu.on('click', '> a', function(event) {
        let $this = $(this);
        event.preventDefault();
        $target.load($this.attr('href'));
    });
});

function viewContent() {
    let id = '<?php echo $id; ?>';
    let url = 'view.php?id=' + id;
    window.open(url);
}

function saveContent() {
    let idp = '<?php echo $id; ?>';
    let content = editor.getHtml(); //get html content of document
    let style = editor.getCss(); //get css content of document
    // Get edit field value
    $.ajax({
        url: 'save.php',
        type: 'post',
        data: {
            idp: idp,
            content: content,
            style: style
        }
    }).done(function(rsp) {
        alert(rsp);
    });
}

function pageList() {
    let url = 'dashboard.php?cms=pagelist';
    location.replace(url);
}

function dashboardPage() {
    let url = 'dashboard.php';
    location.replace(url);
}

function refreshContent() {
    location.reload();
}

function newContent() {
    let url = 'dashboard.php?cms=addpage';
    location.replace(url);
}

function clearContent() {
    let clear = 'clear';
    $.ajax({
        url: 'clearcontent.php',
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
    let files = $('#gjs-am-uploadFile')[0].files[0];
    formData.append('file', files);
    aler(files);
    /*
     let files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
     let formData = new FormData();
     for(let i in files){
     formData.append('file-'+i, files[i]) //containing all the selected images from local
     }*/
    $.ajax({
        url: 'upload_image.php',
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false
    }).done(function(result) {
        let myJSON = [];
        $.each(result['data'], function(key, value) {
            myJSON[key] = value;
        });
        let images = myJSON;
        editor.AssetManager.add(images); //adding images to asset manager of GrapesJS
    });
}

                                                   
                </script>
            </body>
        </html>
        <?php
    } else {
        header('Location: dashboard.php');
        exit();
    }
} else {
    header('Location: ../signin/login.php');
    exit();
}
?>
