<!-- about block -->
<?php
$row = $conn->query("SELECT * FROM `my_info` WHERE `idPro` = '1' AND active = '1'")->fetch_assoc();
$num_ct = count($row);

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
                    echo '<ul class="myinfo">' . "\n";
                    echo '<li><span> ';
                    if ($lng == 1) {
                        echo 'Currently in ';
                    } else {
                        echo 'Actualmente en ';
                    }
                    echo new_location . '</span></li>' . "\n";
                    echo '<li><img src="' . SITE_PATH . 'img/whatsapp.png" />:<span> ' . whatsapp . '</span></li>' . "\n";
                    echo '<li><img src="' . SITE_PATH . 'img/message.png" />:<span> <a href="mailto:' . ADDRESS_CONTACT . '">' . ADDRESS_CONTACT . '</a></span></li>' . "\n";

                    echo '</ul>' . "\n";
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
