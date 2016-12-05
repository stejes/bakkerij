<?php

require_once 'bootstrap.php';

use Steven\Eindtest\Business\ProductService;
use Steven\Eindtest\Business\CartlineService;
use Steven\Eindtest\Entities\Product;
use Steven\Eindtest\Entities\Cartline;

session_start();
//print "blabla";
if (isset($_SESSION["email"])) {

    if (isset($_POST["orderAdd"])) {
        $productSvc = new ProductService();
        $product = $productSvc->getById($_POST["product"]);
        $id = $product->getId();
        if (!isset($_SESSION["cartlines"])) {
            $_SESSION["cartlines"] = array();
        } else if (isset($_SESSION["cartlines"][$id])) {
            $_SESSION["cartlines"][$id]->upAmount($_POST["amount"]);
        } else {
            $cartlineSvc = new CartlineService();
            $cartline = $cartlineSvc->addCartline($product, $_POST["amount"]);
            $_SESSION["cartlines"][$id] = $cartline;
        }


        $view = $twig->render("orderForm.twig", array("cartlines" => $cartlineList));
        print($view);
    } else {
        header("location: login.php");
        exit(0);
    }

    