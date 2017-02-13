<?php

  require 'module_database.php';

  $db = new Database("../../../TaleAStory/src/php/database_config.json");
  $tables = array ("tableaux");
  $entries = array (
    "titre" => "",
    "artiste" => "",
    "date" => "1904"
  );
  $data = $db->query($tables, $entries);
  for ($i=0; $i<count($data); $i++) {
    echo '<p>test bdd :'.$data[$i]['titre'].'</p>';
  }


  //require '../index.html';


?>
