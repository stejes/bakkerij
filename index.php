<?php

session_start();
require_once 'bootstrap.php';
//print "blabla";
$isLoggedIn = false;
if(isset($_SESSION["email"])){
    $isLoggedIn = true;
}
$view = $twig->render("loginForm.html.twig", array("isLoggedIn" => $isLoggedIn));
print($view);

