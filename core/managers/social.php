<div class="container">            
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 py-4">
                    <?php
                    if ($session->isAdmin()) {
                        if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {
                            $valueCount = count($_POST["social_name"]);
                            for ($i = 0; $i < $valueCount; $i++) {
                                $stmt = $conn->prepare("UPDATE `social_link` SET  `social_url` =  :social_url   WHERE `social_name` = :social_name ");
                                $stmt->execute([':social_url' => $_POST['social_url'][$i], ':social_name' => $_POST['social_name'][$i]]);
                            }
                        }
                        ?>                
                        <h3>Administrar Redes Sociales</h3> 
                        <form method='POST'>
<?php echo \PepiuoX\Security\Csrf::field(); ?> 
                            <?php
                            echo "<table class='table table-striped table-sm'>";
                            echo "<thead>";
                            echo "<tr class=title>";
                            echo "<th><b>Red Social</b></th>";
                            echo "<th><b>Url Personal</b></th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            // ✅ PDO: sin "or trigger_error($conn->error)" (PDO lanza PDOException).
                            $result = $conn->query("SELECT * FROM `social_link`");
                            while ($row = $result->fetch(PDO::FETCH_BOTH)) {
                                foreach ($row AS $key => $value) {
                                    $row[$key] = stripslashes($value);
                                }
                                echo "<tr>";
                                echo "<td valign='top'><input type='text' name='social_name[]' id='social_name' value='" . $row['social_name'] . "' readonly /></td>";
                                echo "<td valign='top'><input type='text' name='social_url[]' id='social_url' value='" . $row['social_url'] . "' /></td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "<tfoot>";
                            echo "<tr class=title>";
                            echo "<th><b>Red Social</b></th>";
                            echo "<th><b>Url Personal</b></th>";
                            echo "</tr>";
                            echo "</tfoot>";
                            echo "</table>";
                            ?>                     
                            <div class="mb-3"><input class="button" type='submit' value='Editar Redes Sociales' /><input type='hidden' value='1' name='submitted' /></div> 
                        </form> 
                    <?php }
                    ?> 
                </div>
            </div>
        </div>
    </div>
</div>
