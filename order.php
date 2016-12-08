<?php

require_once 'bootstrap.php';

use Steven\Eindtest\Business\ProductService;
use Steven\Eindtest\Business\CartService;
use Steven\Eindtest\Entities\Product;
use Steven\Eindtest\Entities\Cartline;
use Steven\Eindtest\Exceptions\NonExistingProductException;
use Steven\Eindtest\Exceptions\AmountOutOfBoundsException;

session_start();
//print "blabla";
//print $_POST["deleteLineSubmit"];
$isLoggedIn = false;
$error = null;
if (isset($_SESSION["email"])) {
    $cart = null;
    $isLoggedIn = true;
    $productSvc = new ProductService();
    $productList = $productSvc->getAll();
    if (isset($_POST["cancelSubmit"])) {
        unset($_SESSION["cart"]);
    }
    if (isset($_POST["orderAdd"])) {
        if (isset($_POST["amount"]) && isset($_POST["product"])) {
            $productId = $_POST["product"];
            $amount = $_POST["amount"];


            if (!isset($_SESSION["cart"])) {
                $cart = new CartService();
                $_SESSION["cart"] = serialize($cart);
            }

            $cart = unserialize($_SESSION["cart"]);
            try {
                $cart->addLine($productId, $amount);
            } catch (AmountOutOfBoundsException $ex) {
                $error = "Ongeldige hoeveelheid";
            } catch (NonExistingProductException $ex) {
                $error = "Dit product bestaat niet";
            }
            $_SESSION["cart"] = serialize($cart);
        }
    }
    if (isset($_POST["deleteLineSubmit"])) {
        $cart = unserialize($_SESSION["cart"]);
        $cart->deleteLine($_POST["deleteLineSubmit"]);
        $_SESSION["cart"] = serialize($cart);
    }

    if (isset($_SESSION["cart"])) {
        $cart = unserialize($_SESSION["cart"]);

        //$_SESSION["cart"] = serialize($cart);
        //print_r($cart);
    }
    $view = $twig->render("orderForm.twig", array("cart" => $cart, "productList" => $productList, "isLoggedIn" => $isLoggedIn, "error" => $error));

    print($view);
} else {
    header("location: login.php");
    exit(0);
}
    

    