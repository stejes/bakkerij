<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\UserService;
use Steven\Eindtest\Business\CityService;
use Steven\Eindtest\Exceptions\WrongPasswordException;
use Steven\Eindtest\Exceptions\PasswordsDontMatchException;

//print "blabla";
$isLoggedIn = false;
$passwordString = null;
$error = null;
if (isset($_SESSION["email"])) {
    $isLoggedIn = true;
    $citySvc = new CityService();
    $cityList = $citySvc->getAll();
    $userSvc = new UserService();
    $user = $userSvc->getByEmail($_SESSION["email"]);
    //print_r($user);
    if(isset($_SESSION["password"])){
        $passwordString = $_SESSION["password"];
        unset($_SESSION["password"]);
    }


    if (isset($_POST["accountSubmit"])) {
        $userSvc->editData($_SESSION["email"], $_POST["firstname"], $_POST["name"], $_POST["address"], $_POST["city"]);
    } else if (isset($_POST["passwordSubmit"])) {
        try{
        $userSvc->editPassword($_SESSION["email"], $_POST["oldpassword"], $_POST["password"], $_POST["password2"]);
        }catch(WrongPasswordException $ex){
            $error = "wrong pass";
        }catch(PasswordsDontMatchException $ex){
            $error = "passwordmatch";
        }
    }
    $view = $twig->render("account.twig", array("user" => $user, "cityList" => $cityList, "isLoggedIn" => $isLoggedIn, "password" => $passwordString, "error" => $error));
    print($view);
} else {
    header("location: login.php");
    exit(0);
}



