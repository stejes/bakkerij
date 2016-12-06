<?php

session_start();
require_once 'bootstrap.php';

use Steven\Eindtest\Business\UserService;
use Steven\Eindtest\Business\CityService;

//print "blabla";

if (isset($_SESSION["email"])) {
    $citySvc = new CityService();
    $cityList = $citySvc->getAll();
    $userSvc = new UserService();
    $user = $userSvc->getByEmail($_SESSION["email"]);


    if (isset($_POST["accountSubmit"])) {
        $userSvc->editData($_SESSION["email"], $_POST["firstname"], $_POST["name"], $_POST["address"], $_POST["city"]);
    } else if (isset($_POST["passwordSubmit"])) {
        $userSvc->editPassword($_SESSION["email"], $_POST["oldpassword"], $_POST["password"], $_POST["password2"]);
    }
    $view = $twig->render("account.twig", array("user" => $user, "cityList" => $cityList));
    print($view);
} else {
    header:("location: login.php");
    exit(0);
}



