<?php
$dbprdc = new Database();
$dbprd = $dbprdc->PdoConnection();

$base = 'http://' . $_SERVER['HTTP_HOST'];

$url = $base . "/products_img/";

$urldir = $_SERVER['REQUEST_URI']; //returns the current URL

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $cart = New AddCart($dbprd);
    $rating = New Rating($dbprd);
    $check = New CheckSession($dbprd);

    $cart->session_key = $_SESSION['client_session'];
    $cart->idPrd = $cart->producto_id = $rating->producto_id = $id;

    $query = "SELECT producto FROM productos WHERE idPrd=:idPrd";
    $rest = $dbprd->prepare($query);
    $idPrd = htmlspecialchars(strip_tags($id));
    $rest->bindParam(":idPrd", $idPrd);
// execute query
    $rest->execute();
    $nrt = $rest->rowCount();

    if ($nrt === 1) {
        $ttl = $rest->fetch(PDO::FETCH_ASSOC);
        $title = $ttl['producto'];

//acction for cart system
        if (isset($_POST['addcart'])) {
            include 'addproduct.php';
        }
        if (isset($_POST['updatecart'])) {
            if (isset($_SESSION['client_session']) && !empty($_SESSION['client_session'])) {
                if ($cart->exists()) {
                    $cart->cantidad = $cant = $_POST['cantidad'];
                    $cart->update();
                }
            }
        }
        if (isset($_POST['buynow'])) {
            header('Location: ' . SITE_PATH . 'cart');
            exit;
        }
        if (isset($_POST['heartlike'])) {
            
        }
        ?>
        </head>
        <body>
            <?php
            include_once 'navbar.php';
            include_once 'find_word.php';
            ?>
            <div class="sidebarCart">
                <div class="scrollingCart">
                    <?php
                    echo $cart->itemsCart();
                    ?>                    
                </div>
            </div>
            <section class="pt-3">
                <div class="container-fluid my-2">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">                   
                                    <div class="row">
                                        <?php
                                        $result = $cart->imagenProducto();
                                        $num_rows = $result->rowCount();

                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            $items[] = $row;
                                        }
                                        if ($num_rows > 0) {
                                            ?>
                                            <div class="col-md-12 mx-2 py-2">
                                                <?php
                                                $i = 1;
                                                foreach ($items as $k => $p) {
                                                    $n = $i++;
                                                    ?>
                                                    <div class="mySlides">
                                                        <div class="numbertext">1 / <?= $n; ?></div>
                                                        <img src="<?= PATH_APP . 'products_img/' . $p['imagen_producto']; ?>" style="width:100%">
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <a class="prev" onclick="plusSlides(-1)">❮</a>
                                                <a class="next" onclick="plusSlides(1)">❯</a>
                                            </div>
                                            <div class="col-md-12 mx-2 py-2">
                                                <?php
                                                $j = 1;
                                                foreach ($items as $k => $p) {
                                                    $n = $j++;
                                                    ?>
                                                    <div class="column">
                                                        <img class="demo cursor" src="<?= PATH_APP . 'products_img/' . $p['imagen_producto']; ?>" style="width:100%" onclick="currentSlide(<?= $n; ?>)">
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        } else {

                                            $images = $cart->product();
                                            while ($row = $images->fetch(PDO::FETCH_ASSOC)) {
                                                $imgs[] = $row;
                                            }
                                            ?>
                                            <div class="col-md-12 mx-2 py-2">
                                                <?php
                                                $i = 1;
                                                foreach ($imgs as $k => $p) {
                                                    $n = $i++;
                                                    ?>
                                                    <div class="mySlides">
                                                        <div class="numbertext">1 / <?= $n; ?></div>
                                                        <img src="<?= PATH_APP . 'products_img/' . $p['imagen_producto']; ?>" style="width:100%">
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <a class="prev" onclick="plusSlides(-1)">❮</a>
                                                <a class="next" onclick="plusSlides(1)">❯</a>
                                            </div>
                                            <div class="col-md-12 mx-2 py-2">
                                                <?php
                                                $j = 1;
                                                foreach ($imgs as $k => $p) {
                                                    $n = $j++;
                                                    ?>
                                                    <div class="column">
                                                        <img class="demo cursor" src="<?= PATH_APP . 'products_img/' . $p['imagen_producto']; ?>" style="width:100%" onclick="currentSlide(<?= $n; ?>)">
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        <?php }
                                        ?>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 pt-3">
                                            <?php
                                            $presult = $cart->singleProduct();
                                            $nc = $presult->rowCount();

                                            $prow = $presult->fetch(PDO::FETCH_ASSOC);
                                            $nid = $prow['idPrd'];
                                            $nmar = $prow['nombre_marca'];
                                            $ncat = $prow['nombre_categoria'];
                                            $scat = $prow['nombre_sub_categoria'];
                                            $prec = $prow['precio'];
                                            $pdes = $prow['descuento'];
                                            $descu = '0.' . $pdes;
                                            $tdes = $prec * $descu;
                                            $pt = $prec - $tdes;

                                            function roundDecimal($value) {
                                                $float_round = round($value * 100) / 100;
                                                return number_format($float_round, 2, ".", ",");
                                            }

                                            function genCode($id, $marc, $ncat, $scat) {
                                                $nt = '00000' . $id;
                                                $a = substr($marc, 0, 3);
                                                $b = substr($ncat, 0, 1);
                                                $c = substr($scat, 0, 1);
                                                $d = substr($nt, -5);

                                                return strtoupper($a . $b . $c . $d);
                                            }
                                            ?>
                                            <div class="product mx-2">
                                                <p><span style="font-size: 125%;"><?= $prow['producto'] ?></span></p>
                                                <p><strong>Codigo: <span style="font-size: 14px"><?= genCode($id, $nmar, $ncat, $ncat); ?></span></strong>
                                                    <?php
                                                    if (!empty($prow['marca_id'])) {
                                                        echo '<br>Marca: <a href="' . PATH_APP . 'marcas?brand=' . $prow['idMarc'] . '">' . $prow['nombre_marca'] . '</a>';
                                                    }
                                                    ?>   
                                                </p>
                                                <?php
                                                // Rating products

                                                $rresult = $rating->getRatingProduct();
                                                $rting = $rresult->rowCount();
                                                $rrow = $rresult->fetch(PDO::FETCH_ASSOC);
                                                ?>
                                                <div class="w-100" style='color:#ffb300;'>
                                                    <?php
                                                    if ($rting > 0) {

                                                        if ($rrow['rating'] >= 100) {
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                        } elseif ($rrow['rating'] >= 200) {
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                        } elseif ($rrow['rating'] >= 300) {
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                        } elseif ($rrow['rating'] >= 400) {
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                        } elseif ($rrow['rating'] >= 500) {
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                            echo '<i class="fas fa-star"></i>';
                                                        } else {
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                            echo '<i class="far fa-star"></i>';
                                                        }
                                                    } else {
                                                        echo '<i class="far fa-star"></i>';
                                                        echo '<i class="far fa-star"></i>';
                                                        echo '<i class="far fa-star"></i>';
                                                        echo '<i class="far fa-star"></i>';
                                                        echo '<i class="far fa-star"></i>';
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                                if (!empty($prow['categoria_id'])) {
                                                    echo '<p><b>Categoria:</b> <a href="' . PATH_APP . 'categorias?cat=' . $prow['idCat'] . '">' . $prow['nombre_categoria'] . '</a>';
                                                    if (!empty($prow['sub_categoria_id'])) {
                                                        echo '-> <a href="' . PATH_APP . 'subcategorias?subcat=' . $prow['idSubc'] . '">' . $prow['nombre_sub_categoria'] . '</a>';
                                                    }
                                                    echo '</p>';
                                                }
                                                ?>      
                                                <?php
                                                if (!empty($prow['linea_id'])) {
                                                    echo '<p><b>Linea:</b> <a href="' . PATH_APP . 'lineas?cat=' . $prow['idLin'] . '">' . $prow['nombre_linea'] . '</a>';
                                                    if (!empty($prow['sub_linea_id'])) {
                                                        echo '-> <a href="' . PATH_APP . 'sublineas?subcat=' . $prow['idSlin'] . '">' . $prow['nombre_sub_linea'] . '</a>';
                                                    }
                                                    echo '</p>';
                                                }
                                                ?> 
                                                <?php
                                                if (!empty($prow['familia_id'])) {
                                                    echo '<p><a href="' . PATH_APP . 'families=' . $prow['idFam'] . '">' . $prow['titulo_familia'] . '</a></p>';
                                                }
                                                ?> 
                                                <?php
                                                if (!empty($prow['grupo_id'])) {
                                                    echo '<p><b>Grupo:</b> <a href="' . PATH_APP . 'groups?cat=' . $prow['idLin'] . '">' . $prow['nombre_linea'] . '</a>';
                                                    if (!empty($prow['sub_linea_id'])) {
                                                        echo '-> <a href="' . PATH_APP . 'subgrupos?subcat=' . $prow['idSlin'] . '">' . $prow['nombre_sub_linea'] . '</a>';
                                                    }
                                                    echo '</p>';
                                                }
                                                ?>
                                                <h4>Precio: S/. <?= roundDecimal($pt); ?></h4>                                           
                                                <?php if (!empty($pdes)) {
                                                    ?>
                                                    <div class="mx-2 p-2" style="-webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; text-align:center; <?php
                                                    if ($pdes === 5) {
                                                        echo 'background: #ff0000; color: #fff;';
                                                    } else if ($pdes === 10) {
                                                        echo 'background: #ff5300; color: #fff;';
                                                    } else if ($pdes === 15) {
                                                        echo 'background: #ff9400; color: #fff;';
                                                    } else if ($pdes === 20) {
                                                        echo 'background: #ffd300; color: #666;';
                                                    } else if ($pdes === 25) {
                                                        echo 'background: #ffff00; color: #666;';
                                                    } else if ($pdes === 30) {
                                                        echo 'background: #d0f600; color: #666;';
                                                    } else if ($pdes === 35) {
                                                        echo 'background: #92e900; color: #666;';
                                                    } else if ($pdes === 40) {
                                                        echo 'background: #51dc00; color: #fff;';
                                                    } else if ($pdes === 45) {
                                                        echo 'background: #00cc00; color: #fff;';
                                                    } else if ($pdes === 50) {
                                                        echo 'background: #00b200; color: #fff;';
                                                    }
                                                    ?>">
                                                        <p><span style="font-size: 180%;">con <b><?= $prow['descuento'] ?>%</b> de descuento</span></p>
                                                        <p><span style="font-size: 130%;"> Antes S/. </span> <span style="font-size: 130%; text-decoration: line-through;"><?= $prec; ?> </span> &nbsp;&nbsp;  <span style="font-size: 120%"> ahorras S/. <?= roundDecimal($tdes); ?></span></p>
                                                    </div>
                                                <?php }
                                                ?>
                                                <form method="post">
                                                    <p>
                                                        <?php
                                                        if ($cart->exists()) {
                                                            echo '<button type="submit" class="btn btn-updatecart" id="updatecart" name="updatecart">Actualizar carrito</button> ';
                                                        } else {
                                                            echo '<button type="submit" class="btn btn-addcart" id="addcart" name="addcart">Agregar al carrito</button> ';
                                                        }
                                                        ?>
                                                        <button type="submit" class="btn btn-buynow" id="buynow" name="buynow">Comprar ahora</button>
                                                        <?php
                                                        if ($rating->checkProduct()) {
                                                            echo '<button class="btn btn-outline-heart"><i class="fas fa-heart"></i></button>';
                                                        } else {
                                                            echo '<button type="submit" class="btn btn-outline-heart" name="heartlike"><i class="far fa-heart"></i></button>';
                                                        }
                                                        ?>
                                                    </p>
                                                    <hr>
                                                    <strong>Cantidad:</strong><br />
                                                    <div class="cart-info quantity">                                                                                                               
                                                        <button type="button" id="subs" class="btn btn-outline-dark btn-increment-decrement"><i class="fas fa-minus"></i></button>
                                                        <?php
                                                        $quantity = $cart->quantity();
                                                        $nc = $quantity->rowCount();
                                                        if ($nc > 0) {
                                                            // return values
                                                            $rowq = $quantity->fetch(PDO::FETCH_ASSOC);
                                                            $qp = $rowq['cantidad'];

                                                            echo '<input class="btn btn-outline-dark input-quantity" id="cantidad" name="cantidad" value="' . $qp . '" maxlength="4" size="4">';
                                                        } else {
                                                            echo '<input class="btn btn-outline-dark input-quantity" id="cantidad" name="cantidad" value="1" maxlength="4" size="4">';
                                                        }
                                                        ?>

                                                        <button type="button" id="adds" class="btn btn-outline-dark btn-increment-decrement"><i class="fas fa-plus"></i></button> 
                                                        <?php
                                                        if (isset($qp)) {
                                                            $total_amount = $prec * $qp;
                                                            echo '<span class="btn btn-outline-heart" style="font-size:100%; font-weight: bold;">S/. ' . $total_amount . '</span>';
                                                        }
                                                        ?>

                                                    </div>                                                 
                                                </form>
                                                <hr>
                                                <p>
                                                    <strong>Descripción:</strong> <?= $prow['descripcion'] ?><br />   
                                                    <strong>Detalles:</strong> <?= $prow['detalle_producto'] ?><br />                                                                                                    
                                                    <?php
                                                    if (!empty($prow['modelo'])) {
                                                        echo '<b>Modelo:</b>' . $prow['modelo'] . '<br />';
                                                    }
                                                    if (!empty($prow['medidas'])) {
                                                        echo '<b>Tamaño:</b>' . $prow['medidas'] . '<br />';
                                                    }
                                                    if (!empty($prow['peso'])) {
                                                        echo '<b>Peso:</b> ' . $prow['peso'] . '<br />';
                                                    }
                                                    if (!empty($prow['contenido'])) {
                                                        echo '<b>Contenido:</b> ' . $prow['contenido'];
                                                    }
                                                    ?>        
                                                </p> 
                                                <p><strong>Referencias:</strong><br />
                                                    <?php
                                                    if (!empty($prow['referencia_1'])) {
                                                        echo '<span class="text-primary"><b>' . $prow['referencia_1'] . '</b></span><br />';
                                                    }
                                                    if (!empty($prow['referencia_2'])) {
                                                        echo '<b>' . $prow['referencia_2'] . '</b><br />';
                                                    }
                                                    if (!empty($prow['referencia_3'])) {
                                                        echo '<b>' . $prow['referencia_3'] . '</b><br />';
                                                    }
                                                    if (!empty($prow['referencia_4'])) {
                                                        echo '<b>' . $prow['referencia_4'] . '</b>';
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <h5>Recomendados</h5>
                        </div>
                    </div>                    
                </div>
            </section>
            <section class="bg-contrast py-2">
                <div class="container-fluid">
                    <div class="row content-light">
                        <div class="col-md-3 py-2"><h4>Categorias</h4>
                            <?php include 'nav_categories.php'; ?>
                        </div>
                        <div class="col-md-9 py-2">
                            <h4>Informacióm del producto</h4>
                            <ul class="nav nav-tabs" id="infoproduct" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tb1" role="tab" aria-controls="tb1" aria-selected="true">Caracteristicas</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tb2" role="tab" aria-controls="tb2" aria-selected="false">Detalles</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="messages-tab" data-toggle="tab" href="#tb3" role="tab" aria-controls="tb3" aria-selected="false">Contenido</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#tb4" role="tab" aria-controls="tb4" aria-selected="false">Especificaciones</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="tb1" role="tabpanel" aria-labelledby="tb1-tab">
                                    <div class="p-2">
                                        <p><?= $prow['informacion_producto'] ?></p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tb2" role="tabpanel" aria-labelledby="tb2-tab">
                                    <div class="p-2">
                                        <p>
                                            <?php
                                            if (!empty($prow['garantia'])) {
                                                echo '<b>Garantia:</b> ' . $prow['garantia'] . '<br /> ';
                                            }
                                            if (!empty($prow['condiciones'])) {
                                                echo '<b>Condiciones:</b> ' . $prow['condiciones'] . '<br />';
                                            }
                                            ?>
                                            <?= $prow['contenido'] . '<br />'; ?>
                                            <?= $prow['medidas'] . '<br />'; ?>
                                            <?= $prow['ancho'] . '<br />'; ?>
                                            <?= $prow['altura'] . '<br />'; ?>
                                            <?= $prow['longitud'] . '<br />'; ?>
                                            <?= $prow['peso'] . '<br />'; ?>
                                            <?= $prow['color'] . '<br />'; ?>
                                            <?= $prow['volumen'] . '<br />'; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tb3" role="tabpanel" aria-labelledby="tb3-tab">
                                    <div class="p-2">
                                        <p><?= $prow['informacion_producto'] ?></p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tb4" role="tabpanel" aria-labelledby="tb4-tab">
                                    <div class="p-2">
                                        <p><?= $prow['informacion_producto'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <script>
                var slideIndex = 1;
                showSlides(slideIndex);

                function plusSlides(n) {
                    showSlides(slideIndex += n);
                }

                function currentSlide(n) {
                    showSlides(slideIndex = n);
                }

                function showSlides(n) {
                    var i;
                    var slides = document.getElementsByClassName("mySlides");
                    var dots = document.getElementsByClassName("demo");

                    if (n > slides.length) {
                        slideIndex = 1;
                    }
                    if (n < 1) {
                        slideIndex = slides.length;
                    }
                    for (i = 0; i < slides.length; i++) {
                        slides[i].style.display = "none";
                    }
                    for (i = 0; i < dots.length; i++) {
                        dots[i].className = dots[i].className.replace(" active", "");
                    }
                    slides[slideIndex - 1].style.display = "block";
                    dots[slideIndex - 1].className += " active";

                }
            </script>
            <script>
                function increment_quantity(id) {
                    var inputQuantityElement = $("#input-quantity-" + id);
                    var newQuantity = parseInt($(inputQuantityElement).val()) + 1;
                    save_to_db(code, newQuantity);
                }

                function decrement_quantity(code) {
                    var inputQuantityElement = $("#input-quantity-" + id);
                    if ($(inputQuantityElement).val() > 1)
                    {
                        var newQuantity = parseInt($(inputQuantityElement).val()) - 1;
                        save_to_db(code, newQuantity);
                    }
                }
            </script>
            <script>
                $('#adds').click(function add() {
                    var $prods = $("#cantidad");
                    var a = $prods.val();

                    a++;
                    $("#subs").prop("disabled", !a);
                    $prods.val(a);
                });
                $("#subs").prop("disabled", !$("#cantidad").val());

                $('#subs').click(function subst() {
                    var $prods = $("#cantidad");
                    var b = $prods.val();
                    if (b >= 1) {
                        b--;
                        $prods.val(b);
                    } else {
                        $("#subs").prop("disabled", true);
                    }
                });
                $('input').arrowIncrement({
                    formatFn: function (value) {
                        return '$' + value.toFixed(2);
                    }
                });
            </script>

            <?php
        } else {
            header('Location: ' . SITE_PATH . 'products');
            exit;
        }
    } else {
        header('Location: ' . SITE_PATH . 'products');
        exit;
    }
    ?>


