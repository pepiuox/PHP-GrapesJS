<!-- Two Sidebar Template -->
<?php
$mMenu = $conn->query("SELECT id, type_menu FROM type_menu where id='$men'");
$myMenu = $mMenu->fetch_array();
if (!empty($myMenu['type_menu'])) {
	include 'require/' . $myMenu['type_menu'] . '.php';
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
			<!-- Content left Sidebar -->
		</div>
		<div class="col-md-6">
			<?php
			$mBlocks = $conn->query("SELECT id, type_block,idB, blockID, active, pageId FROM type_blocks, blocks WHERE type_blocks.id=blocks.blockId  AND active='1' AND pageId ='$bid'");
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
		<div class="col-md-3">
			<!-- Content Right Sidebar -->
		</div>
	</div>
</div>
