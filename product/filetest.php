<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			.dropbtn {
				background-color: #3498DB;
				color: white;
				padding: 16px;
				font-size: 16px;
				border: none;
				cursor: pointer;
			}

			.dropbtn:hover, .dropbtn:focus {
				background-color: #2980B9;
			}

			.dropdown {
				position: relative;
				display: inline-block;
			}

			.dropdown-content {
				display: none;
				position: absolute;
				background-color: #f1f1f1;
				min-width: 160px;
				overflow: auto;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				z-index: 1;
			}

			.dropdown-content a {
				color: black;
				padding: 12px 16px;
				text-decoration: none;
				display: block;
			}

			.dropdown a:hover {
				background-color: #ddd;
			}

			.show {
				display: block;
			}
		</style>
	</head>
	<body>
		<h2>Clickable Dropdown</h2>
		<p>Click on the button to open the dropdown menu.</p>
		<div class="dropdown">
			<button onmouseover="myFunction()" class="dropbtn">Dropdown</button>
			<div id="myDropdown" class="dropdown-content">
				<a href="#home">Home</a>
				<a href="#about">About</a>
				<a href="#contact">Contact</a>
			</div>
		</div>
		<script>
			/* When the user clicks on the button,
			 toggle between hiding and showing the dropdown content */
			function myFunction() {
				document.getElementById("myDropdown").classList.toggle("show");
			}
			;

		// Close the dropdown if the user clicks outside of it
			window.onmouseover = function (event) {
				if (!event.target.matches('.dropbtn')) {
					var dropdowns = document.getElementsByClassName("dropdown-content");
					dropdowns.style.display = 'block';
					var i;
					for (i = 0; i < dropdowns.length; i++) {
						var openDropdown = dropdowns[i];
						if (openDropdown.classList.contains('show')) {
							openDropdown.classList.remove('show');
						}
					}
				}
			};
		</script>
	</body>
</html>
