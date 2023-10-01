<?php

class DashboardRoutes {

    public $cms;

    public function __construct() {
        
    }

    public function ViewIncludes($cms) {
        $view = '';
        if ($cms === 'list_posts') {
            $view = 'views/blog_posts.php';
        } elseif ($cms === 'add_post') {
            $view = 'views/blog_posts.php';
        } elseif ($cms === 'edit_post') {
            $view = 'views/blog_posts.php';
        } elseif ($cms === 'delete_post') {
            $view = 'views/blog_posts.php';
        } elseif ($cms === 'post_category') {
            $view = 'views/category.php';
        } elseif ($cms === 'list_pages') {
            $view = 'views/pages.php';
        } elseif ($cms === 'add_page') {
            $view = 'views/pages.php';
        } elseif ($cms === 'edit_page') {
            $view = 'views/pages.php';
        } elseif ($cms === 'delete_page') {
            $view = 'views/pages.php';
        } elseif ($cms === 'siteconf') {
            $view = 'views/settings.php';
        } elseif ($cms === 'themes') {
            $view = 'views/themes.php';
        } elseif ($cms === 'theme_template') {
            $view = 'views/theme_template.php';
        } elseif ($cms === 'menu') {
            $view = 'views/menu.php';
        } elseif ($cms === 'plugins') {
            $view = 'views/plugins.php';
        } elseif ($cms === 'users') {
            $view = 'admin.php';
        } elseif ($cms === 'adduser') {
            $view = 'adduser.php';
        } elseif ($cms === 'table_crud') {
            $view = 'views/table_crud.php';
        } elseif ($cms === 'column_manager') {
            $view = 'views/column_manager.php';
        } elseif ($cms === 'table_config') {
            $view = 'views/table_config.php';
        } elseif ($cms === 'table_manager') {
            $view = 'views/table_manager.php';
        } elseif ($cms === 'volunteer') {
            $view = 'views/volunteer.php';
        } elseif ($cms === 'search') {
            $view = 'views/search.php';
        } else {
            $view = 'views/dashboard.php';
        }
        return $view;
    }

    public function vPages($cms = '') {
        if ($cms === 'list_posts') {
            $vpages = 'List Posts';
        } elseif ($cms === 'add_post') {
            $vpages = 'Add Post';
        } elseif ($cms === 'edit_post') {
            $vpages = 'Edit Post';
        } elseif ($cms === 'delete_post') {
            $vpages = 'Delete Post';
        } elseif ($cms === 'post_category') {
            $vpages = 'Post Categories';
        } elseif ($cms === 'list_pages') {
            $vpages = 'Page List';
        } elseif ($cms === 'add_page') {
            $vpages = 'Add Page';
        } elseif ($cms === 'edit_page') {
            $vpages = 'Edit Page';
        } elseif ($cms === 'delete_page') {
            $vpages = 'Delete Page';
        } elseif ($cms === 'siteconf') {
            $vpages = 'Site Definitions';
        } elseif ($cms === 'themes') {
            $vpages = 'Themes';
        } elseif ($cms === 'theme_template') {
            $vpages = 'Theme Template';
        } elseif ($cms === 'menu') {
            $vpages = 'Menu Template';
        } elseif ($cms === 'plugins') {
            $vpages = 'Plugins App';
        } elseif ($cms === 'table_crud') {
            $vpages = 'Table CRUD';
        } elseif ($cms === 'column_manager') {
            $vpages = 'Column Manager';
        } elseif ($cms === 'table_config') {
            $vpages = 'Table Config';
        } elseif ($cms === 'table_manager') {
            $vpages = 'Table Manager';
        } elseif ($cms === 'volunteer') {
            $vpages = 'Volunteer';
        } elseif ($cms === 'search') {
            $vpages = 'Search';
        } else {
            $vpages = 'Dashboard';
        }
        return $vpages;
    }
}
