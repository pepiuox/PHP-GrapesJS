<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class MembersRegister {

    protected $conn;
    protected $gc;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->gc = new GetCodeDeEncrypt();
    }

    private function Registrater() {
        // Require credentials for DB connection.
        // Variables for createUser()
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $password2 = trim($_POST["password2"]);
        $email = $_POST["email"];

        if ($password === $password2) {
            // Create hashed user password.
            $securing = password_hash($password, PASSWORD_DEFAULT);

            // Check that all fields are filled with values.
            if (!empty($username) && !empty($password) && !empty($email)) {
                // Check if username or email is already taken.
                $stmt = $this->conn->prepare(
                    "SELECT username, email FROM users WHERE username = ? OR email = ?"
                );
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                // Check if email is in the correct format.
                if (
                    !preg_match(
                        "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",
                        $email
                    )
                ) {
                    $_SESSION["ErrorMessage"] = "Please insert the correct email.";
                    header("Location: registration.php");
                    exit();
                }

                // If username or email is taken.
                if ($result->num_rows != 0) {
                    // Promt user error about username or email already taken.
                    $_SESSION["ErrorMessage"] = "Firstname is taken from user or email!";
                    header("Location: registration.php");
                    exit();
                } else {
                    // Insert data into database
                    $code = $this->gc->getIdCode();
                    $stmt = $this->conn->prepare(
                        "INSERT INTO users (username, email, password, activation_code) VALUES (?,?,?,?)"
                    );
                    $stmt->bind_param(
                        "ssss",
                        $username,
                        $email,
                        $securing,
                        $code
                    );
                    $stmt->execute();
                    $stmt->close();

                    // Send user activation e-mail

                    $to = $email;
                    $subject = "Your activation code for registration.";
                    $from = "contact@labemotion.net"; // This should be changed to an email that you would like to send activation e-mail from.
                    $body = "Please follow the steps below <br> To activate your account, click on the following link" .
                        ' <a href="' .
                        $this->baseurl .
                        "/verify.php?id=" .
                        $email .
                        "&code=" .
                        $code .
                        '">Click for activete your account</a>.'; // Input the URL of your website.
                    $headers = "From: " . $from . "\r\n";
                    $headers .= "Reply-To: " . $from . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $success = mail($to, $subject, $body, $headers);
                    if ($success === true) {
                        $_SESSION["SuccessMessage"] = "A message was sent to your mailbox to activate your new account! ";
                    } else {
                        $_SESSION["ErrorMessage"] = "Error sending a message to your mailbox to activate your new account! ";
                    }
                    // If registration is successful return user to registration.php and promt user success pop-up.
                    $_SESSION["ErrorMessage"] = "The user has been created!";
                    header("Location: register.php");
                    exit();
                }
            } else {
                // If registration fails return user to registration.php and promt user failure error.
                $_SESSION["ErrorMessage"] = "Please complete all fields!";
                header("Location: register.php");
                exit();
            }
        } else {
            $_SESSION["ErrorMessage"] = "Passwords do not match!";
            header("Location: register.php");
            exit();
        }
        $this->conn->close();
    }

    /* End Registration() */
}
?>
