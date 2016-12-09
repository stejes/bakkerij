<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\UserService;
use Steven\Eindtest\Business\CityService;
use Steven\Eindtest\Exceptions\WrongPasswordException;
use Steven\Eindtest\Exceptions\PasswordsDontMatchException;
use Steven\Eindtest\Exceptions\EmptyFieldsException;
use Steven\Eindtest\Exceptions\InvalidFieldsException;

//print "blabla";
$isLoggedIn = false;
$passwordString = null;
$error = null;
$success = null;
$cart = null;
if (isset($_SESSION["email"])) {
    $isLoggedIn = true;
    /* haal city en user op voor formulier */
    $citySvc = new CityService();
    $cityList = $citySvc->getAll();
    $userSvc = new UserService();
    $user = $userSvc->getByEmail($_SESSION["email"]);

    /* bij eerste inlog na registreren toon gegenereerd paswoord */
    if (isset($_SESSION["password"])) {
        $passwordString = $_SESSION["password"];
        unset($_SESSION["password"]);
    }

    /* bij wijzigen gegevens */
    if (isset($_POST["accountSubmit"])) {
        try {
            $userSvc->editData($_SESSION["email"], $_POST["firstname"], $_POST["name"], $_POST["address"], $_POST["city"]);
            $success = "datasuccess";
        } catch (EmptyFieldsException $ex) {
            $error = "emptyfield";
        } catch (InvalidFieldsException $ex) {
            $error = "invalidfield";
        }
        /* bij wijzigen paswoord */
    } else if (isset($_POST["passwordSubmit"])) {
        try {
            $userSvc->editPassword($_SESSION["email"], $_POST["oldpassword"], $_POST["password"], $_POST["password2"]);
            $success = "passsuccess";
        } catch (WrongPasswordException $ex) {
            $error = "wrong pass";
        } catch (PasswordsDontMatchException $ex) {
            $error = "passwordmatch";
        }
    }

    if (isset($_SESSION["cart"])) {
        $cart = unserialize($_SESSION["cart"]);
    }
    $view = $twig->render("account.twig", array("user" => $user, "cityList" => $cityList, "isLoggedIn" => $isLoggedIn, "password" => $passwordString, "error" => $error, "success" => $success, "cart" => $cart));
    print($view);
} else {
    header("location: login.php");
    exit(0);
}



