<div class="w-100">
                    <?php
                    /* Requested Username error checking */
                    $req_user = trim($_GET['user']);
                    if (!$req_user || strlen($req_user) == 0 ||
                            !preg_match("/^([0-9a-z])+$/i", $req_user) ||
                            !$conn->usernameTaken($req_user)) {
                        die("Usuario no registrado");
                    }
                    ?>            
                    <?php
                    /* Logged in user viewing own account */
                    if (strcmp($session->username, $req_user) == 0) {
                        echo "<h3>Mi Cuenta</h3>";
                    }
                    /* Visitor not viewing own account */ else {
                        echo "<h3>Información de Usuario</h3>";
                    }

                    /* Display requested user information */
                    $req_user_info = $conn->getUserInfo($req_user);

                    /* Name */
                    echo "<p><b>Nombre:</b> " . $req_user_info['name'] . "<br />";

                    /* Username */
                    echo "<b>Usuario:</b> " . $req_user_info['username'] . "<br />";

                    /* Email */
                    echo "<b>Email:</b> " . $req_user_info['email'] . "</p>";
                    ?>
</div>
<div class="w-100">
                        <?php
                        /**
                         * Note: when you add your own fields to the users tablemysql_numrows()
                         * to hold more information, like homepage, location, etc.
                         * they can be easily accessed by the user info array.
                         *
                         * $session->user_info['location']; (for logged in users)
                         *
                         * ..and for this page,
                         *
                         * $req_user_info['location']; (for any user)
                         */
                        /* If logged in user viewing own account, give link to edit */
                        if (strcmp($session->username, $req_user) == 0) {
                            echo "<a href=\"dashboard.php?cms=useredit\">Editar Cuenta Información</a><br /><br />";
                        }

                        if ($session->isAdmin()) {
                            echo "[<a href=\"admin/admin.php\">Centro de Administración</a>]&nbsp;";
                        }
                        /* Link Volver a main */
                        echo "[<a href=\"index.php\">Principal</a>] <br />";
                        ?>
</div>
                