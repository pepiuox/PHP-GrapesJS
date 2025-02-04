<?php
$cachefile = 'cache/' . basename($_SERVER['PHP_SELF']) . '.cache'; // e.g. cache/index.php.cache
$cachetime = 3600; // time to cache in seconds

if (file_exists($cachefile) && time() - $cachetime <= filemtime($cachefile)) {
  $c = @file_get_contents($cachefile);
  echo $c;
  exit;
} else {
  unlink($cachefile);
}

ob_start();
?>