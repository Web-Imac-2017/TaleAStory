<?php

  require 'module_database.php';
  //$test = '"matisse","date" => "1904"';

  $db = new Database("../../../TaleAStory/src/php/database_config.json");

  $tables = array(
    array(
      "tableaux" => "tableaux.artiste",
      "artistes" => "artistes.nom"
    ),
    array(
      "tableaux" => "tableaux.ID",
      "tableaux_themes" => "tableaux_themes.tableaux_id"
    ),
    array(
      "tableaux_themes" => "tableaux_themes.themes_id",
      "themes" => "themes.ID"
    )
  );

  $table = "tableaux";
  $entries_update = array (
    "titre" => "Lady Castiel",
    "description" => "Un superbe dessin de Castiel occupant le corps de mamie Novak"
  );
  $entries_query = array(
    "tableaux.titre" => "",
    "tableaux.artiste" => "matisse",
    "tableaux.date" => "1904",
    "artistes.prenom" => "",
    "themes.tag" => ""
  );
  $identification = array (
    "ID" => 5,
    "artiste" => "iouii"
  );
  $groupBy = "tableaux.titre";
  //$db->insert($table, $entries_insert);
  //$db->update($table, $entries_update, $identification);
  //$db->delete($table, $identification);

  $data = $db->query($tables, $entries_query, $addEndStatement);
  var_dump($data);
  for ($i=0; $i<count($data); $i++) {
    echo '<p>oeuvre :'.$data[$i]['titre'].",".$data[$i]['artiste']." ".$data['prenom'].'</p>';
    echo '<p>themes :'.$data[$i]['tag'].'</p>';
  }
  $pw= "plop";
  $crypt = $db->encode($pw);
  echo $crypt."\n";
  var_dump($db->decode($pw, $crypt));






  //require '../index.html';


?>
