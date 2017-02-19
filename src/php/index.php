<?php

  require 'Autoloader.php';
  Autoloader::register();

  $router = new Router($_GET['url']);
  $router->get('/', function($id){ echo "Bienvenue sur ma homepage !"; });
  $router->run();

?>
