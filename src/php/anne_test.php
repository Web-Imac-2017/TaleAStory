<?php

  require 'model_joueur.php';
  require 'module_database.php';
  require 'Choice.php';

  $db = new Database("../../../TaleAStory/src/php/database_config.json");

  $Lou = new Joueur($db);
  $c = new Choice(1);

  $c->checkPlayerRequirements($Lou);


?>
