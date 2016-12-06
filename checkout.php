<?php

session_start();
require_once 'bootstrap.php';
//print "blabla";

if (isset($_SESSION["email"])) {

    if (isset($_SESSION["cart"])) {
        $cart = unserialize($_SESSION["cart"]);
        if (isset($_POST["confirmSubmit"])) {
            $orderSvc = new OrderService();
            $orderSvc->confirm($cart);
            header("location: orders.php");
            exit(0);
        }
        $view = $twig->render("checkout.twig", array("cart" => $cart));
    } else {
        $view = $twig->render("checkout.twig", array());
    }
    print($view);
} else {
    header("location: login.php");
}

