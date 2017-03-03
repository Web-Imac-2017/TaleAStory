<?php

  require_once 'model_Player.php';
  require_once 'SpecificChoice.php';
  require_once 'Step.php';

  $Lou = Player::connect("marcel", "inconnus");
/*
  $c = new SpecificChoice("44",1,"transition text",2);
  $cId = $c->save();
  var_dump($c);
  var_dump($c->checkAnswer("44"));
  $c->checkPlayerRequirements($Lou);
  $c->delete();
*/
  $step = new Step("img","body","AM I CRAZY ?",1,4);
  $step->save();
  var_dump($step);
  /*$entries = array(
    'Question' => "AAAAAAAAAAAAAAAAAAAAH ?"
  );
  $step->update($entries);*/
  //var_dump($step->processAnswer($Lou,"J’ouvre les yeux"));
  $step->delete();

?>
