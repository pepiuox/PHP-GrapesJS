<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
			<a href="<?php echo SITE_PATH; ?>index.php" class="nav-link">Home</a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
			<a href="<?php echo SITE_PATH; ?>members/profile.php?user=contacts" class="nav-link">Contact</a>
		</li>
	</ul>

	<!-- SEARCH FORM -->
	<form class="form-inline ml-3">
		<div class="input-group input-group-sm">
			<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
			<div class="input-group-append">
				<button class="btn btn-navbar" type="submit">
					<i class="fas fa-search"></i>
				</button>
			</div>
		</div>
	</form>

	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">

		<li class="nav-item dropdown user-menu">
			<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
				<?php
				if (!empty(USERS_AVATARS)) {
					echo '<img src="<?php echo SITE_PATH; ?>uploads/' . USERS_AVATARS . '" class="user-image img-circle elevation-2" alt="' . USERS_NAMES . '">';
				}
				?>
				<i class="far fa-user"></i>
				<span class="d-none d-md-inline"><?php echo USERS_FULLNAMES; ?></span>

			</a>
			<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
				<!-- User image -->
				<li class="user-header bg-primary">
					<?php
					if (!empty(USERS_AVATARS)) {
						echo '<img src="<?php echo SITE_PATH; ?>uploads/' . USERS_AVATARS . '" class="img-circle elevation-2" alt="' . USERS_NAMES . '">';
					}
					?>
					<p>
						<?php echo USERS_NAMES . ' - ' . USERS_SKILLS; ?>
						<small>Member since Nov. 2012</small>
					</p>
				</li>
				<!-- Menu Body -->
				<li class="user-body">
					<div class="row">
						<div class="col-4 text-center">
							<a href="#">Followers</a>
						</div>
						<div class="col-4 text-center">
							<a href="#">Sales</a>
						</div>
						<div class="col-4 text-center">
							<a href="#">Friends</a>
						</div>
					</div>
					<!-- /.row -->
				</li>
				<!-- Menu Footer-->
				<li class="user-footer">
					<form method="post">
						<button class="btn btn-default btn-flat" type="submit" name="profile">Profile</button>
						<button class="btn btn-default btn-flat float-right" type="submit" name="logout">
							Sign out
						</button>
					</form>

				</li>
			</ul>
		</li>
	</ul>
</nav>
