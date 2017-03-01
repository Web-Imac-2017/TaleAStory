<?php
class Choice {
  public $id;

  public function __construct($id){
   $this->id = $id;
   //insert

  }
  public function alterPlayer($player){
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

    $tables = array(
      array(
        "Choice" => "Choice.ID",
        "Earn" => "Earn.IDChoice"
      ),
      array(
        "Earn" => "Earn.IDItem",
        "Item" => "Item.ID"
      )
    );
    $new_items = $db->query($tables,array("Choice.ID"=>"$this->id","Item.*" => ""));

    $tables = array(
      array(
        "Choice" => "Choice.ID",
        "ItemsRequirement" => "ItemsRequirement.IDChoice"
      ),
      array(
        "ItemsRequirement" => "ItemsRequirement.IDItem",
        "Item" => "Item.ID"
      )
    );
    $requiried_items = $db->query($tables,array("Choice.ID"=>"$this->id","Item.*" => ""));

    $player->alterStats($requiried_stats);
    $player->removeItems($requiried_items);
    $player->addItems($new_items);
  //return bool
  }
  public function checkAnswer($answer){
  //return bool
  }
  public function checkPlayerRequirements($player){
    $db = new Database("../../../TaleAStory/src/php/database_config.json");
    var_dump("STAT");
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
    echo "PLayer stat <br />";
    var_dump($player_stats);
    echo "Choice stat <br />";
    var_dump($requiried_stats);

    if(!empty($requiried_stats)){
      foreach ($player_stats as $key => $value) {
        if($value<$requiried_stats["$key"])
          return false;
      }
    }
    var_dump("ITEEEEEEEEEM");

    $player_items =  $player->items();
    $tables = array(
      array(
        "Choice" => "Choice.ID",
        "ItemsRequirement" => "ItemsRequirement.IDChoice"
      ),
      array(
        "ItemsRequirement" => "ItemsRequirement.IDItem",
        "Item" => "Item.ID"
      )
    );
    $requiried_items = $db->query($tables,array("Choice.ID"=>"$this->id","Item.*" => ""));
    var_dump($player_items);
    var_dump($requiried_items);

    if(!empty($requiried_items)){
      foreach ($player_items as $key => $value) {
        if($value<$requiried_items["$key"])
          return false;
      }
      return true;
    }
  }
}
 ?>
