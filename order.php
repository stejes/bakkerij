<?php


require_once 'bootstrap.php';

use Steven\Eindtest\Business\ProductService;
use Steven\Eindtest\Business\CartlineService;
session_start();
//print "blabla";
if (isset($_SESSION["email"])) {

    if (isset($_POST["orderAdd"])) {
        $productSvc = new ProductService();
        $product = $productSvc->getById($_POST["product"]);
        $cartlineSvc = new CartlineService();
        $cartlineSvc->addCartline($product, $_POST["amount"]);
        $cartlineList = $cartlineSvc->getAll();
        /*print "\n cartline: ";
            print_r($cartline);
        if (!isset($_SESSION["cartlines"])) {
            $_SESSION["cartlines"] = array();
        }
        array_push($_SESSION["cartlines"], $cartline);*/
    }/*
    $cartlines = $_SESSION["cartlines"];
     print "\n cartlines: ";
    print_r($cartlines);
    $view = $twig->render("orderForm.twig", array("cartlines" => $cartlines));*/
        $view = $twig->render("orderForm.twig", array("cartlines" => $cartlineList));
    print($view);
} else {
    header("location: login.php");
    exit(0);
}

