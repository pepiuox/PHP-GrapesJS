<?php
$mMenu = $conn->prepare("SELECT id, type_menu FROM type_menu where id = :menu");
$mMenu->execute([':menu' => $menu]);
$myMenu = $mMenu->fetch(PDO::FETCH_ASSOC);
// ✅ `type_menu` es un segmento de RUTA: se sanea (sin `/`, `\`, `..`) antes de incluirlo.
$menuTpl = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) ($myMenu['type_menu'] ?? ''));
if ($menuTpl !== '' && is_file(__DIR__ . '/require/' . $menuTpl . '.php')) {
    include 'require/' . $menuTpl . '.php';
}
?>
<!-- Home Template -->
<div class="container-fluid">
    <div class="row">  
        <div class="w-100">       
<?php
echo decodeContent($content) . "\n";
echo '<style>' . "\n";
echo decodeContent($style) . "\n";
echo '</style>' . "\n";
?>
<?php
$mBlocks = $conn->prepare("SELECT id, type_block, idB, blockID, active, pageId FROM type_blocks, blocks WHERE type_blocks.id=blocks.blockId  AND active='1' AND pageId = :bid");
$mBlocks->execute([':bid' => $bid]);
$numb = $mBlocks->rowCount();
if ($numb > 0) {
    while ($block = $mBlocks->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($bid)) {
?>
<div class="container myBlock">                    
            <?php // ✅ `type_block` se usa como segmento de RUTA (dos veces): se sanea igual.
$blockTpl = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) ($block['type_block'] ?? ''));
if ($blockTpl !== '' && is_file(dirname(__DIR__) . '/blocks/' . $blockTpl . '/' . $blockTpl . '.php')) {
    require_once dirname(__DIR__) . '/blocks/' . $blockTpl . '/' . $blockTpl . '.php';
} ?>                                      
</div>
            <?php
        }
    }
} else {
    echo "Agregue un componente";
}
            ?>        
        </div>      
    </div>
</div>
