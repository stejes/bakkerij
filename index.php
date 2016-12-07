<?php

session_start();
require_once 'bootstrap.php';
//print "blabla";
$isLoggedIn = false;

if(isset($_SESSION["email"])){
    $isLoggedIn = true;
}
$view = $twig->render("index.twig", array("isLoggedIn" => $isLoggedIn));
print($view);

