<?php
use Router\Router;
use Router\Route;
use Router\RouterException;

define('DS', DIRECTORY_SEPARATOR); // meilleur portabilité sur les différents systeme.
define('ROOT', dirname(__FILE__).DS); // pour se simplifier la vie


try {
  require 'Autoloader.php';
  Autoloader::register();
  Router::init();
  Router::setJson("Routes.json");
  Router::run();
} catch (RouterException $error) {
  $error->send();
}


?>
