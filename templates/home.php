<?php
$mMenu = $conn->query("SELECT id, type_menu FROM type_menu where id='$menu'");
$myMenu = $mMenu->fetch_array();
if (!empty($myMenu['type_menu'])) {
	include 'require/' . $myMenu['type_menu'] . '.php';
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
			$mBlocks = $conn->query("SELECT id, type_block, idB, blockID, active, pageId FROM type_blocks, blocks WHERE type_blocks.id=blocks.blockId  AND active='1' AND pageId ='$bid'");
			$numb = $mBlocks->num_rows;
			if ($numb > 0) {
				while ($block = $mBlocks->fetch_array()) {
					if (!empty($bid)) {
						?>
						<div class="container myBlock">
							<?php require_once 'blocks/' . $block['type_block'] . '/' . $block['type_block'] . '.php'; ?>
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
