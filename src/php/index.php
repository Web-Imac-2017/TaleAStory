<?php
use \Server\Router;
use \Server\Autoloader;
use \Server\RouterException;

define('DS', DIRECTORY_SEPARATOR); // meilleur portabilité sur les différents systèmes (pour l'Autoloader)
define('ROOT', dirname(__FILE__).DS); // pour récupérer le chemin du dossier actuel (pour l'Autoloader)


try {
  require 'Server/Autoloader.php';
  Autoloader::register();
  Router::init();
  Router::setRoutes("Server/Routes.json");
  Router::run();
} catch (RouterException $error) {
  $error->send();
}
echo "test";


?>
