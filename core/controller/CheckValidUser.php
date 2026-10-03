<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class CheckValidUser
{
    protected PDO $conn;
    private ?int $id = null;
    private ?string $hash = null;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_SESSION['user_id'], $_SESSION['hash'])) {
            $this->id   = (int) $_SESSION['user_id'];
            $this->hash = $_SESSION['hash'];
            $this->checkUser();
        }
    }

    /**
     * Verifica la validez del usuario y define constantes globales.
     */
    private function checkUser(): void
    {
        $stmt = $this->conn->prepare(
            "SELECT i.firstname, i.lastname,
            u.avatar, u.profile_image, u.profession, u.occupation
            FROM users_profiles u
            LEFT JOIN users_info i ON u.usercode = i.usercode
            WHERE u.idp = :id AND u.mkhash = :hash
            LIMIT 1"
        );
        $stmt->execute([
            ':id'   => $this->id,
            ':hash' => $this->hash,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Definir constantes solo si no están ya definidas
            $this->defineIfNot('USERS_NAMES', $row['firstname'] ?? '');
            $this->defineIfNot('USERS_LASTNAMES', $row['lastname'] ?? '');
            $this->defineIfNot(
                'USERS_FULLNAMES',
                trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? ''))
            );
            $this->defineIfNot('USERS_AVATARS', $row['avatar'] ?? '');
            $this->defineIfNot('USERS_IMAGE', $row['profile_image'] ?? '');
            $this->defineIfNot('USERS_SKILLS', $row['profession'] ?? '');
            $this->defineIfNot('USERS_CURRENTS_OCCUPATION', $row['occupation'] ?? '');
        } else {
            // Sesión inválida: destruir
            $this->destroySession();
        }
    }

    /**
     * Define una constante solo si no existe previamente.
     */
    private function defineIfNot(string $name, string $value): void
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Destruye la sesión del usuario.
     */
    private function destroySession(): void
    {
        $keysToUnset = [
            'username', 'user_id', 'level', 'hash',
            'access_id', 'client_session',
        ];

        foreach ($keysToUnset as $key) {
            unset($_SESSION[$key]);
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }

        // Eliminar cookies de sesión
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
    }
}
