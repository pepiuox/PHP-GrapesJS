<!-- profile block -->
<div class="row">
    <div id="myProfile">       
        <div class="col-md-6">
            <div class="infocontact">
                <?php
                // ✅ Si no hay fila, `fetch()` devuelve `false` y cualquier `$row['x']` emitía
                // avisos ("Trying to access array offset on value of type bool" /
                // "Undefined array key"). Se parte de un array con las claves esperadas.
                $row = $conn->query("SELECT * FROM `my_info` WHERE `idPro` = '1' AND active = '1'")->fetch(PDO::FETCH_ASSOC)
                        ?: array_fill_keys(
                            ['image', 'first_name', 'last_name', 'age', 'gender', 'description_en', 'description_es'],
                            ''
                        );
                ?>
                <div class="myPhoto">
                    <img class="scale" data-scale="best-fit-down" data-align="center" src="<?php echo $row['image']; ?>" />
                </div>
                <p><?php echo $row['first_name'] . ' ' . $row['last_name']; ?><br /> 
                    <?php
                    echo $row['age'] . ' / ';

                    $aage = array("Mujer", "Varon");

                    reset($aage);

                    foreach ($aage as $key => $val) {
                        if ($row['gender'] == $key) {
                            echo $val . '<br />';
                        }
                    }

                    $resultc = $conn->query("SELECT value FROM config");
                    $array = array();
                    while ($rowt = $resultc->fetch(PDO::FETCH_NUM)) {
                        $array[] = $rowt;
                    }
                    echo '<a href="mailto:' . SITE_EMAIL . '">' . SITE_EMAIL . '</a><br />';
                    echo 'Telf: ' . PHONE_CONTACT . '<br />';
                    // ✅ `skype` (minúscula) es una constante inexistente: en PHP 8 lanza
                    // Error fatal "Undefined constant". La constante definida es SKYPE.
                    echo 'Skype: ' . SKYPE;
                    ?>
                </p>                  
            </div>
        </div>        
        <div class="col-md-6">                    
            <div class='scrollpanel'>
                <div class="sconte">
                    <?php
                    if ($lng == 1) {
                        echo $row['description_en'];
                    } else {

                        echo $row['description_es'];
                    }
                    ?>
                </div>
            </div> 
        </div>
    </div>
</div>