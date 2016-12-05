<?php

session_start();
require_once 'bootstrap.php';
//print "blabla";

$view = $twig->render("my_orders.twig", array());
print($view);

