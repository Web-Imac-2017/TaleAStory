<?php
class Choice {
  public $id;

  public function __construct($answer,$idStep){
   $this->id = $id;
   $table = "Choice";
   $entries = array(
     "ID" => "",
     "Answer" => $answer,
     "IDStep" => $idStep //return false si idStep non existant ?
   );
   $db->insert($table, $entries);

  }
  /*
  @function alterPlayer
  @param  $player  joueur courant
  @return $bool faux si erreur, vrai si ok
  Maj les stats du joueur (perdues et gagnées), ajoute les items gagnés et enlève les items recquis
  */
  public function alterPlayer($player){
   try {
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
    }catch (RuntimeException $e) {
        echo $e->getMessage();
        return false;
    }
    return true;
  }
  /*
  @function checkAnswer
  @param  $answer
  @return $bool
  check si l'answer donnée par le joueur correspond bien à une answer du choix (dans la bd)
  */
  public function checkAnswer($answer){
    $tables = array("Choice"=>"Choice.ID");
    $choiceAnswer = $bd->query($tables,array("Choice.Answer"=>""));
    return $choiceAnswer == $answer ? true:false;
  }
  /*
  @function checkPlayerRequirements
  @param  $player joueur courant
  @return $bool false si le choix est impossible, vrai sinon
  Check si un joueur a le droit de faire un choix (items recquis, stats recquises par le choix)
  */
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
