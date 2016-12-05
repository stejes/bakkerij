<?php

session_start();
require_once 'bootstrap.php';
//print "blabla";

$view = $twig->render("loginForm.html.twig", array());
print($view);

