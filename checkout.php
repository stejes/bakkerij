<?php

session_start();
require_once 'bootstrap.php';
//print "blabla";

$view = $twig->render("checkout.twig", array());
print($view);

