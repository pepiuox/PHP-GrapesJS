<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION)) {
    session_start();
}
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
$baseweb = $protocol . $_SERVER["HTTP_HOST"] . "/";
?>

    <!-- Section CSS -->
    <!-- jQuery UI (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?php echo $baseweb; ?>assets/plugins/jquery-ui/jquery-ui.css">
    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?php echo $baseweb; ?>core/filemanager/css/elfinder.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $baseweb; ?>core/filemanager/css/theme.css">
    <!-- Section JavaScript -->
    <!-- jQuery and jQuery UI (REQUIRED) -->
    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]>
    <!-->
    <script src="<?php echo $baseweb; ?>assets/plugins/jquery/jquery.min.js"></script>
    <!--
    <![endif]-->
    <script src="<?php echo $baseweb; ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- elFinder JS (REQUIRED) -->
    <script src="<?php echo $baseweb; ?>core/filemanager/js/elfinder.min.js"></script>
    <!-- Extra contents editors (OPTIONAL) -->
    <script src="<?php echo $baseweb; ?>core/filemanager/js/extras/editors.default.min.js"></script>
    <!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
    <!--<script src="js/extras/quicklook.googledocs.js"></script>-->
    <!-- elFinder initialization (REQUIRED) -->   
    <script type="text/javascript" charset="utf-8">
        
      // Documentation for client options:
      // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
      $(document).ready(function() {
          function getUrlParam(paramName) {
                var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i');
                var match = window.location.search.match(reParam);
                return (match && match.length > 1) ? match[1] : '';
            }
        $('#elfinder').elfinder(
          // 1st Arg - options
          {
            cssAutoLoad: false, // Disable CSS auto loading
            baseUrl: './', // Base URL to css/*, js/*
            url: '<?php echo $baseweb; ?>core/filemanager/php/connector.minimal.php', // connector URL (REQUIRED)
            // , lang: 'ru'                    // language (OPTIONAL)
            resizable: false,
                onlyMimes: ["image"],
                uiOptions: {
                    // toolbar configuration
                    toolbar: [
                        ["reload"],
                        ['upload'],
                        ["open", "download", "getfile"],
                        ["duplicate", "rename", "edit", "resize"],
                        ["quicklook", "info"],
                        ["view", "sort"]
                    ]
                },
                getfile: {
                    onlyURL: true,
                    multiple: false,
                    folders: false,
                    oncomplete: ""
                }
          },
          // 2nd Arg - before boot up function
          function(fm, extraObj) {
            // `init` event callback function
            fm.bind('init', function() {
              // Optional for Japanese decoder "encoding-japanese.js"
              if (fm.lang === 'ja') {
                fm.loadScript(
                  ['<?php echo $baseweb; ?>assets/js/encoding.min.js'],
                  function() {
                    if (window.Encoding && Encoding.convert) {
                      fm.registRawStringDecoder(function(s) {
                        return Encoding.convert(s, {
                          to: 'UNICODE',
                          type: 'string'
                        });
                      });
                    }
                  }, {
                    loadType: 'tag'
                  });
              }
            });
            // Optional for set document.title dynamically.
            var title = document.title;
            fm.bind('open', function() {
              var path = '',
                cwd = fm.cwd();
              if (cwd) {
                path = fm.path(cwd.hash) || null;
              }
              document.title = path ? path + ':' + title : title;
            }).bind('destroy', function() {
              document.title = title;
            });
          });
      });
    </script>
  
    <!-- Element where elFinder will be created (REQUIRED) -->
    <div id="elfinder"></div>
    



    
