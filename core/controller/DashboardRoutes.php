<?php
declare(strict_types=1);

/**
 * Gestor de rutas del dashboard.
 * Usa arrays de mapeo en lugar de múltiples if/elseif.
 */
class DashboardRoutes
{
    /**
     * Mapeo de rutas a vistas.
     */
    private array $viewMap = [
        'list_posts'      => 'views/blog_posts.php',
        'add_post'        => 'views/blog_posts.php',
        'edit_post'       => 'views/blog_posts.php',
        'delete_post'     => 'views/blog_posts.php',
        'post_category'   => 'views/category.php',
        'list_pages'      => 'views/pages.php',
        'add_page'        => 'views/pages.php',
        'edit_page'       => 'views/pages.php',
        'delete_page'     => 'views/pages.php',
        'siteconf'        => 'views/settings.php',
        'themes'          => 'views/themes.php',
        'files'           => 'views/files.php',
        'theme_template'  => 'views/theme_template.php',
        'menu_builder'    => 'views/menu_builder.php',
        'menu'            => 'views/menu.php',
        'plugins'         => 'views/plugins.php',
        'users'           => 'admin.php',
        'adduser'         => 'adduser.php',
        'table_crud'      => 'views/table_crud.php',
        'column_manager'  => 'views/column_manager.php',
        'table_config'    => 'views/table_config.php',
        'table_manager'   => 'views/table_manager.php',
        'volunteer'       => 'views/volunteer.php',
        'search'          => 'views/search.php',
    ];

    /**
     * Mapeo de rutas a títulos de página.
     */
    private array $titleMap = [
        'list_posts'      => 'List Posts',
        'add_post'        => 'Add Post',
        'edit_post'       => 'Edit Post',
        'delete_post'     => 'Delete Post',
        'post_category'   => 'Post Categories',
        'list_pages'      => 'Page List',
        'add_page'        => 'Add Page',
        'edit_page'       => 'Edit Page',
        'delete_page'     => 'Delete Page',
        'siteconf'        => 'Site Definitions',
        'themes'          => 'Themes',
        'files'           => 'Files',
        'theme_template'  => 'Theme Template',
        'menu_builder'    => 'Menu builder',
        'menu'            => 'Menu Template Color',
        'plugins'         => 'Plugins App',
        'table_crud'      => 'Table CRUD',
        'column_manager'  => 'Column Manager',
        'table_config'    => 'Table Config',
        'table_manager'   => 'Table Manager',
        'volunteer'       => 'Volunteer',
        'search'          => 'Search',
    ];

    /**
     * Obtiene la vista correspondiente a una ruta.
     *
     * @param string $cms Ruta solicitada
     * @return string Ruta al archivo de vista
     */
    public function ViewIncludes(string $cms): string
    {
        // 🔒 Validar que el CMS no contenga caracteres peligrosos
        $cms = $this->sanitizeRoute($cms);

        return $this->viewMap[$cms] ?? 'views/dashboard.php';
    }

    /**
     * Obtiene el título de página correspondiente a una ruta.
     *
     * @param string $cms Ruta solicitada
     * @return string Título de la página
     */
    public function vPages(string $cms = ''): string
    {
        $cms = $this->sanitizeRoute($cms);

        return $this->titleMap[$cms] ?? 'Dashboard';
    }

    /**
     * Registra una nueva ruta personalizada.
     */
    public function registerRoute(string $cms, string $view, string $title): self
    {
        $cms = $this->sanitizeRoute($cms);
        $this->viewMap[$cms] = $view;
        $this->titleMap[$cms] = $title;
        return $this;
    }

    /**
     * Obtiene todas las rutas registradas.
     */
    public function getAllRoutes(): array
    {
        return $this->viewMap;
    }

    /**
     * Sanitiza una ruta para prevenir path traversal.
     */
    private function sanitizeRoute(string $route): string
    {
        $route = trim($route);

        // Solo permitir caracteres alfanuméricos y guiones bajos
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $route)) {
            return '';
        }

        // Prevenir path traversal
        $route = str_replace(['..', '/', '\\'], '', $route);

        return $route;
    }
}
