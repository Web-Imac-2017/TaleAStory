<?php
try {
  require 'Autoloader.php';
  Autoloader::register();
  Router::setJson("Routes.json");
  Router::run();

} catch (RouterException $e) {
  echo 'Exception reçue : ',  $e->message, "\n";
}


?>
