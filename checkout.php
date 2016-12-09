<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\OrderService;
use Steven\Eindtest\Exceptions\DateOutOfBoundsException;
use Steven\Eindtest\Exceptions\OrderExistsException;
use Steven\Eindtest\Business\UserService;

$isLoggedIn = false;
$error = null;

if (isset($_SESSION["email"])) {
    $cart = null;
    $isLoggedIn = true;
    /* als er items in winkelwagentje haal deze op */
    if (isset($_SESSION["cart"])) {
        $cart = unserialize($_SESSION["cart"]);
        /* na klik op bevestigen */
        if (isset($_POST["confirmSubmit"])) {
            $userSvc = new UserService();
            $user = $userSvc->getByEmail($_SESSION["email"]);
            /* check of user geblokkeerd is */
            if ($user->getIsBlocked()) {
                $error = "Uw account is geblokkeerd, contacteer de winkelverantwoordelijke";
            } else {
                /* bevestig de bestelling of catch exceptions */
                try {
                    $orderSvc = new OrderService();
                    $orderSvc->confirm($cart, $_SESSION["email"], $_POST["date"]);
                    unset($_SESSION["cart"]);
                    header("location: orders.php");
                    exit(0);
                } catch (DateOutOfBoundsException $ex) {
                    $error = "Datum moet na vandaag zijn en maximum drie dagen in de toekomst.";
                } catch (OrderExistsException $ex) {
                    $error = "Er is al een bestelling voor die datum.";
                }
            }
        }
    }
    $view = $twig->render("checkout.twig", array("cart" => $cart, "isLoggedIn" => $isLoggedIn, "error" => $error));

    print($view);
} else {
    header("location: login.php");
}

