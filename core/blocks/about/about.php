<!-- about block -->
<?php
$row = $conn->query("SELECT * FROM `my_info` WHERE `idPro` = '1' AND active = '1'")->fetch(PDO::FETCH_ASSOC);

// ✅ Cuando la consulta no devuelve ninguna fila, `fetch()` devuelve `false` y
// `count(false)` es un **TypeError** en PHP 8 (error fatal). La intención original era
// simplemente "¿hay datos para mostrar?".
$num_ct = ($row === false) ? 0 : count($row);

if ($num_ct > 0) {
    ?>  
    <div class="row">
        <div class="col-md-4">
            <div class="infocontact">

                <div class="imginfo">
                    <img class="scale" data-scale="best-fit-down" data-align="center" src="<?php echo $row['image']; ?>" alt="<?php echo $ttlp; ?>"/>  
                </div>
                <div class="tinfo">
                    <?php
                    echo '<ul class="myinfo">'."\n";
                    echo '<li><span> ';
                    if ($lng == 1) {
                        echo 'Currently in ';
                    } else {
                        echo 'Actualmente en ';
                    }
                    echo new_location . '</span></li>'."\n";
                    echo '<li><img src="' . SITE_PATH.'assets/img/whatsapp.png" />:<span> ' . WHATSAPP . '</span></li>'."\n";
                    echo '<li><img src="' . SITE_PATH.'assets/img/message.png" />:<span> <a href="mailto:' . EMAIL_CONTACT . '">' . EMAIL_CONTACT . '</a></span></li>'."\n";
                                       
                    echo '</ul>'."\n";
                    ?>           
                </div>

            </div>
        </div>        
        <div class="col-md-8">
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

    <?php
} else {
    if ($language == '1') {
        echo 'Missing items, add to better display page.';
    } else {
        echo'Faltan elementos, agregue para visualizar mejor la página.';
    }
}
?>