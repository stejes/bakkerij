<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\OrderService;

//print "blabla";
$isLoggedIn = false;
if (isset($_SESSION["email"])) {
    $isLoggedIn = true;
    
    if (isset($_POST["cancelOrderSubmit"])) {
        try{
        $orderSvc = new OrderService();
        $orderSvc->cancel($_POST["cancelOrderSubmit"]);
        }catch(NonExistingOrderException $ex){
            
        }catch(UnauthorizedException $ex){
            
        }
    }
    $orderSvc = new OrderService();
    $orderList = $orderSvc->getOrders($_SESSION["email"]);
    
    $view = $twig->render("my_orders.twig", array("orderlist" => $orderList, "isLoggedIn" => $isLoggedIn));
    print($view);
} else {
    header("location: login.php");
    exit(0);
}



