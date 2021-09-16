

<?php
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Nombre es requirido";
    } else {
        $name = protect($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Solo letras y espacios en blanco";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email es requirido";
    } else {
        $email = protect($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalido el formato de email";
        }
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Telefono es requirido";
    } else {
        $phone = protect($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[0-9 ]*$/", $phone)) {
            $phoneErr = "Solo numeros y espacios";
        }
    }

    if (empty($_POST["website"])) {
        $website = "";
    } else {
        $website = protect($_POST["website"]);
        // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
            $websiteErr = "Invalid URL";
        }
    }

    if (empty($_POST["comment"])) {
        $comment = "";
    } else {
        $comment = protect($_POST["comment"]);
    }

    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = protect($_POST["gender"]);
    }
}

function protect($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<div class="col-md-12">       
    <div class="col_25_2">
        <?php
        if ($bid) {
            $result = $conn->query("SELECT type_name, value FROM config");
        }
        $row = $result->fetch_assoc();
        echo $row['content'];
        ?>
        <p><?php echo SITE; ?>
            <br />    
            info@mysite.com
            <br /> 
            Tel: 123-456-7890
        </p>
    </div>        
    <div class="col_25_2">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="col-md-12">
                <label>Nombre:</label>
                <input id='name' type="text" value=""/>
                <span class="error">* <?php echo $nameErr; ?></span>
            </div>
            <div class="col-md-12">
                <label>Email:</label>
                <input id='email' type="text" value=""/>
                <span class="error">* <?php echo $emailErr; ?></span>
            </div>
            <div class="col-md-12">
                <label>Teléfono:</label>
                <input id='phone' type="text" value=""/>
                <span class="error">* <?php echo $phoneErr; ?></span>
            </div>
            <div class="col-md-12">
                <label>
                    Website:</label>
                <input type="text" name="website" value="<?php echo $website; ?>">
                <span class="error"><?php echo $websiteErr; ?></span>
            </div>
            <div class="col-md-12">
                Genero:
                <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female") echo "checked"; ?> value="female">Mujer
                <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male") echo "checked"; ?> value="male">varon
                <span class="error">* <?php echo $genderErr; ?></span>
            </div>
            <div class="col-md-12">
                <label>Comentario:</label>
                <textarea id='comment'></textarea>
            </div>
            <div class="col-md-12">
                <input type="submit" value="Enviar"/>
            </div>
        </form>
    </div>
</div>

</div>