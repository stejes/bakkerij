<?php

require_once 'bootstrap.php';

use Steven\Eindtest\Business\ProductService;
use Steven\Eindtest\Business\CartService;
use Steven\Eindtest\Entities\Product;
use Steven\Eindtest\Entities\Cartline;

session_start();
//print "blabla";
if (isset($_SESSION["email"])) {

    if(isset($_POST["cancelSubmit"])){
        unset($_SESSION["cart"]);
    }
    if (isset($_POST["orderAdd"])) {
        $productSvc = new ProductService();
        $product = $productSvc->getById($_POST["product"]);
        $amount = $_POST["amount"];
        /*print "<br>";
        print_r($product);
        print "<br>";*/

        if (!isset($_SESSION["cart"])) {
            $cart = new CartService();
            $_SESSION["cart"] = serialize($cart);
        }

        $cart = unserialize($_SESSION["cart"]);
        $cart->addLine($product, $amount);
        $_SESSION["cart"] = serialize($cart);
    }
    if (isset($_SESSION["cart"])) {
        $cart = unserialize($_SESSION["cart"]);

        //$_SESSION["cart"] = serialize($cart);
        //print_r($cart);
        $view = $twig->render("orderForm.twig", array("cart" => $cart));
    } else {
        $view = $twig->render("orderForm.twig", array());
    }

    print($view);
} else {
    header("location: login.php");
    exit(0);
}
    

    