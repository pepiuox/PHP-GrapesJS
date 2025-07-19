<?php
$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$comment = trim($_POST["commnet"]);

$nameErr = $emailErr = "";

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
    if (empty($_POST["comment"])) {
        $comment = htmlspecialchars($comment);
    } else {
        $comment = protect($_POST["comment"]);
    }
} else {
    if (isset($_POST['enviar']) && $_POST['enviar'] == '') {

        // put your email address here     
        $youremail = SITE_EMAIL;

        // prepare a "pretty" version of the message
        $body = "Este manesaje a sido enviado desde la página web:" . "\n";
        $body .="Nombre:  " . $_POST[name] . "\n";
        $body .="E-Mail: " . $_POST[email] . "\n";
        $body .="Mensaje: " . $_POST[comment] . "\n";

        // Use the submitters email if they supplied one     
        // (and it isn't trying to hack your form).     
        // Otherwise send from your email address.     

        if ($_POST['email'] && !preg_match("/[\r\n]/", $_POST['email'])) {
            $headers = "From: $_POST[email]";
        } else {
            $headers = "From: $youremail";
        }

        // finally, send the message     
        mail($youremail, 'Contact Form', $body, $headers);
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
        <div class="infocontact">
<?php
$resultc = $conn->query("SELECT value FROM config");
$array = array();
while ($rowt = $resultc->fetch_row()) {
    $array[] = $rowt;
}
echo '<p>';
echo SITE_NAME . '<br>';
echo SITE_EMAIL . '<br />';
echo 'Telf: ' . PHONE_CONTACT;
?>   
            <br />             
            <?php
            $rsl = $conn->query("SELECT social_name, social_url FROM social_link");
            while ($sl = $rsl->fetch_array()) {
                if (!empty($sl['social_url'])) {
                    echo '<a href="' . $sl['social_url'] . '" target="_blank"><i class="fa fa-' . $sl['social_name'] . '"></i></a>&nbsp;';
                }
            }
            ?>                  
        </div>
    </div>        
    <div class="col_25_2">
        <h2>Contact <?php echo SITE_NAME; ?></h2>        
        <form class="ll" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                <label>Mensaje:</label>
                <textarea id='comment'></textarea>
            </div>
            <div class="col-md-12">
                <input type="submit" value="Enviar"/>
                <input type='hidden' value='1' name='enviar' />
            </div>
        </form>
<?php ?>
    </div>
</div>

</div>