<?php

session_start();
require_once 'bootstrap.php';
//print "blabla";

$view = $twig->render("account.twig", array());
print($view);

