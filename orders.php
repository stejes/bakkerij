<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\OrderService;
use Steven\Eindtest\Exceptions\DateOutOfBoundsException;
use Steven\Eindtest\Exceptions\UnauthorizedException;
use Steven\Eindtest\Exceptions\NonExistingOrderException;

//print "blabla";
$isLoggedIn = false;
$error = null;
if (isset($_SESSION["email"])) {
    $isLoggedIn = true;
    
    if (isset($_POST["cancelOrderSubmit"])) {
        try{
        $orderSvc = new OrderService();
        $orderSvc->cancel($_POST["cancelOrderSubmit"]);
        }catch(NonExistingOrderException $ex){
            $error = "Dit order bestaat niet";
        }catch(UnauthorizedException $ex){
            $error = "Niet toegestaan";
        }catch(DateOutOfBoundsException $ex){
            $error = "Je kan ten laatste de dag zelf annuleren";
        }
    }
    $orderSvc = new OrderService();
    $orderList = $orderSvc->getOrders($_SESSION["email"]);
    
    $view = $twig->render("my_orders.twig", array("orderlist" => $orderList, "isLoggedIn" => $isLoggedIn, "error" => $error));
    print($view);
} else {
    header("location: login.php");
    exit(0);
}



