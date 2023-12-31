<?php

if (isset($_SESSION["client_session"]) && !empty($_SESSION["client_session"])) {
    // add items
    $cant = $_POST["cantidad"];
    $secl = $_SESSION["client_session"];
    // check if client session exists
    if (isset($_SESSION["client_id"]) && !empty($_SESSION["client_id"])) {
        $client = $_SESSION["client_id"];
        $cartses = $dbprd->prepare(
            "INSERT INTO canasta_articulos SET session_key= :session_key, producto_id= :producto_id, cantidad= :cantidad, cliente_id= :cliente_id"
        );

        $secl = htmlspecialchars(strip_tags($secl));
        $id = htmlspecialchars(strip_tags($id));
        $cant = htmlspecialchars(strip_tags($cant));
        $client = htmlspecialchars(strip_tags($client));

        $cartses->bindParam(":session_key", $secl);
        $cartses->bindParam(":producto_id", $id);
        $cartses->bindParam(":cantidad", $cant);
        $cartses->bindParam(":cliente_id", $client);

        $cartses->execute();
    } else {
        // check if session exists
        $cartses = $dbprd->prepare(
            "INSERT INTO canasta_articulos SET session_key= :session_key, producto_id= :producto_id, cantidad= :cantidad"
        );

        $secl = htmlspecialchars(strip_tags($secl));
        $id = htmlspecialchars(strip_tags($id));
        $cant = htmlspecialchars(strip_tags($cant));

        $cartses->bindParam(":session_key", $secl);
        $cartses->bindParam(":producto_id", $id);
        $cartses->bindParam(":cantidad", $cant);
        $cartses->execute();
    }
} else {
}
?>