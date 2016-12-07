<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\OrderService;
use Steven\Eindtest\Exceptions\DateOutOfBoundsException;
$isLoggedIn = false;
$error = null;
//print "blabla";

if (isset($_SESSION["email"])) {
    $cart = null;
    $isLoggedIn = true;
    if (isset($_SESSION["cart"])) {
        $cart = unserialize($_SESSION["cart"]);
        if (isset($_POST["confirmSubmit"])) {
            try{
            $orderSvc = new OrderService();
            $orderSvc->confirm($cart, $_SESSION["email"], $_POST["date"]);
            unset($_SESSION["cart"]);
            header("location: orders.php");
            exit(0);
            }catch(DateOutOfBoundsException $ex){
                $error = "Datum moet na vandaag zijn en maximum drie dagen in de toekomst.";
            }
        }
    }
    $view = $twig->render("checkout.twig", array("cart" => $cart, "isLoggedIn" => $isLoggedIn, "error" => $error));

    print($view);
} else {
    header("location: login.php");
}

