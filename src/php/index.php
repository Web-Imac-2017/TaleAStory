<?php

  require 'model_joueur.php';

  echo '<p>TEST</br></p>';
  //constructeur avec 4+ param = signup
  $Tom = new Joueur("masterloutre","willie","bloup","willie@");
  //constructeur 1-3 param = connexion
  $Tom = new Joueur("willie","bloup");
  //constructeur sans param = instanciation à 0
  $Tom = new Joueur();
  //possibilité d'appeler ensuite un fct de signup ou connexion
  $Tom = $Tom->signup("masteroutre","willie","bloup","williejunpow@");
  echo $Tom->mail;
?>
