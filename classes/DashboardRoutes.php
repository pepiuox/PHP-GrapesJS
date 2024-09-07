<?php

class DashboardRoutes {

	public function views_ary($key, $ret){
		$dashboard_routes = [
			'list_posts' 			=> ['t' => 'List Posts', 'u' 			=> 'views/blog_posts.php'],
			'add_post' 				=> ['t' => 'Add Post', 'u' 				=> 'views/blog_posts.php'],
			'edit_post' 			=> ['t' => 'Edit Post', 'u' 			=> 'views/blog_posts.php'],
			'delete_post' 		=> ['t' => 'Delete Post', 'u' 		=> 'views/blog_posts.php'],
			'post_category' 	=> ['t' => 'Post Categories', 'u' => 'views/category.php'],
			'list_pages' 			=> ['t' => 'Page List', 'u' 			=> 'views/pages.php'],
			'add_page' 				=> ['t' => 'Add Page', 'u' 				=> 'views/pages.php'],
			'edit_page' 			=> ['t' => 'Edit Page', 'u' 			=> 'views/pages.php'],
			'delete_page' 		=> ['t' => 'Delete Page', 'u' 		=> 'views/pages.php'],
			'siteconf' 				=> ['t' => 'Site Definitions', 'u' => 'views/settings.php'],
			'themes' 					=> ['t' => 'Themes', 'u' 					=> 'views/themes.php'],
			'theme_template' 	=> ['t' => 'Theme Template', 'u'	=> 'views/theme_template.php'],
			'menu' 						=> ['t' => 'Menu Template', 'u' 	=> 'views/menu.php'],
			'plugins' 				=> ['t' => 'Plugins App', 'u' 		=> 'views/plugins.php'],
			'users' 					=> ['t' => 'Users', 'u' 					=> 'views/admin.php'],
			'adduser' 				=> ['t' => 'Add User', 'u' 				=> 'views/adduser.php'],
			'table_crud' 			=> ['t' => 'Table CRUD', 'u' 			=> 'views/table_crud.php'],
			'column_manager' 	=> ['t' => 'Column Manager', 'u'	=> 'views/column_manager.php'],
			'table_config' 		=> ['t' => 'Table Config', 'u' 		=> 'views/table_config.php'],
			'table_manager' 	=> ['t' => 'Table Manager', 'u' 	=> 'views/table_manager.php'],
			'volunteer' 			=> ['t' => 'Volunteer', 'u' 			=> 'views/volunteer.php'],
			'search'					=> ['t' => 'Search', 'u' 					=> 'views/search.php'],
			'home' 						=> ['t' => 'Dashboard', 'u' 			=> 'views/dashboard.php'],
		];
		return !isset($dashboard_routes[$key]) ? $dashboard_routes['home'][$ret] : $dashboard_routes[$key][$ret];
	}

	public function ViewIncludes($cms) {
		return $this->views_ary($cms, 'u');
	}
	public function vPages($cms = '') {
		return $this->views_ary($cms, 't');
	}
}
