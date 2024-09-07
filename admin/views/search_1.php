<?php
$p = new Protect();

if (isset($_GET['cms']) && !empty($_GET['cms'])) {
	s = $p->secureStr($_GET['cms']);
}

if (isset($_GET['w']) && !empty($_GET['w'])) {
	= $p->secureStr($_GET['w']);
}

function protect($string) {
	otection = htmlspecialchars(trim($string), ENT_QUOTES);
	urn $protection;
}

$w = protect($_GET['w']);

$c = new MyCRUD();
?>
<style>
	gination {
		tyle-type: none;
		: 0 auto;
		g: 10px 0;
		y: inline-flex;
		y-content: space-between;
		zing: border-box;

	gination li {
		zing: border-box;
		g-right: 10px;

	gination li a {
		zing: border-box;
		ound-color: #e2e6e6;
		g: 8px;
		ecoration: none;
		ize: 12px;
		eight: bold;
		 #616872;
		-radius: 4px;

	gination li a:hover {
		ound-color: #d4dada;

	gination .next a, .pagination .prev a {
		ransform: uppercase;
		ize: 12px;

	gination .currentpage a {
		ound-color: #518acb;
		 #fff;

	gination .currentpage a:hover {
		ound-color: #518acb;

</style>
<?php
if ($w == "select") {

	($result = $c->selectData("SELECT * FROM table_config")) {
		_found = $result->num_rows;

		otal_found > 0) {
			sqli_fetch_assoc($result);
			es = explode(',', $row['table_name']);



	ript>
		tion () {
			ttb").change(function () {
				 = $(this).val();
				selecttb;
				e.replace("_", " ");
				ashboard.php?cms=search&w=find&tbl=' + selecttb;
				ext('Buscar en ' + value);
				ion.replace(url);


	cript>
	v class="container">
		lass="row pt-3">
			s="col-md-6">
				">Lista de tablas de aplicaci&oacute;n</h3>

			s="col-md-6">
				form-group">
					ecttb" name="selecttb" class="form-control">
						elecione una Tabla</option>

						es as $tname) {
							_", " ", $tname);
							' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";






	iv>
	hp
} elseif ($w == "find") {
	le = protect($_GET['tbl']);
	tl = ucfirst(str_replace("_", " ", $tble));
	lmns = $c->viewColumns($tble);
	ol = $c->getID($tble);

	v class="container">
		lass="row pt-3">
			s="col-md-3">
				n btn-secondary" href="dashboard.php?cms=search&w=select">Volver a seleccionar tabla</a>

			s="col-md-9">
				ext-primary">Buscar Datos en <?php echo $titl; ?></h2>


	iv>
	v class="container">
		include 'searchData.php'; ?>
		method="post">
			s="row">
				col-lg-12">
					">
						-2">
							do: </label>

						-3">
							="frase" name="frase" class="form-control search-slt" placeholder="Contenido o frase a search">

						-4">
							ontrol search-slt" id="columna" name="columna">
								umna</option>

								mn) {
									("_", " ", $colmn->name));
									 {


									olmn->name . '">' . $cnme . '</option>';




						-3">
							id="search" name="search"
								 wrn-btn" value="Enviar consulta">




		>
	iv>
	v class="container pt-3">
		lass="row">

			($_POST['search'])) {
				OST['frase'];
				_POST['columna'];
				tble, $columna, $frase);

				GET['id'])) {
					'];
					e, $ncol, $id);




	iv>
	hp
}
?>
