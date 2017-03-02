<?php
class Choice {
  public $id;

  public function alterPlayer($player){
  //return bool
  }
  public function checkAnswer($answer){
  //return bool
  }
  public function checkPlayerRequirements($player){
    $db = new Database("../../../TaleAStory/src/php/database_config.json");
    $player_stats =  $player->stats();
    $tables = array(
      array(
        "Choice" => "Choice.ID",
        "StatsRequirement" => "StatsRequirement.IDChoice"
      ),
      array(
        "StatsRequirement" => "StatsRequirement.IDStat",
        "Stat" => "Stat.ID"
      )
    );
    $requiried_stats = $db->query($tables,array("Choice.ID"=>"$this->id","Stat.*" => ""));
    var_dump($player_stats);
    var_dump($requiried_stats);
  }
}
 ?>
