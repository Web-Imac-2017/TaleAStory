<?php

  /*
  $pdo = new PDO('mysql:dbname=test;host=localhost', 'root', '', $options);
  $i = $pdo->query('SELECT COUNT(*) FROM test');
  var_dump($i->fetchColumn(0));
  */

  require 'module_database.php';

  $db = new Database("../../../TaleAStory/src/php/database_config.json");
  //var_dump($db);
  $testbuild = $db->buildQuery();
  $data = $db->sendQuery('SELECT * FROM tableaux');
  echo '<p>test bdd :'.$data['titre'].'</p>';


  //require '../index.html';


?>
