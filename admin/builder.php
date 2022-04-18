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

        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <title><?php echo SITE_NAME; ?> | Builder</title>
                <!-- stylesheet -->
                <link href="<?php echo $base; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>                
                <!-- Font Awesome -->
                <link rel="stylesheet" href="<?php echo $base; ?>assets/plugins/font-awesome/css/font-awesome.min.css"  type="text/css"/>               
               <!--
                <link rel="stylesheet" href="<?php echo $base; ?>assets/plugins/fontawesome-free/css/all.min.css">
               -->
                <link rel="stylesheet" href="<?php echo $base; ?>assets/css/toastr.min.css">
                <link rel="stylesheet" href="<?php echo $base; ?>assets/plugins/grapesjs/css/grapes.min.css">
                <link href="<?php echo $base; ?>assets/css/editor.css" rel="stylesheet" type="text/css"/>
                <link rel="stylesheet" href="<?php echo $base; ?>assets/plugins/grapesjs/css/grapesjs-preset-webpage.min.css">
                <link href="<?php echo $base; ?>assets/css/grapesjs-component-code-editor.min.css" rel="stylesheet" type="text/css"/>
                <link rel="stylesheet" href="<?php echo $base; ?>assets/css/tooltip.css">
                <link rel="stylesheet" href="<?php echo $base; ?>assets/plugins/grapesjs/css/grapesjs-plugin-filestack.css">
                <link rel="stylesheet" href="<?php echo $base; ?>assets/css/demos.css">
                <link href="<?php echo $base; ?>assets/plugins/grapesjs/css/grapesjs-project-manager.min.css" rel="stylesheet">
                <!--  script  -->
                <script src="<?php echo $base; ?>assets/js/jquery.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>            
                <!--  <script src="<?php echo $base; ?>assets/js/backbone-min.js"></script> -->
                <script src="<?php echo $base; ?>assets/plugins/ckeditor/ckeditor.js"></script> 
                <script src="<?php echo $base; ?>assets/js/toastr.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapes.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-bootstrap-elements.js"></script>                               
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-preset-webpage.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-lory-slider.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-tabs.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-component-code-editor.min.js" type="text/javascript"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-custom-code.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-touch.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-parser-postcss.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-tooltip.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-tui-image-editor.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-navbar.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-blocks-bootstrap4.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-code-editor.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-plugin-ckeditor.min.js"></script>                            
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-script-editor.min.js" type="text/javascript"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-typed.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-uikit"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-page-break.min.js"></script>                
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-project-manager"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-ga"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-parser-postcss.min.js"></script>
                <script src="<?php echo $base; ?>assets/plugins/grapesjs/js/grapesjs-swiper-slider.min.js"></script>
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
                <div class="app-wrap">
                    <!-- Side-Nav -->
                    <div class="panel-wrp">
                        <div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar" >
                            <nav class="component">

                                <ul class="nav flex-column text-white w-100">
                                    <?php

                                    function getListcomponent($directory) {
                                        $results_array = array();

                                        if (is_dir($directory)) {
                                            if ($handle = opendir($directory)) {

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
                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php
                                        if (!empty(USERS_AVATARS)) {
                                            echo '<img src="<?php echo $base; ?>uploads/' . USERS_AVATARS . '" class="user-image img-circle elevation-2" alt="' . USERS_NAMES . '">';
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
                                                echo '<img src="<?php echo $base; ?>uploads/' . USERS_AVATARS . '" class="img-circle elevation-2" alt="' . USERS_NAMES . '">';
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
                                    </ul>
                                </div>

                            </div>
                        </nav>

                        <div id="gjs" class='box' ondragenter="return dragEnter(event)" ondrop="return dragDrop(event)" ondragover="return dragOver(event)">
                            <?php
                            echo decodeContent($pcontent) . "\n";
                            echo '<style>' . "\n";
                            echo decodeContent($pstyle) . "\n";
                            echo '</style>' . "\n";
                            ?>
                        </div>
                    </div>
                </div>
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
                              '<?php echo $base; ?>assets/plugins/bootstrap/css/bootstrap.min.css'
                          ],
                          scripts: [
                              '<?php echo $base; ?>assets/plugins/jquery/jquery.min.js',
                              '<?php echo $base; ?>assets/js/popper.min.js',
                              '<?php echo $base; ?>assets/plugins/bootstrap/js/bootstrap.min.js'
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

              pn.addButton('views', {
                  id: 'open-pages',
                  className: 'fa fa-file-o',
                  attributes: {
                      title: 'Take Screenshot'
                  },
                  command: 'open-pages',
                  togglable: false
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
                }
                doResize(null, starterData);
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
