<?php

session_start();
require_once 'bootstrap.php';
//print "blabla";
$isLoggedIn = false;
$cart = null;

if(isset($_SESSION["email"])){
    $isLoggedIn = true;
}
if (isset($_SESSION["cart"])) {
        $cart = unserialize($_SESSION["cart"]);
    }
$view = $twig->render("index.twig", array("isLoggedIn" => $isLoggedIn, "cart" => $cart));
print($view);

