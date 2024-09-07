<?php
$p = new Protect();
if (isset($_GET['w']) && !empty($_GET['w'])) {
	$w = $p->secureStr($_GET['w']);
} else {
	?>
	<meta http-equiv="Refresh" content="0; url='dashboard.php?cms=search&w=select'" />
	<?php
}

$c = new MyCRUD();
?>
<style>
	.pagination {
		list-style-type: none;
		margin: 0 auto;
		padding: 10px 0;
		display: inline-flex;
		justify-content: space-between;
		box-sizing: border-box;
	}
	.pagination li {
		box-sizing: border-box;
		padding-right: 10px;
	}
	.pagination li a {
		box-sizing: border-box;
		background-color: #e2e6e6;
		padding: 8px;
		text-decoration: none;
		font-size: 12px;
		font-weight: bold;
		color: #616872;
		border-radius: 4px;
	}
	.pagination li a:hover {
		background-color: #d4dada;
	}
	.pagination .next a, .pagination .prev a {
		text-transform: uppercase;
		font-size: 12px;
	}
	.pagination .currentpage a {
		background-color: #518acb;
		color: #fff;
	}
	.pagination .currentpage a:hover {
		background-color: #518acb;
	}
</style>
<?php
if ($w == "select") {

	if ($result = $c->selectData("SELECT * FROM table_config")) {
		$total_found = $result->num_rows;

		if ($total_found > 0) {
			$row = $result->fetch_assoc();
			$tableNames = explode(',', $row['table_name']);
		}
	}
	?>

	</script>
	<div class="container">
		<div class="row pt-3">
			<div class="col-md-6">
				<h3 id="fttl">Select Table</h3>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<select id="selecttb" name="selecttb" class="form-control">
						<option value="">Select Table</option>
						<?php
						if (!empty($tableNames)) {
							foreach ($tableNames as $tname) {
								$remp = str_replace("_", " ", $tname);
								echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
							}
						}
						?>
					</select>
					<script>
						let select = document.querySelector('#selecttb');
						select.addEventListener('change', function () {
							let url = 'dashboard.php?cms=search&w=find&tbl=' + this.value;
							window.location.replace(url);
						});
					</script>
				</div>
			</div>
		</div>
	</div>
	<?php
} elseif ($w == "find") {
	$tble = protect($_GET['tbl']);
	$titl = ucfirst(str_replace("_", " ", $tble));
	$colmns = $c->viewColumns($tble);
	$ncol = $c->getID($tble);
	?>
	<div class="container">
		<div class="row pt-3">
			<div class="col-md-3">
				<a class="btn btn-secondary" href="search.php?w=select">Select another table</a>
			</div>
			<div class="col-md-9">
				<h2 class="text-primary">Search Data in <?php echo $titl; ?></h2>
			</div>
		</div>
	</div>
	<div class="container">
		<?php include_once 'searchData.php'; ?>
		<form method="post">
			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-md-2">
							<label>Find content: </label>
						</div>
						<div class="col-md-3">
							<input type="text" id="frase" name="frase"
								   class="form-control search-slt"
								   placeholder="Contenido o frase a search">
						</div>
						<div class="col-md-4">
							<select class="form-control search-slt" id="columna"
									name="columna">
								<option>Search in the column</option>
								<?php
								foreach ($colmns as $colmn) {
									$cnme = ucfirst(str_replace("_", " ", $colmn->name));
									if ($colmn->name === $ncol) {
										continue;
									}
									echo '<option value="' . $colmn->name . '">' . $cnme . '</option>';
								}
								?>
							</select>
						</div>
						<div class="col-md-3">
							<input type="submit" id="search" name="search" class="btn btn-danger wrn-btn" value="Enviar consulta">
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="container-fluid pt-3">
		<div class="row">
			<?php
			if (isset($_POST['search'])) {
				$frase = $_POST['frase'];
				$columna = $_POST['columna'];
				searchData($tble, $columna, $frase);
			} else {
				if (isset($_GET['id'])) {
					$id = $_GET['id'];
					searchData($tble, $ncol, $id);
				}
			}
			?>
		</div>
	</div>
	<?php
}
?>
