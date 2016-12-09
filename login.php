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
use Steven\Eindtest\Exceptions\InvalidFieldsException;

$citySvc = new CityService();
$cityList = $citySvc->getAll();
$isLoggedIn = false;
$error = null;
$email = null;

if (isset($_POST["registerSubmit"])) {
    /* maak een voorlopig userobject om data in formulier te kunnen bewaren */
    $citySvc = new CityService();
    $city = $citySvc->getById($_POST["city"]);
    $user = User::create(0, $_POST["name"], $_POST["firstname"], $_POST["address"], $city, $_POST["email"], null, 0);
    
    /* probeer te registreren, geef voorlopig paswoord mee aan sessie bij succes en log user in */
    try {
        $userSvc = new UserService();
        $passwordString = $userSvc->registerUser($_POST["email"], $_POST["name"], $_POST["firstname"], $_POST["address"], $_POST["city"]);
        if ($passwordString) {
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["password"] = $passwordString;
            setcookie("email", $_SESSION["email"]);
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
    } catch (InvalidFieldsException $ex){
        $error = "invalidfield";
    }
    /* inloggen */
} else if (isset($_POST["loginSubmit"])) {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        try {
            $userSvc = new UserService();
            $isValid = $userSvc->checkLogin($_POST["email"], $_POST["password"]);
            if ($isValid) {
                $_SESSION["email"] = $_POST["email"];
                setcookie("email", $_SESSION["email"]);
                header("location: order.php");
                exit(0);
            }
        } catch (LoginFailedException $ex) {
            $error = "loginfailed";
        }
    }
    /* uitloggen */
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

$view = $twig->render("loginForm.html.twig", array("cityList" => $cityList, "isLoggedIn" => $isLoggedIn, "error" => $error, "user" => $user, "email" => $email));
print($view);

