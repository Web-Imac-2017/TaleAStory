<?php

require_once('/Server/Database.php');
require_once('/Model/Player.php');
require_once('/Model/Choice.php');

use \Model\Player;
use \Server\Database;
use \Model\Choice;

$Lou = Player::connect("marcel", "inconnus");
$c = new Choice("42",1,"transition text",2);
$c->save();
var_dump($cId);
var_dump($Lou);

$step = new Step("45","body","question",1);
$step->save();
var_dump($step);

//step->processAnswer($Lou,"toto");
