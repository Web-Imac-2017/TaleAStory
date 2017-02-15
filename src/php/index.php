<?php

  require 'module_database.php';
  //$test = '"matisse","date" => "1904"';

  $db = new Database("../../../TaleAStory/src/php/database_config.json");
  $tables = array ("tableaux");
  $entries = array (
    "artiste" => "",
    "date" => "1904",
    "titre" => ""
  );
  $data = $db->query($tables, $entries);
  for ($i=0; $i<count($data); $i++) {
    echo '<p>test bdd :'.$data[$i]['titre'].'</p>';
  }


  //require '../index.html';


?>
