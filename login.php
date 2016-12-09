<?php

session_start();
require_once 'bootstrap.php';

//print "blabla";
use Steven\Eindtest\Entities\User;
use Steven\Eindtest\Business\CityService;
use Steven\Eindtest\Business\UserService;
use Steven\Eindtest\Exceptions\EmptyFieldsException;
use Steven\Eindtest\Exceptions\CustomerExistsException;
use Steven\Eindtest\Exceptions\LoginFailedException;
use Steven\Eindtest\Exceptions\NonExistingCityException;
use Steven\Eindtest\Exceptions\NotAnEmailException;

$citySvc = new CityService();
$cityList = $citySvc->getAll();
$isLoggedIn = false;
$error = null;
$email = null;
if (isset($_POST["registerSubmit"])) {
    //print "register";
    //$user = User::create(0, $_POST["name"], $_POST["firstname"], $_POST["address"], $_POST["city"], $_POST["email"], null, 0);
    //print_r($user);
    try {
        $userSvc = new UserService();
        $passwordString = $userSvc->registerUser($_POST["email"], $_POST["name"], $_POST["firstname"], $_POST["address"], $_POST["city"]);
        if ($passwordString) {
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["password"] = $passwordString;
            setcookie($email, $_SESSION["email"]);
            header("location: account.php");
            exit(0);
        }
    } catch (EmptyFieldsException $ex) {
        //header("location: login.php?error=emptyfields");
        $error = "emptyfields";
    } catch (CustomerExistsException $ex) {
        //header("location: login.php?error=customerexists");
        $error = "customerexists";
    } catch (NonExistingCityException $ex) {
        //header("location: login.php?error=cityerror");
        $error = "cityerror";
    } catch (NotAnEmailException $ex){
        $error = "notemail";
    }
} else if (isset($_POST["loginSubmit"])) {
    //print "in eerste if";
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        try {
            $userSvc = new UserService();
            //$isValid = $userSvc->checkLogin($_POST["email"], $_POST["password"]);
            $isValid = $userSvc->checkLogin($_POST["email"], $_POST["password"]);
            // print "in tweede if";
            //print $isValid;
            //if ($isValid) {
            if ($isValid) {
                $_SESSION["email"] = $_POST["email"];
                setcookie("email", $_SESSION["email"]);
                header("location: order.php");
                exit(0);
            }
            
            //}
        } catch (LoginFailedException $ex) {
            //header("location: login.php?error=loginfailed");
            $error = "loginfailed";
        }
    }
} else if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_unset();
    header("location: index.php");
}

if (isset($_SESSION["email"])) {
    $isLoggedIn = true;
}


if (!isset($user)) {
    $user = null;
}
if(isset($_COOKIE["email"])){
    $email = $_COOKIE["email"];
}
print_r($user);
//$view = $twig->render("loginForm.html.twig", array("cityList" => $cityList, "email" => $_POST["email"], "password" => $passwordString));
$view = $twig->render("loginForm.html.twig", array("cityList" => $cityList, "isLoggedIn" => $isLoggedIn, "error" => $error, "user" => $user, "email" => $email));
print($view);

