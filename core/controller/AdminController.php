<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class AdminController extends BaseController
{
    private PDO $db;
    private Auth $auth;

    public function __construct(PDO $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
    }

    /**
     * Muestra el dashboard del administrador.
     */
    public function dashboard(): void
    {
        $this->auth->requireAdmin();

        $stats = $this->getDashboardStats();

        // Renderizar vista (ejemplo básico)
        echo "<h1>Welcome to Admin Dashboard</h1>";
        echo "<p>Usuarios activos: {$stats['active_users']}</p>";
        echo "<p>Visitas hoy: {$stats['visits_today']}</p>";
    }

    /**
     * Obtiene estadísticas para el dashboard.
     */
    private function getDashboardStats(): array
    {
        $stats = [
            'active_users' => 0,
            'visits_today' => 0,
            'pending_orders' => 0,
        ];

        try {
            // Usuarios activos
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM users WHERE verified = 1 AND is_banned = 0"
            );
            $stmt->execute();
            $stats['active_users'] = (int) $stmt->fetchColumn();

            // Visitas de hoy
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM visits WHERE DATE(visit_date) = CURDATE()"
            );
            $stmt->execute();
            $stats['visits_today'] = (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Dashboard stats error: " . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Lista todos los usuarios (solo admin).
     */
    public function listUsers(int $page = 1, int $perPage = 20): array
    {
        $this->auth->requireAdmin();

        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            "SELECT id, username, email, level, verified, last_login
            FROM users
            ORDER BY last_login DESC
            LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
