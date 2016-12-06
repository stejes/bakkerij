<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\OrderService;

//print "blabla";
if (isset($_SESSION["email"])) {
    $orderSvc = new OrderService();
    $orderList = $orderSvc->getOrders($_SESSION["email"]);
    $view = $twig->render("my_orders.twig", array("orderlist" => $orderList));
    print($view);
} else {
    header("location: login.php");
    exit(0);
}



