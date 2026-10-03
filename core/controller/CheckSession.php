<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class CheckSession
{
    protected PDO $conn;
    private string $session = '';
    private string $access  = '';
    private int $cookieLifetime = 21600; // 6 horas

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->checking();
    }

    private function sessionKey(int $len = 32): string
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes) . substr(sha1(random_bytes(13)), -$len);
    }

    private function accessKey(int $len = 32): string
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes) . substr(sha1(random_bytes(27)), -$len);
    }

    /**
     * Verifica y gestiona la sesión del cliente.
     */
    public function checking(): void
    {
        $cookieSession = $_COOKIE['client_session'] ?? '';

        if (!empty($cookieSession)) {
            $this->session = $cookieSession;

            if (empty($_SESSION['client_session'])) {
                // Verificar si la sesión existe en BD
                $stmt = $this->conn->prepare(
                    "SELECT COUNT(*) FROM active_sessions WHERE session = :session"
                );
                $stmt->execute([':session' => $this->session]);
                $exists = (int) $stmt->fetchColumn() > 0;

                if (!$exists) {
                    // Crear nueva sesión
                    $this->access = $this->accessKey();
                    $insert = $this->conn->prepare(
                        "INSERT INTO active_sessions (session, access) VALUES (:session, :access)"
                    );
                    $insert->execute([
                        ':session' => $this->session,
                        ':access'  => $this->access,
                    ]);
                }

                $_SESSION['client_session'] = $this->session;
            } elseif ($_SESSION['client_session'] !== $cookieSession) {
                // Inconsistencia: regenerar
                $this->regenerateSession();
            }
        } else {
            $this->regenerateSession();
        }
    }

    /**
     * Regenera la sesión del cliente.
     */
    private function regenerateSession(): void
    {
        unset($_COOKIE['client_session'], $_SESSION['client_session']);
        $nval = $this->sessionKey();
        setcookie('client_session', $nval, time() + $this->cookieLifetime, "/", "", false, true);
        $_SESSION['client_session'] = $nval;
    }
}
