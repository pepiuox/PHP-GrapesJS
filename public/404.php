<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION)) {
    session_start();
}
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
    $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
$url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$escaped_url = htmlspecialchars($url, ENT_QUOTES, "UTF-8");
$url_path = parse_url($escaped_url, PHP_URL_PATH);
$basename = pathinfo($url_path, PATHINFO_BASENAME);

require_once '../elements/top.php';
?>
</head>
<body>
<div id="wrapper"> 
         <?php
         require_once '../elements/menu.php';
         ?>

   <div class="login-box">
    <div class="login-logo">
            <div class="card text-center">
                <div class="card-body login-card-body">
                    <h4 class="card-title"> This page not exist</h4>
                    <p class="card-text">You will be directed to the main page.</p>
                </div>
            </div>
        </div>
    </div>
 </div>
<script>
setTimeout(function(){ 
window.location.href = "<?php echo SITE_PATH; ?>"; 
}, 10 * 1000);
</script>
    
</body>
</html>
