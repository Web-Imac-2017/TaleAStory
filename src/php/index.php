<?php

  require 'module_database.php';
  //$test = '"matisse","date" => "1904"';

  $db = new Database("../../../TaleAStory/src/php/database_config.json");
  $tables = array ("tableaux");
  $table = "tableaux";
  $entries_update = array (
    "titre" => "Lady Cas",
    "description" => "Un superbe dessin de Castiel occupant le corps de mamie Novak"
  );
  $entries_query = array(
    "titre" => "La Joconde",
    "artiste" => "",
    "date" => ""
  );
  $identification = array (
    "ID" => 5
  );
  //$db->insert($table, $entries_insert);
  $db->update($table, $entries_update, $identification);
  /*
  $data = $db->query($tables, $entries_query);
  for ($i=0; $i<count($data); $i++) {
    echo '<p>test bdd :'.$data[$i]['titre'].",".$data[$i]['artiste'].'</p>';
  }
  $pw= "plop";
  $crypt = $db->encode($pw);
  echo $crypt."\n";
  var_dump($db->decode($pw, $crypt));
  */




  //require '../index.html';


?>
