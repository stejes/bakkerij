<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\OrderService;

//print "blabla";
$isLoggedIn = false;
if (isset($_SESSION["email"])) {
    $isLoggedIn = true;
    $orderSvc = new OrderService();
    $orderList = $orderSvc->getOrders($_SESSION["email"]);
    $view = $twig->render("my_orders.twig", array("orderlist" => $orderList, "isLoggedIn" => $isLoggedIn));
    print($view);
} else {
    header("location: login.php");
    exit(0);
}



