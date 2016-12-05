<?php
//bootstrap.php
use Doctrine\Common\ClassLoader;
require_once("Doctrine/Common/ClassLoader.php");
$classLoader = new ClassLoader("Steven", "src");
$classLoader->register();
//meer Doctrine-specifieke code


//voor twig
require_once("Libraries/Twig/Autoloader.php");
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("src/Steven/Eindtest/Presentation");
$twig = new Twig_Environment($loader);