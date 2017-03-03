<?php

  require 'model_Player.php';
  require 'Choice.php';

  $Lou = Player::connect("marcel", "inconnus");

  $c = new Choice("42",1,"transition text",2);
  //var_dump($c);
  $cId = $c->save();
  var_dump($cId);
  $c->id=5;
  var_dump($c);
  var_dump($Lou);
  $c->checkPlayerRequirements($Lou);


?>
