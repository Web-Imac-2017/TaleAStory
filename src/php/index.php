<?php
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
