<?php
use \Server\Router;
use \Server\Autoloader;
use \Server\RouterException;
use \Server\Session;

define('DS', DIRECTORY_SEPARATOR); // meilleur portabilité sur les différents systèmes (pour l'Autoloader)
define('ROOT', dirname(__FILE__).DS); // pour récupérer le chemin du dossier actuel (pour l'Autoloader)

try {
  require 'Server/Autoloader.php';
  Autoloader::register();
  Session::setSession();
  Router::init();
  Router::setRoutes("Server/Routes.json");
  $_SESSION["userid"] = 2;
  Router::run();
} catch (RouterException $error) {
  $error->send();
}


?>
