<!-- Multimedia Template -->
<?php
$mMenuStmt = $conn->prepare("SELECT id, type_menu FROM type_menu where id=?");
$mMenuStmt->bind_param("s", $men);
$mMenuStmt->execute();
$myMenu = $mMenuStmt->get_result()->fetch_array();
if (!empty($myMenu['type_menu'])) {
    include 'require/' . $myMenu['type_menu'] . '.php';
}
?>
<div class="container-fluid">
    <div class="row">    
        <div class="w-100">           
<?php
$mBlocksStmt = $conn->prepare("SELECT id, type_block,idB, blockID, active, pageId FROM type_blocks, blocks WHERE type_blocks.id=blocks.blockId  AND active='1' AND pageId =?");
$mBlocksStmt->bind_param("s", $bid);
$mBlocksStmt->execute();
$mBlocks = $mBlocksStmt->get_result();
while ($block = $mBlocks->fetch_array()) {
    if (!empty($bid)) {
?>
<div class="container myBlock">                    
        <?php require_once 'blocks/' . $block['type_block'] . '/' . $block['type_block'] . '.php'; ?>                                      
</div>
        <?php
    }
}
        ?>        
        </div>      

    </div>
</div>
