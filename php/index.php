<?php

  require 'Autoloader.php';
  Autoloader::register();
  Router::setJson("Routes.json");
  Router::run();


?>
