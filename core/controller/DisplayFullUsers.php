<?php
declare(strict_types=1);

class DisplayFullUsers
{
    protected PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Muestra los usuarios en una tabla HTML formateada.
     */
    public function displayUsers(): void
    {
        try {
            // Obtener claves de encriptación
            $stmt = $this->conn->prepare(
                "SELECT SECURE_HASH, SECURE_TOKEN FROM site_security WHERE site = :site LIMIT 1"
            );
            $stmt->execute([':site' => 1]);
            $secure = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$secure) {
                echo '<div class="alert alert-danger">Error: Security configuration not found.</div>';
                return;
            }

            $stoken = $secure['SECURE_TOKEN'];
            $shash  = $secure['SECURE_HASH'];

            // Obtener usuarios
            $stmt = $this->conn->prepare(
                "SELECT username, email, level, timestamp
                FROM uverify
                ORDER BY level DESC"
            );
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($users) === 0) {
                echo '<p class="text-muted">No users found in the database.</p>';
                return;
            }

            $acc = new GetCodeDeEncrypt();

            // Renderizar tabla
            echo '<table class="table table-striped table-hover" id="display">';
            echo '<thead class="table-dark">';
            echo '<tr>';
            echo '<th colspan="2">Username</th>';
            echo '<th>Level</th>';
            echo '<th colspan="2">Email</th>';
            echo '<th colspan="2">Last activity</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($users as $row) {
                // Decrypt datos
                $uname = $acc->ende_crypter('decrypt', $row['username'], $stoken, $shash);
                $email = $acc->ende_crypter('decrypt', $row['email'], $stoken, $shash);
                $ulevel = (int) $row['level'];
                $time = $row['timestamp'];

                // 🛡️ XSS PROTECTION: escapar TODOS los outputs
                $uname = htmlspecialchars($uname ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $email = htmlspecialchars($email ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $time  = htmlspecialchars($time ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

                echo '<tr>';
                echo '<td colspan="2">' . $uname . '</td>';
                echo '<td><span class="badge bg-info">' . $ulevel . '</span></td>';
                echo '<td colspan="2">' . $email . '</td>';
                echo '<td colspan="2">' . $time . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } catch (PDOException $e) {
            error_log('DisplayUsers error: ' . $e->getMessage());
            echo '<div class="alert alert-danger">Error displaying users.</div>';
        }
    }

    /**
     * Muestra los usuarios baneados en una tabla HTML.
     */
    public function displayBannedUsers(): void
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT user_id, banned_timestamp
                FROM banned_users
                ORDER BY banned_timestamp DESC"
            );
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($users) === 0) {
                echo '<p class="col-12 text-muted">There are no banned users.</p>';
                return;
            }

            echo '<div class="table-responsive">';
            echo '<table class="table table-sm table-hover" id="display">';
            echo '<thead class="table-dark">';
            echo '<tr>';
            echo '<th colspan="2">Usuario</th>';
            echo '<th colspan="2">Tiempo Prohibido</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($users as $row) {
                // 🛡️ XSS PROTECTION
                $uname = htmlspecialchars($row['user_id'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $time  = htmlspecialchars($row['banned_timestamp'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

                echo '<tr>';
                echo '<td colspan="2">' . $uname . '</td>';
                echo '<td colspan="2">' . $time . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } catch (PDOException $e) {
            error_log('DisplayBannedUsers error: ' . $e->getMessage());
            echo '<div class="alert alert-danger">Error displaying banned users.</div>';
        }
    }
}
