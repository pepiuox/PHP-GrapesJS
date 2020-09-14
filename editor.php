<?php
include 'conn.php';
$base = "http://{$_SERVER['HTTP_HOST']}/grapesjs/";
$hostlk = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $lpath = "https";
} else {
    $lpath = "http";
}
$lpath .= "://";
$lpath .= $_SERVER['HTTP_HOST'];
$lpath .= $_SERVER['PHP_SELF'];

$fileName = basename($_SERVER['PHP_SELF']);

$path = basename($_SERVER['REQUEST_URI']);

if ($hostlk === $lpath) {
    header("Location: editor.php?w=editor");
    exit();
}
if (isset($_GET['w'])) {
    $w = $_GET['w'];
}

$id = '';
$pcontent = '';
$pstyle = '';
if ($w == "editor") {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $erow = $conn->query("SELECT id, title, content, style FROM page WHERE id='$id'")->fetch_assoc();
        $pcontent = $erow['content'];
        $pstyle = $erow['style'];
        // View list pages
        ?>
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>PHP GrapesJS</title>

                <link rel="stylesheet" href="<?php echo $base; ?>css/toastr.min.css">
                <link rel="stylesheet" href="<?php echo $base; ?>dist/css/grapes.min.css">
                <link rel="stylesheet"
                      href="<?php echo $base; ?>css/grapesjs-preset-webpage.min.css">
                <link rel="stylesheet" href="<?php echo $base; ?>css/tooltip.css">
                <link rel="stylesheet"
                      href="<?php echo $base; ?>css/grapesjs-plugin-filestack.css">
                <link rel="stylesheet" href="<?php echo $base; ?>css/demos.css">
                <script src="<?php echo $base; ?>js/jquery.min.js"></script>
                <!--  <script src="<?php echo $base; ?>js/backbone-min.js"></script> -->
                <script src="<?php echo $base; ?>js/toastr.min.js"></script>
                <script src="<?php echo $base; ?>js/grapes.min.js"></script>
                <script src="<?php echo $base; ?>ckeditor/ckeditor.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-preset-webpage.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-lory-slider.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-tabs.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-indexeddb.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-custom-code.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-touch.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-parser-postcss.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-tooltip.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-tui-image-editor.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-navbar.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-blocks-bootstrap4.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-code-editor.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-plugin-ckeditor.min.js"></script>
                <script src="<?php echo $base; ?>js/grapesjs-typed.js"></script>

                <script>
                    $(".gjs-pn-buttons").click(function () {
                        var imp = $("span").find("[data-tooltip='Import']");
                        alert();
                    });
                </script>
            </head>
            <body>
                <div style="display: none">
                    <div class="gjs-logo-cont">
                        <a href="//grapesjs.com"><img class="gjs-logo" src="<?php echo $base; ?>img/grapesjs-logo-cl.png"></a>
                        <div class="gjs-logo-version"></div>
                    </div>
                </div>
                <!-- start content editor -->
                <div id="gjs" class="gjs-editor-cont"
                     style="height: 100%; min-height: 700px; overflow: hidden">
                         <?php echo html_entity_decode($pcontent); ?>

                    <?php
                    echo '<style>' . "\n";
                    echo html_entity_decode($pstyle) . "\n";
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
                $targetDir = "uploads/";

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

                    var images = <?php echo $storeImage; ?>;
                    var editor = grapesjs.init({
                        avoidInlineStyle: 1,
                        height: '100%',
                        container: '#gjs',
                        fromElement: 1,
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
                            uploadFile: function (e) {
                                var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                                var formData = new FormData();
                                for (var i in files) {
                                    formData.append('file-' + i, files[i]) //containing all the selected images from local
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
                                    success: function (result) {
                                        var myJSON = [];
                                        $.each(result['data'], function (key, value) {
                                            myJSON[key] = value;
                                        });
                                        var images = myJSON;
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
                            //urlStore: 'store.php?id=<?php echo $id; ?>',
                            //urlLoad: 'load.php?id=<?php echo $id; ?>',
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
                            defaults: [
                                {
                                    buttons: [
                                        {
                                            attributes: {title: 'Open Code'},
                                            className: 'fa fa-code',
                                            command: 'open-code',
                                            id: 'open-code'
                                        }
                                    ],
                                    id: 'views'
                                }
                            ]
                        },
                        styleManager: {clearProperties: 1},
                        plugins: [
                            'gjs-preset-webpage',
                            'grapesjs-lory-slider',
                            'grapesjs-tabs',
                            'grapesjs-custom-code',
                            'grapesjs-typed',
                            'grapesjs-indexeddb',
                            'grapesjs-touch',
                            'grapesjs-parser-postcss',
                            'grapesjs-tooltip',
                            'grapesjs-tui-image-editor',
                            'gjs-navbar'
                        ],
                        pluginsOpts: {
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
                                            'Text row three',
                                        ],
                                    }
                                }
                            },
                            'grapesjs-indexeddb': {},
                            'gjs-navbar': {},
                            'gjs-preset-webpage': {
                                modalImportTitle: 'Import Template',
                                modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
                                modalImportContent: function (editor) {
                                    return editor.getHtml() + '<style>' + editor.getCss() + '</style>';
                                },
                                filestackOpts: null, //{ key: 'AYmqZc2e8RLGLE7TGkX3Hz' },
                                aviaryOpts: false,
                                blocksBasicOpts: {flexGrid: 1},
                                customStyleManager: [{
                                        name: 'General',
                                        buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom'],
                                        properties: [{
                                                name: 'Alignment',
                                                property: 'float',
                                                type: 'radio',
                                                defaults: 'none',
                                                list: [
                                                    {value: 'none', className: 'fa fa-times'},
                                                    {value: 'left', className: 'fa fa-align-left'},
                                                    {value: 'right', className: 'fa fa-align-right'}
                                                ]
                                            },
                                            {property: 'position', type: 'select'}
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
                                                properties: [
                                                    {name: 'Top', property: 'margin-top'},
                                                    {name: 'Right', property: 'margin-right'},
                                                    {name: 'Bottom', property: 'margin-bottom'},
                                                    {name: 'Left', property: 'margin-left'}
                                                ]
                                            }, {
                                                property: 'padding',
                                                properties: [
                                                    {name: 'Top', property: 'padding-top'},
                                                    {name: 'Right', property: 'padding-right'},
                                                    {name: 'Bottom', property: 'padding-bottom'},
                                                    {name: 'Left', property: 'padding-left'}
                                                ]
                                            }]
                                    }, {
                                        name: 'Typography',
                                        open: false,
                                        buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-decoration', 'text-shadow'],
                                        properties: [
                                            {name: 'Font', property: 'font-family'},
                                            {name: 'Weight', property: 'font-weight'},
                                            {name: 'Font color', property: 'color'},
                                            {
                                                property: 'text-align',
                                                type: 'radio',
                                                defaults: 'left',
                                                list: [
                                                    {value: 'left', name: 'Left', className: 'fa fa-align-left'},
                                                    {value: 'center', name: 'Center', className: 'fa fa-align-center'},
                                                    {value: 'right', name: 'Right', className: 'fa fa-align-right'},
                                                    {value: 'justify', name: 'Justify', className: 'fa fa-align-justify'}
                                                ]
                                            }, {
                                                property: 'text-decoration',
                                                type: 'radio',
                                                defaults: 'none',
                                                list: [
                                                    {value: 'none', name: 'None', className: 'fa fa-times'},
                                                    {value: 'underline', name: 'underline', className: 'fa fa-underline'},
                                                    {value: 'line-through', name: 'Line-through', className: 'fa fa-strikethrough'}
                                                ]
                                            }, {
                                                property: 'text-shadow',
                                                properties: [
                                                    {name: 'X position', property: 'text-shadow-h'},
                                                    {name: 'Y position', property: 'text-shadow-v'},
                                                    {name: 'Blur', property: 'text-shadow-blur'},
                                                    {name: 'Color', property: 'text-shadow-color'}
                                                ]
                                            }]
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
                                                properties: [
                                                    {name: 'Top', property: 'border-top-left-radius'},
                                                    {name: 'Right', property: 'border-top-right-radius'},
                                                    {name: 'Bottom', property: 'border-bottom-left-radius'},
                                                    {name: 'Left', property: 'border-bottom-right-radius'}
                                                ]
                                            }, {
                                                property: 'box-shadow',
                                                properties: [
                                                    {name: 'X position', property: 'box-shadow-h'},
                                                    {name: 'Y position', property: 'box-shadow-v'},
                                                    {name: 'Blur', property: 'box-shadow-blur'},
                                                    {name: 'Spread', property: 'box-shadow-spread'},
                                                    {name: 'Color', property: 'box-shadow-color'},
                                                    {name: 'Shadow type', property: 'box-shadow-type'}
                                                ]
                                            }, {
                                                property: 'background',
                                                properties: [
                                                    {name: 'Image', property: 'background-image'},
                                                    {name: 'Repeat', property: 'background-repeat'},
                                                    {name: 'Position', property: 'background-position'},
                                                    {name: 'Attachment', property: 'background-attachment'},
                                                    {name: 'Size', property: 'background-size'}
                                                ]
                                            }]
                                    }, {
                                        name: 'Extra',
                                        open: false,
                                        buildProps: ['transition', 'perspective', 'transform'],
                                        properties: [{
                                                property: 'transition',
                                                properties: [
                                                    {name: 'Property', property: 'transition-property'},
                                                    {name: 'Duration', property: 'transition-duration'},
                                                    {name: 'Easing', property: 'transition-timing-function'}
                                                ]
                                            }, {
                                                property: 'transform',
                                                properties: [
                                                    {name: 'Rotate X', property: 'transform-rotate-x'},
                                                    {name: 'Rotate Y', property: 'transform-rotate-y'},
                                                    {name: 'Rotate Z', property: 'transform-rotate-z'},
                                                    {name: 'Scale X', property: 'transform-scale-x'},
                                                    {name: 'Scale Y', property: 'transform-scale-y'},
                                                    {name: 'Scale Z', property: 'transform-scale-z'}
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
                                                list: [
                                                    {value: 'block', name: 'Disable'},
                                                    {value: 'flex', name: 'Enable'}
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
                                    }
                                ]
                            }
                        }/*
                         // Canvas
                         canvas: {
                         styles: [
                         'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'
                         ],
                         scripts: [
                         'https://code.jquery.com/jquery-3.3.1.min.js',
                         'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
                         'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'
                         ]
                         }*/
                    });

                    // Store and load events
                    // Store and load events
                    //   editor.on('storage:load', function(e) {
                    //      console.log('Loaded ', e)
                    //      });
                    //    editor.on('storage:store', function(e) { console.log('Stored ', e) });
                    // Store and load events
                    /*
                     editor.StorageManager.add('local', {
                     // New logic for the local storage
                     load() {
                     // ...
                     },
                     store() {
                     // ...
                     },
                     });
                     */
                    // More functions

                    var pn = editor.Panels;
                    var modal = editor.Modal;
                    var cmdm = editor.Commands;

                    cmdm.add('canvas-clear', function () {
                        if (confirm('Are you sure to clean the canvas?')) {
                            var comps = editor.DomComponents.clear();
                            setTimeout(function () {
                                localStorage.clear();
                            }, 0);
                        }
                    });
                    cmdm.add('set-device-desktop', {
                        run: function (ed) {
                            ed.setDevice('Desktop');
                        },
                        stop: function () {}
                    });
                    cmdm.add('set-device-tablet', {
                        run: function (ed) {
                            ed.setDevice('Tablet');
                        },
                        stop: function () {}
                    });
                    cmdm.add('set-device-mobile', {
                        run: function (ed) {
                            ed.setDevice('Mobile portrait');
                        },
                        stop: function () {}
                    });
                    // Store DB
                    cmdm.add('save-database', {
                        run: function (em, sender) {
                            sender.set('active', true);
                            saveContent();
                        }
                    });
                    cmdm.add('view-page', {
                        run: function (em, sender) {
                            sender.set('active', true);
                            viewContent();
                        }
                    });
                    cmdm.add('update-page', {
                        run: function (em, sender) {
                            sender.set('active', true);
                            updateContent();
                        }
                    });
                    cmdm.add('refresh-page', {
                        run: function (em, sender) {
                            sender.set('active', true);
                            refreshContent();
                        }
                    });
                    cmdm.add('new-page', {
                        run: function (em, sender) {
                            sender.set('active', true);
                            newContent();
                        }
                    });
                    cmdm.add('view-page', {
                        run: function (em, sender) {
                            sender.set('active', true); //get full HTML structure after design
                            viewContent();
                        }
                    });
                    // Add info command

                    var mdlClass = 'gjs-mdl-dialog-sm';
                    var infoContainer = document.getElementById('info-panel');
                    cmdm.add('open-info', function () {
                        var mdlDialog = document.querySelector('.gjs-mdl-dialog');
                        mdlDialog.className += ' ' + mdlClass;
                        infoContainer.style.display = 'block';
                        modal.setTitle('About this demo');
                        modal.setContent(infoContainer);
                        modal.open();
                        modal.getModel().once('change:open', function () {
                            mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
                        });
                    });
                    pn.addButton('options', {
                        id: 'open-info',
                        className: 'fa fa-question-circle',
                        command: function () {
                            editor.runCommand('open-info');
                        },
                        attributes: {
                            'title': 'About',
                            'data-tooltip-pos': 'bottom'
                        }
                    });
                    //More Buttom
                    pn.addButton('options', [{
                            id: 'save-database',
                            className: 'fa fa-floppy-o',
                            command: 'save-database',
                            attributes: {
                                title: 'Save page',
                                'data-tooltip-pos': 'bottom'
                            }}]);
                    pn.addButton('options', [{
                            id: 'update-page',
                            className: 'fa fa-pencil-square-o',
                            command: 'update-page',
                            attributes: {
                                title: 'Update page',
                                'data-tooltip-pos': 'bottom'
                            }}]);
                    pn.addButton('options', [{
                            id: 'view-page',
                            className: 'fa fa-file-text-o',
                            command: 'view-page',
                            attributes: {
                                title: 'View Page',
                                'data-tooltip-pos': 'bottom'
                            }}]);
                    pn.addButton('options', [{
                            id: 'refresh-page',
                            className: 'fa fa-refresh',
                            command: 'refresh-page',
                            attributes: {
                                title: 'Refresh page',
                                'data-tooltip-pos': 'bottom'
                            }}]);
                    pn.addButton('options', [{
                            id: 'new-page',
                            className: 'fa fa-file-o',
                            command: 'new-page',
                            attributes: {
                                title: 'New page',
                                'data-tooltip-pos': 'bottom'
                            }}]);
                    // Simple warn notifier
                    var origWarn = console.warn;
                    toastr.options = {
                        closeButton: true,
                        preventDuplicates: true,
                        showDuration: 250,
                        hideDuration: 150
                    };
                    console.warn = function (msg) {
                        if (msg.indexOf('[undefined]') == -1) {
                            toastr.warning(msg);
                        }
                        origWarn(msg);
                    };
                    // Add and beautify tooltips
                    [['sw-visibility', 'Show Borders'], ['preview', 'Preview'], ['fullscreen', 'Fullscreen'],
                        ['export-template', 'Export'], ['undo', 'Undo'], ['redo', 'Redo'],
                        ['gjs-open-import-webpage', 'Import'], ['canvas-clear', 'Clear canvas']]
                            .forEach(function (item) {
                                pn.getButton('options', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
                            });
                    [['open-sm', 'Style Manager'], ['open-layers', 'Layers'], ['open-blocks', 'Blocks']]
                            .forEach(function (item) {
                                pn.getButton('views', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
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

                    // Show borders by default
                    pn.getButton('options', 'sw-visibility').set('active', 1);


                    // Do stuff on load
                    editor.on('load', function () {
                        var $ = grapesjs.$;
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
                        // Add Settings Sector
                        var traitsSector = $('<div class="gjs-sm-sector no-select">' +
                                '<div class="gjs-sm-title"><span class="icon-settings fa fa-cog"></span> Settings</div>' +
                                '<div class="gjs-sm-properties" style="display: none;"></div></div>');
                        var traitsProps = traitsSector.find('.gjs-sm-properties');
                        traitsProps.append($('.gjs-trt-traits'));
                        $('.gjs-sm-sectors').before(traitsSector);
                        traitsSector.find('.gjs-sm-title').on('click', function () {
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
                    });

                    // function buttom

                    window.editor = editor;
                    function viewContent() {
                        var id = '<?php echo $id; ?>';
                        var url = 'view.php?id=' + id;
                        window.open(url);
                    }
                    function saveContent() {
                        var idp = '<?php echo $id; ?>';
                        var content = editor.getHtml(); //get html content of document
                        var style = editor.getCss(); //get css content of document
                        // Get edit field value
                        $.ajax({
                            url: 'save.php',
                            type: 'post',
                            data: {idp: idp, content: content, style: style}
                        }).done(function (rsp) {
                            alert(rsp);
                        });
                    }
                    function updateContent() {
                        var idp = '<?php echo $id; ?>';
                        var content = editor.getHtml(); //get html content of document
                        var style = editor.getCss(); //get css content of document
                        // Get edit field value
                        $.ajax({
                            url: 'update.php',
                            type: 'post',
                            data: {idp: idp, content: content, style: style}
                        }).done(function (rsp) {
                            alert(rsp);
                        });
                    }

                    function refreshContent() {
                        location.reload();
                    }
                    function newContent() {
                        var url = 'editor.php?w=add';
                        location.replace(url);
                    }
                    function clearContent() {
                        var clear = 'clear';
                        $.ajax({
                            url: 'clearcontent.php',
                            type: 'post',
                            data: {clear: clear}
                        }).done(function (rsp) {
                            $('#result').html(rsp);
                        });
                    }
                    function getContent() {
                    }
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
                            url: 'upload_image.php',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false
                        }).done(function (result) {
                            var myJSON = [];
                            $.each(result['data'], function (key, value) {
                                myJSON[key] = value;
                            });
                            var images = myJSON;
                            editor.AssetManager.add(images); //adding images to asset manager of GrapesJS
                        });
                    }

                    $(document).ready(function (argument) {
                        $('.btn-save-button').click(function () {
                            saveContent();
                        });
                        $('#save').click(function () {
                            var content = editor.getHtml();
                            var style = editor.getCss(); //get css content of document
                            // Get edit field value

                            $.ajax({
                                url: 'savecontent.php',
                                type: 'post',
                                data: {content: content, style: style}
                            }).done(function (rsp) {
                                alert(rsp);
                            });
                        });
                        $('#clear').click(function () {
                            var clear = 'clear';
                            $.ajax({
                                url: 'clearcontent.php',
                                type: 'post',
                                data: {clear: clear}
                            }).done(function (rsp) {
                                alert(rsp);
                            });
                        });
                    });

                </script>

            </body>
        </html>
        <?php
    } else {
        header("Location: editor.php?w=add");
        exit();
    }
} else if ($w == "add") {
    ?>
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
            <title>Content Editor</title>
            <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
        </head>
        <body>
            <div class="container">           
                <div class="row pt-4">              
                    <div class="col-md-6">
                        <a class="btn btn-primary" href="list.php"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                    </div>
                    <div class="col-md-6">                    
                        <a class="btn btn-primary" href="settings.php"><i class="fa fa-gear" aria-hidden="true"></i> Edit Settings</a>         
                    </div>               
                </div>
                <div class="row">
                    <div class="col-md-12 py-3">
                        <?php
                        // Add page properties
                        if (isset($_POST['submit'])) {
                            if (!empty($_FILES['image']['name'])) {
                                $errors = array();
                                $file_name = $_FILES['image']['name'];
                                $file_size = $_FILES['image']['size'];
                                $file_tmp = $_FILES['image']['tmp_name'];
                                $file_type = $_FILES['image']['type'];
                                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                                // $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

                                $extensions = array(
                                    "jpeg",
                                    "jpg",
                                    "png",
                                    "gif"
                                );

                                if (in_array($file_ext, $extensions)) {
                                    if (file_exists("uploads/" . $file_name)) {
                                        $errors[] = $file_name . " is already exists.";
                                    } else {
                                        move_uploaded_file($file_tmp, "uploads/" . $file_name);
                                        echo '<div class="alert alert-success" role="alert">';
                                        echo "Your file was uploaded successfully.";
                                        echo '</div>';
                                    }
                                } else {
                                    $errors[] = "Extension not allowed, please choose a JPEG, JPG, PNG or GIF file. <br/>Or you have not selected a file";
                                }

                                if ($file_size > 2097152) {
                                    $errors[] = 'File size must be excately 2 MB';
                                }

                                if (empty($errors) === true) {
                                    echo '<div class="alert alert-success" role="alert">';
                                    echo "Success";
                                    echo '</div>';
                                } else {
                                    foreach ($errors as $key => $item) {
                                        echo '<div class="alert alert-danger" role="alert">';
                                        echo "$item <br>";
                                        echo '</div>';
                                    }
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">';
                                echo "It is necessary to add an image that relates the page";
                                echo '</div>';
                            }

                            $title = $_POST['title']; // Page name
                            $link = strtolower(str_replace(" ", "-", $_POST['link'])); // Page link
                            $keyword = $_POST['keyword'];
                            $classification = $_POST['classification'];
                            $description = $_POST['description'];
                            $parent = $_POST['parent'];
                            // Check if parent exist or is empty
                            if (!is_int($parent) || empty($parent)) {
                                $parent = 0;
                            }
                            $active = $_POST['active'];

                            // Insert info in table PAGE 
                            $sql = "INSERT INTO page ( title, link, keyword, classification, description, image, parent, active) VALUES ('" . protect($title) . "', '" . protect($link) . "', '" . protect($keyword) . "', '" . protect($classification) . "', '" . protect($description) . "', '" . protect($file_name) . "','" . protect($parent) . "', '" . protect($active) . "')";
                            if ($conn->query($sql) === TRUE) {
                                $last_id = $conn->insert_id;
                                // Insert info in table MENU
                                $sqlm = "INSERT INTO menu (page_id, title_page, link_page, parent_id) VALUES ('" . $last_id . "', '" . protect($title) . "', '" . protect($link) . "', '" . protect($parent) . "')";
                                if ($conn->query($sqlm) === TRUE) {
                                    /*
                                      // Store in folder pages
                                      $directory = 'pages/';
                                      //Check if the directory already exists.
                                      if (!is_dir($directory)) {
                                      //Directory does not exist, so lets create it.
                                      mkdir($directory, 0755, true);
                                      }
                                      // Change to the extension you want.
                                      $ext_files = ".html";
                                      $link_path = $directory . $link . $ext_files;
                                      $myfile = fopen($link_path, "w") or die("Unable to open file!");
                                     */
                                    // For redirect in php
                                    /*
                                      $txt = '<?php header("Location: ../view.php?id=' . $last_id . '"); ?>';
                                     */
                                    // For redirect in html
                                    /*
                                      $txt = '<html><head><script>window.location.replace("../view.php?id=' . $last_id . '");</script></head><body></body></html>';
                                      fwrite($myfile, $text);
                                      fclose($myfile);
                                     */
                                    echo '<div class="alert alert-success" role="alert">';
                                    echo "Page " . $title . " : Created ";
                                    echo '</div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">';
                                    echo "Failed: The page was not added to the menu";
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">';
                                echo "Failed: The page has not been created";
                                echo '</div>';
                            }
                            echo '<meta http-equiv="refresh" content="3; url=builder.php?id=' . $last_id . '" />';
                        }
                        echo '<h3>Add new page</h3>' . "\n";
                        echo '<form method="post" enctype="multipart/form-data">' . "\n";
                        echo '<div class="row"><div class="col-md-6">' . "\n";
                        echo '<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title">
  </div>' . "\n";
                        echo '</div><div class="col-md-6">' . "\n";
                        echo '<div class="form-group">
    <label for="link">Link</label>
    <input type="text" class="form-control" id="link" name="link">
  </div>' . "\n";
                        echo '</div></div><div class="form-group">
    <label for="keyword">Keyword</label>
    <input type="text" class="form-control" id="keyword" name="keyword">
  </div>' . "\n";
                        echo '<div class="form-group">
    <label for="classification">Classification</label>
    <input type="text" class="form-control" id="classification" name="classification">
  </div>' . "\n";
                        echo '<div class="form-group">
    <label for="description">Description</label>
    <input type="text" class="form-control" id="description" name="description">
  </div>' . "\n";
                        echo '<div class="form-group">
    <label for="image">Image:</label>
    <input type="file" class="form-control" id="imagen" name="image">
  </div>' . "\n";
                        echo '<div class="form-group">
    <label for="parent">Parent</label>' . "\n";
                        echo nparent();
                        echo '</div>' . "\n";

                        echo '<div class="form-group">
    <label for="active">Active</label>
    <select class="form-control" id="active" name="active">
<option value="1">Active</option>
<option value="0">Inactive</option>
</select>
  </div>' . "\n";
                        echo '<input type="submit" name="submit" class="btn btn-primary" value="Save">' . "\n";
                        echo '</form>' . "\n";
                        ?>
                    </div>
                </div>
            </div>
            <script src="js/jquery.min.js" type="text/javascript"></script>
            <script src="js/bootstrap.min.js" type="text/javascript"></script>        
            <script src="js/popper.min.js" type="text/javascript"></script>
            <script>
                $(function () {
                    $("#title").keyup(function () {

                        var value = $(this).val();
                        value = value.toLowerCase();

                        value = value.replace(/ /g, "-");
                        $("#link").val(value);
                    }).keyup();
                });
            </script>
        </body>
    </html>
    <?php
}
?>
