<?php

$sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$rs = $conn->query("select * from pages");
$total = $rs->num_rows;
while ($fila = $rs->fetch_object()) {
    $sitemap .= '<url>
    <loc>' . $fila->ruta;
    $sitemap .= '</loc>
   <lastmod>' . $fila->created . '</lastmod>
   <changefreg>' . $fila->updated . ' </changefreg>
   <priority>0.7</priority>
   </url> ';
}
$sitemap .= '</urlset> ';

$path = SITE_PATH."sitemap.xml";
$modo = "w+";

if ($fp = fopen($path, $modo)) {
    fwrite($fp, $sitemap);
    echo "<p><b>Sitemap file created successfully</b></p>";
} else {
    echo "<p><b>There was a problem and the file was not created correctly.</b></p>";
}
?>
