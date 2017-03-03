<?php
class Choice {
  public $id;
  public $answer;
  public $idStep;
  public $transitionText;
  public $idNextStep;
  public static $table = "Choice";

  public function __construct($answer,$idStep,$transitionText,$idNextStep){
   $this->answer = $answer;
   $this->idStep = $idStep;
   $this->transitionText = $transitionText;
   $this->idNextStep = $idNextStep;

  }
  /**
   * [arrayMap description]
   * @param  [type] $entry [description]
   * @param  [string] $key   [champ de la table, si int le tableau retourné aura des index numériques incrémentés à partir de 0]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function arrayMap($entry, $key, $value) {
    $map = array();
    foreach($entry as $data){
      //var_dump($data);
      $map = array_merge($map, array($data[$key]=>$data[$value]));
    }
    return $map;
  }

  public function save() {
     $entries = array(
       "IDStep" => $this->idStep,
       'Answer' => $this->answer,
       'TransitionText' => $this->transitionText,
       'IDNextStep' => $this->idNextStep
     );
     $id = Database::instance()->insert(self::$table, $entries);
     //$id = Database::instance()->query(self::$table, array("IDChoice" =>"", "Login" =>$this->login));
     var_dump($id);
     $this->id = $id[0]['IDChoice'];
     return $id[0]['IDChoice'];
   }

   public function update($answer = NULL, $IdStep = NULL, $transitionText = NULL, $idNextStep = NULL) {
     $entries = array(
       "IDChoice" => $this->id,
       "IDStep" => $idStep,
       'TransitionText' => $transitionText,
       'Answer' => $answer,
       'IDNextStep' => $idNextStep
     );
     Database::instance()->update(self::$table, $entries);
   }

   public function delete() {
     $entries = array(
       "IDChoice" => $this->id
     );
     $table = "Step";
     Database::instance()->delete($table, $entries);
     $table = "StatAlteration";
     Database::instance()->delete($table, $entries);
     $table = "StatsRequirement";
     Database::instance()->delete($table, $entries);
     $table = "Earn";
     Database::instance()->delete($table, $entries);
     $table = "Lose";
     Database::instance()->delete($table, $entries);
     $table = "ItemRequirement";
     Database::instance()->delete($table, $entries);

     Database::instance()->delete(self::$table, $entries);
   }

  /*
  @function alterPlayer
  @param  $player  joueur courant
  @return $bool faux si erreur, vrai si ok
  Maj les stats du joueur (perdues et gagnées), ajoute les items gagnés et enlève les items recquis
  */
  public function alterPlayer($player){
   try {
       $database = Database::instance();
       //on récupère les stats recquises
       $tables = array(
           self::$table => "Choice.ID",
           "StatsRequirement" => "StatsRequirement.IDChoice"
       );
       $requiried_stats = $db->query($tables,array("Choice.ID"=>"$this->id","StatsRequirement.*" => ""));

       //on récupère les items gagnés
       $tables = array(
           self::$table => "Choice.ID",
           "Earn" => "Earn.IDChoice"
       );
       $new_items = $db->query($tables,array("Choice.ID"=>"$this->id","Earn.*" => ""));

       //on récupère les items perdus
       $tables = array(
           self::$table => "Choice.ID",
           "ItemsRequirement" => "ItemsRequirement.IDChoice"
       );
       $requiried_items = $db->query($tables,array("Choice.ID"=>"$this->id","ItemsRequirement.*" => ""));

       var_dump($requiried_stats);
       var_dump($requiried_items);
       var_dump($new_items);

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
    $tables = array(self::$table=>"Choice.IDChoice");
    $choiceAnswer = Database::instance()->query($tables,array("Choice.Answer"=>"","Choice.IDChoice"=>$this->id));
    return $choiceAnswer == $answer ? true:false;
  }
  /*
  @function checkPlayerRequirements
  @param  $player joueur courant
  @return $bool false si le choix est impossible, vrai sinon
  Check si un joueur a le droit de faire un choix (items recquis, stats recquises par le choix)
  */
  public function checkPlayerRequirements($player){
    var_dump("STAT");
    $player_stats =  $player->stats();
    $tables = array(
       array(
         self::$table => "Choice.IDChoice",
         "StatRequirement" => "StatRequirement.IDChoice",
       ),
       array(
         "StatRequirement" => "StatRequirement.IDStat",
         "Stat" => "Stat.IDStat"
       )
    );
    $statsQuery = Database::instance()->query($tables,array("Choice.IDChoice"=>"$this->id","StatRequirement.Value" => "", "Stat.Name"=>""));

    $requiried_stats = $this->arrayMap($statsQuery, 'Name', 'Value');
    echo "PLayer stat <br />";
    var_dump($player_stats);
    echo "Choice stat <br />";
    var_dump($requiried_stats);

    foreach ($requiried_stats as $key => $value) {
      if(!isset($player_stats["$key"]) || $value>$player_stats["$key"])
        var_dump("FALSE STATS");
    }

    var_dump("ITEEEEEEEEEM");

    $player_items =  $player->items();
    $tables = array(
       array(
         self::$table => "Choice.IDChoice",
         "ItemRequirement" => "ItemRequirement.IDChoice",
       ),
       array(
         "ItemRequirement" => "ItemRequirement.IDItem",
         "Item" => "Item.IDItem"
       )
    );

    $itemsQuery = Database::instance()->query($tables,array("Choice.IDChoice"=>"$this->id","ItemRequirement.quantity" => "","Item.Name"));
    //var_dump($itemsQuery);
    $requiried_items = $this->arrayMap($itemsQuery, 'Name', 'quantity');

    var_dump($player_items);
    var_dump($requiried_items);

    foreach ($requiried_items as $key => $value) {
      if(!isset($player_items["$key"]) || $value>$player_items["$key"])
        var_dump("FALSE ITEMS");
    }
    return true;
  }
}
 ?>
