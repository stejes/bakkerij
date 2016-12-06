<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\UserService;
use Steven\Eindtest\Business\CityService;

//print "blabla";
$isLoggedIn = false;
$passwordString = null;
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
        $userSvc->editPassword($_SESSION["email"], $_POST["oldpassword"], $_POST["password"], $_POST["password2"]);
    }
    $view = $twig->render("account.twig", array("user" => $user, "cityList" => $cityList, "isLoggedIn" => $isLoggedIn, "password" => $passwordString));
    print($view);
} else {
    header("location: login.php");
    exit(0);
}



