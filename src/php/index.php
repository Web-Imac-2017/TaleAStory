<?php

  require 'model_joueur.php';
  require 'module_database.php';
  $db = new Database("../../../TaleAStory/src/php/database_config.json");
  echo '<p>TEST</br></p>';

  //constructeur avec 4+ param = signup
  //$Lou = new Joueur($db,"masterloutre","willie","bloup","willie@");
  $db->insert("Player",array("Pseudo"=>"test"));
  //constructeur 1-3 param = connexion
  //$Tom = new Joueur($db,"willie","bloup");
  //constructeur sans param = instanciation à 0
  //$Tom = new Joueur($db);
  //possibilité d'appeler ensuite un fct de signup ou connexion
  //$Tom = $Tom->signup("masteroutre","willie","bloup","williejunpow@");
  var_dump($Lou->imgpath);

?>
