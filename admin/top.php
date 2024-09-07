<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" name="viewport"
			  content="width-device=width, initial-scale=1" />
		<title>Admin CRUD</title>
		<link rel="stylesheet" href="<?php echo SITE_PATH; ?>/css/theme.css">
		<link rel="stylesheet" href="<?php echo SITE_PATH; ?>/css/fonts.css">
		<link rel="stylesheet"
			  href="<?php echo SITE_PATH; ?>/css/font-awesome.min.css">
		<!-- Custom .css -->
		<link rel="stylesheet" href="<?php echo SITE_PATH; ?>/css/custom/custom.css">
		<!-- Custom font -->
		<link href="https://fonts.googleapis.com/css?family=Quicksand"
			  rel="stylesheet">
		<link href="<?php echo SITE_PATH; ?>/css/bootstrap-datepicker.min.css"
			  rel="stylesheet" type="text/css" />
		<link href="<?php echo SITE_PATH; ?>/css/jquery-ui.min.css" rel="stylesheet"
			  type="text/css" />
		<script type="text/javascript"
		src="<?php echo SITE_PATH; ?>/js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript"
		src="<?php echo SITE_PATH; ?>/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo SITE_PATH; ?>/js/typeahead.js"></script>
		<script type="text/javascript" src="<?php echo SITE_PATH; ?>/js/vue.js"></script>
		<script type="text/javascript" src="<?php echo SITE_PATH; ?>/js/axios.js"></script>
		<script type="text/javascript"
		src="<?php echo SITE_PATH; ?>/js/popper.min.js"></script>
		<script type="text/javascript"
		src="<?php echo SITE_PATH; ?>/js/bootstrap.min.js"></script>
		<script src="<?php echo SITE_PATH; ?>/js/bootstrap-datepicker.min.js"
		type="text/javascript"></script>
		<script src="<?php echo SITE_PATH; ?>/js/tinymce/tinymce.min.js"></script>
		<script>tinymce.init({selector: 'textarea'});</script>
		<script>
			$('#imagen').on('change', function () {
				//get the file name
				var fileName = $(this).val();
				//replace the "Choose a file" label
				$(this).next('.custom-file-label').html(fileName);
			})
		</script>
		<style type="text/css">

			/* ============ desktop view ============ */
			@media all and (min-width: 992px) {

				.dropdown-menu li{
					position: relative;
				}
				.dropdown-menu .submenu{
					display: none;
					position: absolute;
					left:100%;
					top:-7px;
				}
				.dropdown-menu .submenu-left{
					right:100%;
					left:auto;
				}

				.dropdown-menu > li:hover{
					background-color: #f1f1f1
				}
				.dropdown-menu > li:hover > .submenu{
					display: block;
				}
			}
			/* ============ desktop view .end// ============ */

			/* ============ small devices ============ */
			@media (max-width: 991px) {

				.dropdown-menu .dropdown-menu{
					margin-left:0.7rem;
					margin-right:0.7rem;
					margin-bottom: .5rem;
				}

			}
			/* ============ small devices .end// ============ */

		</style>
		<script type="text/javascript">
		//	window.addEventListener("resize", function() {
		//		"use strict"; window.location.reload();
		//	});


			document.addEventListener("DOMContentLoaded", function () {


				/////// Prevent closing from click inside dropdown
				document.querySelectorAll('.dropdown-menu').forEach(function (element) {
					element.addEventListener('click', function (e) {
						e.stopPropagation();
					});
				})



				// make it as accordion for smaller screens
				if (window.innerWidth < 992) {

					// close all inner dropdowns when parent is closed
					document.querySelectorAll('.navbar .dropdown').forEach(function (everydropdown) {
						everydropdown.addEventListener('hidden.bs.dropdown', function () {
							// after dropdown is hidden, then find all submenus
							this.querySelectorAll('.submenu').forEach(function (everysubmenu) {
								// hide every submenu as well
								everysubmenu.style.display = 'none';
							});
						})
					});

					document.querySelectorAll('.dropdown-menu a').forEach(function (element) {
						element.addEventListener('click', function (e) {

							let nextEl = this.nextElementSibling;
							if (nextEl && nextEl.classList.contains('submenu')) {
								// prevent opening link if link needs to open dropdown
								e.preventDefault();
								console.log(nextEl);
								if (nextEl.style.display == 'block') {
									nextEl.style.display = 'none';
								} else {
									nextEl.style.display = 'block';
								}

							}
						});
					})
				}
				// end if innerWidth

			});
			// DOMContentLoaded  end
		</script>
