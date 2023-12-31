<?php


$path = 'http://localhost:83/concrete5-8.5.4/';
require 'addCart';

$dbprd = $dbconnect->PdoConnection('ecommerce');

// get the product id
$producto_id = isset($_GET['id']) ? $_GET['id'] : "";

// initialize objects
$cart_item = new addCart($dbprd);

// remove cart item from database
$cart_item->session_key = $_SESSION['client_session']; // we default to '1' because we do not have logged in user
$cart_item->producto_id = $producto_id;
$cart_item->deleteSession();

// redirect to product list and tell the user it was added to cart
$url = $path . 'cart?action=removed&id=' . $producto_id;
$r = new RedirectResponse($url);
$r->send();
exit;
?>













