<?php

  require 'module_database.php';
  //$test = '"matisse","date" => "1904"';

  $db = new Database("../../../TaleAStory/src/php/database_config.json");

  $tables = array(
    array(
      "tableaux" => "tableaux.artiste",
      "artistes" => "artistes.nom"
    )
    /*,
    array(
      "tableaux" => "tableaux.ID",
      "tableaux_themes" => "tableaux_themes.tableaux_id"
    ),
    array(
      "tableaux_themes" => "tableaux_themes.themes_id",
      "themes" => "themes.ID"
    )
    */
  );

  $table = "tableaux";
  $table = array("tableaux" => "");
  $entries_insert = array(
    "nom" => "iouii"
  );
  $entries_update = array (
    "tableaux.ID" => 5,
    "artistes.prenom" => "Lou"
  );
  $entries_query = array(
    "tableaux.titre" => "",
    "tableaux.artiste" => "matisse",
    "tableaux.date" => "1904",
    "artistes.prenom" => "",
    "themes.tag" => ""
  );
  $identification = array (
    "tableaux.ID" => 5,
    "tableaux.artiste" => "iouii"
  );
  $addEndStatement = "GROUP BY tableaux.titre";
  //$db->insert("artistes", $entries_insert);
  $db->update($tables, $entries_update, $identification);
  //$db->delete($table, $identification);
/*
  $data = $db->query($tables, $entries_query);
  echo '<pre>'.var_export($data, true).'</pre>';
  for ($i=0; $i<count($data); $i++) {
    echo '<p>oeuvre :'.$data[$i]['titre'].",".$data[$i]['artiste']." ".$data[$i]['prenom'].'</p>';
    echo '<p>themes :'.$data[$i]['tag'].'</p>';
  }
  */
  $pw= "plop";
  $crypt = $db->encode($pw);
  echo $crypt."\n";
  var_dump($db->decode($pw, $crypt));






  //require '../index.html';


?>
