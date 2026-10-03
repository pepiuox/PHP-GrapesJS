<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class CheckUsersSession
{
    protected PDO $conn;
    private ?int $user = null;
    private ?string $hash = null;
    private int $expiry = 3600;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->expiry = time() + 3600;

        if (isset($_SESSION['user_id'], $_SESSION['hash'])) {
            $this->user = (int) $_SESSION['user_id'];
            $this->hash = $_SESSION['hash'];
            $this->checkUsers();
        }
    }

    /**
     * Verifica si la sesión del usuario sigue siendo válida en la base de datos.
     */
    private function checkUsers(): void
    {
        $stmt = $this->conn->prepare(
            "SELECT idUser FROM users
            WHERE idUser = :user AND mkhash = :hash
            LIMIT 1"
        );
        $stmt->execute([
            ':user' => $this->user,
            ':hash' => $this->hash,
        ]);

        $exists = $stmt->fetch(PDO::FETCH_ASSOC) !== false;

        if (!$exists) {
            $this->destroySession();
        }
    }

    /**
     * Destruye la sesión y redirige al login.
     */
    private function destroySession(): void
    {
        // Eliminar cookies específicas
        $cookiesToRemove = ['cookname', 'cookid'];
        foreach ($cookiesToRemove as $cookie) {
            if (isset($_COOKIE[$cookie])) {
                unset($_COOKIE[$cookie]);
                setcookie($cookie, '', time() - $this->expiry, '/');
            }
        }

        // Limpiar sesión
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }

        // Eliminar cookie de sesión PHP
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                      '',
                      time() - 42000,
                      $params['path'],
                      $params['domain'],
                      $params['secure'],
                      $params['httponly']
            );
        }

        // Redirigir al login
        header('Location: login.php');
        exit;
    }
}
