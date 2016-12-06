<?php

session_start();
require_once 'bootstrap.php';

//print "blabla";
use Steven\Eindtest\Business\CityService;
use Steven\Eindtest\Business\UserService;
use Steven\Eindtest\Exceptions\EmptyFieldsException;
use Steven\Eindtest\Exceptions\CustomerExistsException;
use Steven\Eindtest\Exceptions\LoginFailedException;

$citySvc = new CityService();
$cityList = $citySvc->getAll();
$isLoggedIn = false;
$error = null;
if (isset($_POST["registerSubmit"])) {
    //print "register";
    try {
        $userSvc = new UserService();
        $passwordString = $userSvc->registerUser($_POST["email"], $_POST["name"], $_POST["firstname"], $_POST["address"], $_POST["city"]);
        if ($passwordString) {
            $_SESSION["email"] = $_POST["email"];
        }
    } catch (EmptyFieldsException $ex) {
        header("location: login.php?error=emptyfields");
    } catch (CustomerExistsException $ex) {
        header("location: login.php?error=customerexists");
    } catch (NonExistingCityException $ex){
        header("location: login.php?error=cityerror");
    }
} else if (isset($_POST["loginSubmit"])) {
    //print "in eerste if";
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        try {
            $userSvc = new UserService();
            //$isValid = $userSvc->checkLogin($_POST["email"], $_POST["password"]);
           $userSvc->checkLogin($_POST["email"], $_POST["password"]);
            // print "in tweede if";
            //print $isValid;
            //if ($isValid) {
            $_SESSION["email"] = $_POST["email"];
            header("location: order.php");
            exit(0);
            //}
        } catch (LoginFailedException $ex) {
            header("location: login.php?error=loginfailed");
        }
    }
} else if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_unset();
    header("location: index.php");
}

if (isset($_SESSION["email"])) {
    $isLoggedIn = true;
}
if(isset($_GET["error"])){
    $error = $_GET["error"];
}
//$view = $twig->render("loginForm.html.twig", array("cityList" => $cityList, "email" => $_POST["email"], "password" => $passwordString));
$view = $twig->render("loginForm.html.twig", array("cityList" => $cityList, "isLoggedIn" => $isLoggedIn, "error" => $error));
print($view);

