<!-- Right Sidebar Template -->
<?php
$mMenu = $conn->prepare("SELECT id, type_menu FROM type_menu where id = :men");
$mMenu->execute([':men' => $men]);
$myMenu = $mMenu->fetch(PDO::FETCH_ASSOC);
// ✅ `type_menu` es un segmento de RUTA: se sanea (sin `/`, `\`, `..`) antes de incluirlo.
$menuTpl = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) ($myMenu['type_menu'] ?? ''));
if ($menuTpl !== '' && is_file(__DIR__ . '/require/' . $menuTpl . '.php')) {
    include 'require/' . $menuTpl . '.php';
}
?>
<div class="container-fluid">
    <div class="row">    

        <div class="col-md-9">           
<?php
$mBlocks = $conn->prepare("SELECT id, type_block,idB, blockID, active, pageId FROM type_blocks, blocks WHERE type_blocks.id=blocks.blockId  AND active='1' AND pageId = :bid");
$mBlocks->execute([':bid' => $bid]);
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
        ?>        
        </div>      
        <div class="col-md-3"> 
            <!-- Content Right Sidebar --> 
        </div> 
    </div>
</div>
