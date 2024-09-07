<?php
$dbprd = \Database::connection('store');
include 'actions/Addcart.php';
include 'actions/Rating.php';
include 'actions/CheckSession.php';
$product = new AddCart($dbprd);
$rating = New Rating($dbprd);
$check = New CheckSession($dbprd);

if (isset($_GET['product'])) {
	$id = $_GET['product'];
	if (isset($_SESSION['client_session']) && !empty($_SESSION['client_session'])) {
		// add items
		$cant = 1;
		$secl = $_SESSION['client_session'];
		// check if client session exists
		if (isset($_SESSION['client_id']) && !empty($_SESSION['client_id'])) {
			$client = $_SESSION['client_id'];
			$cartses = $dbprd->prepare("INSERT INTO canasta_articulos SET session_key= :session_key, producto_id= :producto_id, cantidad= :cantidad, cliente_id= :cliente_id");

			$secl = htmlspecialchars(strip_tags($secl));
			$id = htmlspecialchars(strip_tags($id));
			$cant = htmlspecialchars(strip_tags($cant));
			$client = htmlspecialchars(strip_tags($client));

			$cartses->bindParam(":session_key", $secl);
			$cartses->bindParam(":producto_id", $id);
			$cartses->bindParam(":cantidad", $cant);
			$cartses->bindParam(":cliente_id", $client);

			$cartses->execute();
			header('Location: products.php');
		} else {
			// check if session exists
			$cartses = $dbprd->prepare("INSERT INTO canasta_articulos SET session_key= :session_key, producto_id= :producto_id, cantidad= :cantidad");

			$secl = htmlspecialchars(strip_tags($secl));
			$id = htmlspecialchars(strip_tags($id));
			$cant = htmlspecialchars(strip_tags($cant));

			$cartses->bindParam(":session_key", $secl);
			$cartses->bindParam(":producto_id", $id);
			$cartses->bindParam(":cantidad", $cant);

			$cartses->execute();
			header('Location: products.php');
		}
	}
}

if (isset($_GET['heart'])) {
	$heart = $_GET['heart'];
	$rating->ratingFavoritos('producto', $heart);
}

if (isset($_POST['viewcart'])) {
	if (isset($_SESSION['client_session']) && !empty($_SESSION['client_session'])) {
		if ($product->exists()) {
			$product->cantidad = $cant = $_POST['cantidad'];
			$product->update();
		}
	}
}

function text_echo($x, $length) {
	if (strlen($x) <= $length) {
		echo $x;
	} else {
		$y = substr($x, 0, $length) . '...';
		echo $y;
	}
}

if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}
$visible = 'Disponible';
$limit = 20;
$offset = ($page - 1) * $limit;

$presult = $dbprd->query("SELECT * FROM productos WHERE visible='Disponible' LIMIT $offset, $limit");
$nums = $presult->rowCount();
$prow = $presult->fetchAll();
?>
<div class="sidebarCart">
	<div class="scrollingCart">
		<?php
		echo $product->itemsCart();
		?>
	</div>
</div>
<div class="container py-4">
	<div class="row">
		<?php
		if ($nums > 0) {
			foreach ($prow as $kp) {
				?>
				<!-- Full-width images with number text -->
				<div class="col-md-3 mpro pt-2">
					<?php
					if ($kp['estado'] === 'Agotado') {
						?>
						<img class="estado"src="<?= PATH_IMG . 'agotado.png'; ?>">
						<?php
					} elseif ($kp['estado'] === 'Pronto') {
						?>
						<img class="estado"src="<?= PATH_IMG . 'pronto.png'; ?>">
						<?php
					}
					?>
					<div class="card">
						<a href="<?= PATH_APP . 'products/product?id=' . $kp['idPrd'] ?>">
							<img class="card-img-top" src="<?= PATH_IMG . $kp['imagen_producto']; ?>" alt="<?= $kp['producto']; ?>">
						</a>
						<div class="card-body" >
							<p class="card-title">
								<a href="<?= PATH_APP . 'products/product?id=' . $kp['idPrd'] ?>">
									<?= text_echo($kp['producto'], 56) ?>
								</a>
							</p>
							<div class="row">
								<div class="col"><strong>S/. <?= $kp['precio']; ?></strong></div>
								<div class="col text-right">
									<?php
									$rating->prodicto_id = $kp['idPrd'];
									if ($rating->checkProduct()) {
										echo '<a class="bg-outline-heart"><i class="fas fa-heart"></i></a>';
									} else {
										echo '<a href="' . PATH_APP . 'products?heart=' . $kp['idPrd'] . '" class="bg-outline-heart" name="heartlike"><i class="far fa-heart"></i></a>';
									}
									?>
									<?php
									$product->session_key = $_SESSION['client_session'];
									$product->producto_id = $kp['idPrd'];
									if ($product->exists()) {
										?>
										<a href="<?= PATH_APP; ?>cart"><span class="text-addcart"><i class="fas fa-cart-plus"></i></span></a>
										<?php
									}
									?>
								</div>
								<div class="w-100 text-center" style='color:#ffb300;'>
									<?php
									// Rating products
									$rresult = $rating->getRatingProduct();
									$rting = $rresult->rowCount();
									$rrow = $rresult->fetch(PDO::FETCH_ASSOC);
									?>
									<div class="w-100 mb-1" style='color:#ffb300;'>
										<?php
										if ($rting > 0) {

											if ($rrow['rating'] >= 100) {
												echo '<i class="fas fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
											} elseif ($rrow['rating'] >= 200) {
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
											} elseif ($rrow['rating'] >= 300) {
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
											} elseif ($rrow['rating'] >= 400) {
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="far fa-star"></i>';
											} elseif ($rrow['rating'] >= 500) {
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
												echo '<i class="fas fa-star"></i>';
											} else {
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
												echo '<i class="far fa-star"></i>';
											}
										} else {
											echo '<i class="far fa-star"></i>';
											echo '<i class="far fa-star"></i>';
											echo '<i class="far fa-star"></i>';
											echo '<i class="far fa-star"></i>';
											echo '<i class="far fa-star"></i>';
										}
										?>
									</div>
								</div>
								<div class="w-100 text-center">

									<?php
									if ($product->exists()) {
										echo '<a href="' . PATH_APP . 'cart" class="btn btn-updatecart" id="viewcart" name="viewcart">Ver carrito</a> ';
									} else {
										echo '<a href="' . PATH_APP . 'products?product=' . $kp['idPrd'] . '" class="btn btn-addcart" id="addcart" name="addcart">Agregar al carrito</a> ';
									}
									?>

								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
	<div class="row">
		<div class='w-100 nav-scroller py-1 mb-2'>
			<nav aria-label="Page navigation" class="navbar-toggleable-md table-responsive">
				<?php
				if (isset($_GET['page']) && $_GET['page'] != "") {
					$page = $_GET['page'];
				} else {
					$page = 1;
				}
				$query = "SELECT * FROM productos WHERE visible='Disponible'";
				$rs_result = $dbprd->query($query);
				$row = $rs_result->fetchAll();
				$total_records = count($row);

				$previous_page = $page - 1;
				$next_page = $page + 1;
				$adjacents = "2";
				$range = 10;
				$total_no_of_pages = ceil($total_records / $limit);
				$second_last = $total_no_of_pages - 1;
				?>
				<ul class="pagination justify-content-center">
					<?php
					if ($page > 1) {
						echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=1'>First Page</a></li>";
					}
					?>

					<li <?php
					if ($page <= 1) {
						echo "class='disabled page-item'";
					} else {
						echo "class='page-item'";
					}
					?>>
						<a class='page-link' <?php
						if ($page > 1) {
							echo "href='" . PATH_APP . "?page=$previous_page'";
						}
						?>>Previous</a>
					</li>
					<?php
					if ($total_no_of_pages <= $range) {
						$showp = $total_no_of_pages;
					} else {
						$showp = $range;
					}

					if ($page <= 5) {
						for ($counter = 1; $counter <= $showp; $counter++) {
							if ($counter == $page) {
								echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
							} else {
								echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=$counter'>$counter</a></li>";
							}
						}
						if ($total_no_of_pages >= $range) {
							echo "<li class='page-item'><a class='page-link'>...</a></li>";
							echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=$second_last'>$second_last</a></li>";
							echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=$total_no_of_pages'>$total_no_of_pages</a></li>";
						}
					} elseif ($page > 5 && $page < $total_no_of_pages - 5) {
						if ($total_no_of_pages >= $range) {
							echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=1'>1</a></li>";
							echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=2'>2</a></li>";
							echo "<li class='page-item'><a class='page-link'>...</a></li>";
							for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
								if ($counter == $page) {
									echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
								} else {
									echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=$counter'>$counter</a></li>";
								}
							}
							echo "<li class='page-item'><a class='page-link'>...</a></li>";
							echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=$second_last'>$second_last</a></li>";
							echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=$total_no_of_pages'>$total_no_of_pages</a></li>";
						}
					} else {
						if ($total_no_of_pages >= $range) {
							echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=1'>1</a></li>";
							echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=2'>2</a></li>";
							echo "<li class='page-item'><a class='page-link'>...</a></li>";
							for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
								if ($counter == $page) {
									echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
								} else {
									echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=$counter'>$counter</a></li>";
								}
							}
						}
					}
					?>
					<li <?php
					if ($page >= $total_no_of_pages) {
						echo "class='disabled page-item'";
					} else {
						echo "class='page-item'";
					}
					?>>
						<a class='page-link' <?php
						if ($page < $total_no_of_pages) {
							echo "href='" . PATH_APP . "?page=$next_page'";
						}
						?>>Next</a>
					</li>

					<?php
					if ($page < $total_no_of_pages) {
						echo "<li class='page-item'><a class='page-link' href='" . PATH_APP . "?page=$total_no_of_pages'>Last</a></li>";
					}
					?>
				</ul>
			</nav>
		</div>
	</div>
</div>
<?php $this->inc('elements/footer.php'); ?>
