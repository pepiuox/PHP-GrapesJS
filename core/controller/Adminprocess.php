<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class AdminProcess
{
    public $session;
    protected PDO $conn;
    public $form;

    public function __construct(PDO $conn, $session, $form)
    {
        $this->session = $session;
        $this->conn = $conn;
        $this->form = $form;

        /* Verificar que el administrador esté accediendo */
        if (!$this->session->isAdmin()) {
            header("Location: ../index.php");
            return;
        }

        /* Router de acciones */
        $actions = [
            'subjoin'      => 'procRegisterUser',
            'subupdvalid'  => 'procUpdateValid',
            'subupdlevel'  => 'procUpdateLevel',
            'subdeluser'   => 'procDeleteUser',
            'subdelinact'  => 'procDeleteInactive',
            'subbanuser'   => 'procBanUser',
            'subdelbanned' => 'procDeleteBannedUser',
        ];

        $executed = false;
        foreach ($actions as $postKey => $method) {
            if (isset($_POST[$postKey])) {
                $this->$method();
                $executed = true;
                break;
            }
        }

        if (!$executed) {
            header("Location: ../index.php");
            exit;
        }
    }

    public function procRegisterUser(): void
    {
        $_POST = $this->session->cleanInput($_POST);

        if (defined('ALL_LOWERCASE') && ALL_LOWERCASE) {
            $_POST["user"] = strtolower($_POST["user"]);
        }

        $retval = $this->session->register(
            $_POST["user"],
            $_POST["pass"],
            $_POST["email"],
            $_POST["name"]
        );

        if ($retval == 0) {
            $_SESSION["reguname"] = $_POST["user"];
            $_SESSION["regsuccess"] = true;
        } elseif ($retval == 1) {
            $_SESSION["value_array"] = $_POST;
            $_SESSION["error_array"] = $this->form->getErrorArray();
        } elseif ($retval == 2) {
            $_SESSION["reguname"] = $_POST["user"];
            $_SESSION["regsuccess"] = false;
        }

        header("Location: " . $this->session->referrer);
        exit;
    }

    public function procUpdateValid(): void
    {
        $subuser = $this->checkUsername("valuser");

        if ($this->form->num_errors > 0) {
            $_SESSION["value_array"] = $_POST;
            $_SESSION["error_array"] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
            exit;
        }

        // ✅ CORREGIDO: usa prepared statements
        $query = "UPDATE uverify SET valid = :valid WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':valid' => (int) $_POST["updvalid"],
                       ':username' => $subuser,
        ]);

        header("Location: " . $this->session->referrer);
        exit;
    }

    public function procUpdateLevel(): void
    {
        $subuser = $this->checkUsername("upduser");

        if ($this->form->num_errors > 0) {
            $_SESSION["value_array"] = $_POST;
            $_SESSION["error_array"] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
            exit;
        }

        // ✅ CORREGIDO: usa prepared statements
        $query = "UPDATE uverify SET level = :level WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':level' => (int) $_POST["updlevel"],
                       ':username' => $subuser,
        ]);

        header("Location: " . $this->session->referrer);
        exit;
    }

    public function procDeleteUser(): void
    {
        $subuser = $this->checkUsername("deluser");

        if ($this->form->num_errors > 0) {
            $_SESSION["value_array"] = $_POST;
            $_SESSION["error_array"] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
            exit;
        }

        // 🔒 CORREGIDO: SQL injection crítico eliminado
        $query = "DELETE FROM uverify WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':username' => $subuser]);

        header("Location: " . $this->session->referrer);
        exit;
    }

    public function procDeleteInactive(): void
    {
        $inactDays = (int) ($_POST["inactdays"] ?? 0);
        if ($inactDays <= 0) {
            header("Location: " . $this->session->referrer);
            exit;
        }

        $inactTime = time() - ($inactDays * 24 * 60 * 60);
        $adminLevel = defined('ADMIN_LEVEL') ? ADMIN_LEVEL : 9;

        // 🔒 CORREGIDO: SQL injection crítico eliminado
        $query = "DELETE FROM uverify
        WHERE timestamp < :inact_time AND level != :admin_level";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':inact_time' => $inactTime,
            ':admin_level' => $adminLevel,
        ]);

        header("Location: " . $this->session->referrer);
        exit;
    }

    public function procBanUser(): void
    {
        $subuser = $this->checkUsername("banuser");

        if ($this->form->num_errors > 0) {
            $_SESSION["value_array"] = $_POST;
            $_SESSION["error_array"] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
            exit;
        }

        // 🔒 CORREGIDO: SQL injection crítico eliminado
        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare("DELETE FROM uverify WHERE username = :username");
            $stmt->execute([':username' => $subuser]);

            $stmt = $this->conn->prepare(
                "INSERT INTO banned_users (username, timestamp) VALUES (:username, :time)"
            );
            $stmt->execute([
                ':username' => $subuser,
                ':time' => time(),
            ]);

            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error banning user: " . $e->getMessage());
        }

        header("Location: " . $this->session->referrer);
        exit;
    }

    public function procDeleteBannedUser(): void
    {
        $subuser = $this->checkUsername("delbanuser", true);

        if ($this->form->num_errors > 0) {
            $_SESSION["value_array"] = $_POST;
            $_SESSION["error_array"] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
            exit;
        }

        // 🔒 CORREGIDO: SQL injection crítico eliminado
        $query = "DELETE FROM banned_users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':username' => $subuser]);

        header("Location: " . $this->session->referrer);
        exit;
    }

    /**
     * Valida el username enviado por el formulario.
     */
    public function checkUsername(string $uname, bool $ban = false): string
    {
        $subuser = trim($_POST[$uname] ?? '');
        $field = $uname;

        if ($subuser === '' || strlen($subuser) === 0) {
            $this->form->setError($field, "* Usuario no aceptado<br>");
            return '';
        }

        $subuser = stripslashes($subuser);

        if (strlen($subuser) < 5 || strlen($subuser) > 30) {
            $this->form->setError($field, "* Longitud de usuario inválida<br>");
            return '';
        }

        if (!preg_match("/^([0-9a-z])+$/i", $subuser)) {
            $this->form->setError($field, "* Caracteres no permitidos<br>");
            return '';
        }

        if (!$ban && !$this->usernameExists($subuser)) {
            $this->form->setError($field, "* El usuario no existe<br>");
            return '';
        }

        return $subuser;
    }

    /**
     * Verifica si un username existe en la base de datos (usando PDO).
     */
    private function usernameExists(string $username): bool
    {
        $query = "SELECT COUNT(*) FROM uverify WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':username' => $username]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
?>
