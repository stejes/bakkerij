<?php

session_start();
require_once 'bootstrap.php';

//print "blabla";
use Steven\Eindtest\Data\CityDAO;
use Steven\Eindtest\Business\UserService;
use Steven\Eindtest\Exceptions\EmptyFieldsException;
use Steven\Eindtest\Exceptions\CustomerExistsException;

$cityDao = new CityDAO();
$cityList = $cityDao->getAll();

if (isset($_POST["registerSubmit"])) {
    print "register";
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
    }
} else if (isset($_POST["loginSubmit"])) {
    print "in eerste if";
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $userSvc = new UserService();
        $isValid = $userSvc->checkLogin($_POST["email"], $_POST["password"]);
        print "in tweede if";
        print_r($isValid);
        if ($isValid) {
            $_SESSION["email"] = $_POST["email"];
            header("location: order.php");
            exit(0);
        }
    }
}
//$view = $twig->render("loginForm.html.twig", array("cityList" => $cityList, "email" => $_POST["email"], "password" => $passwordString));
$view = $twig->render("loginForm.html.twig", array("cityList" => $cityList));
print($view);

