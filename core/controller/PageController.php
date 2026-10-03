<?php
// classes/PageController.php
// Migrated to PDO & Secured by AI Assistant
class PageController {
    private PageManager $repository;
    private PagePublic $pageRenderer;
    private PageCacheManager $cache;

    public function __construct(PDO $db, array $config = []) {
        $this->repository = new PageManager($db);
        $this->pageRenderer = new PagePublic($db);
        $this->cache = new PageCacheManager($db, $config);
    }

    /**
     * Renderiza una página pública por slug
     */
    public function renderPage(string $slug): void {
        // Normalizar slug
        $slug = trim($slug, '/');

        if (empty($slug)) {
            $slug = 'home';
        }

        // Intentar obtener de caché primero
        $pageData = $this->cache->getPage($slug);

        if (!$pageData) {
            // Cargar desde base de datos
            $pageData = $this->repository->getPageBySlug($slug);

            if (!$pageData) {
                http_response_code(404);
                echo '<h1>Página no encontrada</h1>';
                return;
            }

            // Guardar en caché para próximas visitas
            $this->cache->saveToCache(
                $this->cache->generateCacheKey($slug),
                                      $slug,
                                      $pageData
            );
        }

        // Renderizar página
        $this->pageRenderer->viewPagePublic($pageData);
    }

    /**
     * Renderiza una página de administrador
     */
    public function renderAdminPage(int $page_id, int $user_id): void {
        $pageData = $this->repository->getPage($page_id, $user_id);

        if (!$pageData) {
            http_response_code(404);
            echo '<h1>Página no encontrada</h1>';
            return;
        }

        // Verificar permisos
        if ((int)$pageData['user_id'] !== $user_id) {
            http_response_code(403);
            echo '<h1>Acceso denegado</h1>';
            return;
        }

        // Renderizar en modo edición
        $pageData['edit_mode'] = true;
        $this->pageRenderer->viewPagePublic($pageData);
    }

    /**
     * Crea una nueva página
     */
    public function createPage(array $data): int|false {
        // Validar datos
        if (empty($data['title']) || empty($data['user_id'])) {
            return false;
        }

        // Generar slug si no existe
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        $page_id = $this->repository->createPage($data);

        if ($page_id) {
            // Invalidar caché relacionado
            $this->cache->invalidateAllCache();
        }

        return $page_id;
    }

    /**
     * Actualiza una página existente
     */
    public function updatePage(int $page_id, int $user_id, array $data): bool {
        // Verificar que la página existe y pertenece al usuario
        $page = $this->repository->getPage($page_id, $user_id);

        if (!$page) {
            return false;
        }

        $success = $this->repository->updatePage($page_id, $data);

        if ($success) {
            // Invalidar caché de esta página
            $this->cache->invalidateCache($page['slug']);
        }

        return $success;
    }

    /**
     * Elimina una página
     */
    public function deletePage(int $page_id, int $user_id): bool {
        // Verificar permisos
        $page = $this->repository->getPage($page_id, $user_id);

        if (!$page) {
            return false;
        }

        $success = $this->repository->deletePage($page_id);

        if ($success) {
            // Invalidar caché
            $this->cache->invalidateCache($page['slug']);
        }

        return $success;
    }

    /**
     * Publica/despublica una página
     */
    public function togglePublish(int $page_id, int $user_id, bool $publish): bool {
        $page = $this->repository->getPage($page_id, $user_id);

        if (!$page) {
            return false;
        }

        $success = $publish
        ? $this->repository->publish($page_id, $user_id)
        : $this->repository->unpublish($page_id, $user_id);

        if ($success) {
            // Invalidar caché
            $this->cache->invalidateCache($page['slug']);
        }

        return $success;
    }

    /**
     * Obtiene estadísticas del caché
     */
    public function getCacheStats(): array {
        return $this->cache->getCacheStats();
    }

    /**
     * Limpia caché expirado
     */
    public function cleanExpiredCache(): int {
        return $this->cache->cleanExpiredCache();
    }

    /**
     * Genera un slug único
     */
    private function generateSlug(string $title): string {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        $slug = $slug . '-' . bin2hex(random_bytes(4));

        return $slug;
    }
}
