<?php
session_start();
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
	uire_once '../config/dbconnection.php';
	uire_once 'Autoload.php';
	gin = new UserClass();
	eck = new CheckValidUser();
	vel = new AccessLevel();
} else {
	der('Location: ../installer/install.php');
	t();
}
if (isset($_GET['tbl']) && isset($_GET['id'])) {
	l = $_GET['tbl'];
	 = $_GET['id'];
	g = $conn->prepare("SELECT * FROM $tbl WHERE id=?");
	g->bind_param("i", $id);
	g->execute();
	 = $spg->get_result();
	 = $rs->num_rows;
	($nm > 0) {
		 $rs->fetch_assoc();
		 $row['id'];
		 = $row['title'];
		 = $row['link'];
		rd = $row['keyword'];
		ification = $row['classification'];
		iption = $row['description'];
		nt = $row['content'];
		 = $row['style'];


		ype html>
		lang="en">

				t="utf-8"/>
				quiv="X-UA-Compatible" content="IE=edge"/>
				viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
				mpty($description)) { ?>
					cription" content="<?php echo $description; ?>" />
				 { ?>
					cription" content="<?php echo SITE_DESCRIPTION; ?>" />


				keyword)) {

					words" content="<?php echo $keyword; ?>" />
				 { ?>
					words" content="<?php echo SITE_KEYWORDS; ?>" />


				classification)) {

					ssification" content="<?php echo $classification; ?>" />
				 { ?>
					ssification" content="<?php echo SITE_CLASSIFICATION; ?>" />

				 echo $title; ?></title>
				<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
				tylesheet" type="text/css" href="<?php echo SITE_PATH; ?>assets/css/font-awesome.min.css" />

					ilter = function (html) {



				"text/css">
					 >.dropdown-menu{
						portant;


					nu:hover > .dropdown-menu{
						portant;





						/* 13px */


					e::after{
						ont-md);
						x;


					li a.active{



					arrow{





					-all .dropdown-menu, .dropdown-hover>.dropdown-menu.dropend {
						!important


					li {
						;


					.dropdown-submenu {

						;




					.dropdown-submenu-left {




					> li:hover > .dropdown-submenu {



					:hover>.dropdown-menu {
						ock;


					>.dropdown-toggle:active {
						icking will make it sticky*/
						ne;

					 desktop view ============ */
					(min-width: 992px) {




						opdown-submenu{





						opdown-submenu-left{




						i:hover{
							f1f1

						i:hover > .dropdown-submenu{



					 desktop view .end// ============ */

					 small devices ============ */
					th: 991px) {

						opdown-submenu{






					 small devices .end// ============ */


				"http://localhost:130/assets/js/menu.js" type="text/javascript"></script>


		ecodeContent($style) . "\n";





				 '../elements/menu.php';

				container">

					ent($content) . "\n";


				"<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
				"<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
				"<?php echo SITE_PATH; ?>assets/js/popper.min.js" type="text/javascript"></script>

		>

	lse {
		('Location: dashboard.php?cms=list_pages');
		;

} else {
	der('Location: dashboard.php?cms=list_pages');
	t();
}
?>
