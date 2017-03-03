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

  /*
  @function save
  @return $id du choix créé, null si erreur
  Insère un nouveau choix en base de données
  */
  public function save() {
     $entries = array(
       "IDStep" => $this->idStep,
       'Answer' => $this->answer,
       'TransitionText' => $this->transitionText,
       'IDNextStep' => $this->idNextStep
     );
     Database::instance()->insert(self::$table, $entries);
     //SOLUTION TEMP POUR LES TEST - A MODIFIER lorque insert renverra directement l'ID
     $id = Database::instance()->query(self::$table, array("Answer" =>"$this->answer", "TransitionText" =>$this->transitionText,"IDNextStep"=>$this->idNextStep,"IDChoice"=>""));
     //var_dump($id);
     if($id != null){
       $this->id = $id[0]['IDChoice'];
       return $id[0]['IDChoice'];
     }
     return null;
   }
   /*
   @function update
   @param  $entries array de la forme : "Champ à modifier"=>"nouvelle valeur"
   @return void
   Maj un choix
   */
   public function update($entries) {
     //AFFICHE UN MESSAGE D'ERREUR MAIS FONCTIONNE QUAND MEME ??
     Database::instance()->update(self::$table, $entries, array("IDChoice"=>$this->id));
   }
   /*
   @function delete
   @return $bool faux si erreur, vrai si ok
   Supprime un choix donné, ainsi que ses "mentions" dans les tables qui sont liées aux choix
   */
   public function delete() {
     $entries = array(
       "IDChoice" => $this->id
     );
    try {
       $table = "StatAlteration";
        Database::instance()->delete($table, array("IDChoice"=>$this->id));
       $table = "StatRequirement";
        Database::instance()->delete($table, array("IDChoice"=>$this->id));
       $table = "Earn";
        Database::instance()->delete($table, array("IDChoice"=>$this->id));
       $table = "Lose";
        Database::instance()->delete($table, array("IDChoice"=>$this->id));
       $table = "ItemRequirement";
        Database::instance()->delete($table, array("IDChoice"=>$this->id));

       Database::instance()->delete(self::$table, $entries);
     }catch (RuntimeException $e) {
         echo $e->getMessage();
         return false;
     }
     return true;
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
          array(
            "StatRequirement" => "StatRequirement.IDStat",
            "Stat" => "Stat.IDStat"
          )
       );
       $statsQuery = Database::instance()->query($tables,array("StatRequirement.IDChoice"=>"$this->id","StatRequirement.Value" => "", "Stat.Name"=>""));
       $requiried_stats = $this->arrayMap($statsQuery, 'Name', 'Value');

       //on récupère les items gagnés
       $tables = array(
          array(
            "Earn" => "Earn.IDItem",
            "Item" => "Item.IDItem"
          )
       );
       $itemsQuery = Database::instance()->query($tables,array("Earn.IDChoice"=>"$this->id","Earn.quantity" => "","Item.Name"=>""));
       $earned_items = $this->arrayMap($itemsQuery, 'Name', 'quantity');

       //on récupère les items perdus
       $tables = array(
          array(
            "ItemRequirement" => "ItemRequirement.IDItem",
            "Item" => "Item.IDItem"
          )
       );
       $itemsQuery = Database::instance()->query($tables,array("ItemRequirement.IDChoice"=>"$this->id","ItemRequirement.quantity" => "","Item.Name"=>""));
       $requiried_items = $this->arrayMap($itemsQuery, 'Name', 'quantity');

       var_dump($requiried_stats);
       var_dump($requiried_items);
       var_dump($earned_items);

       //on modifie le joueur
       if(!empty($requiried_stats))
          $player->alterStats($requiried_stats);
       if(!empty($requiried_items))
          $player->removeItems($requiried_items);
       if(!empty($earned_items))
          $player->addItems($earned_items);

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
    $tables = array(self::$table=>"IDChoice");
    $choiceAnswer = Database::instance()->query($tables,array("Choice.Answer"=>"","IDChoice"=>$this->id));
    var_dump($choiceAnswer);
    return $choiceAnswer[0]['Answer'] == $answer ? true:false;
  }
  /*
  @function checkPlayerRequirements
  @param  $player joueur courant
  @return $bool false si le choix est impossible, vrai sinon
  Check si un joueur a le droit de faire un choix (items recquis, stats recquises par le choix)
  */
  public function checkPlayerRequirements($player){
    $player_stats =  $player->stats();
    $tables = array(
       array(
         "StatRequirement" => "StatRequirement.IDStat",
         "Stat" => "Stat.IDStat"
       )
    );
    $statsQuery = Database::instance()->query($tables,array("StatRequirement.IDChoice"=>"$this->id","StatRequirement.Value" => "", "Stat.Name"=>""));
    $requiried_stats = $this->arrayMap($statsQuery, 'Name', 'Value');

    foreach ($requiried_stats as $key => $value) {
      if(!isset($player_stats["$key"]) || $value>$player_stats["$key"])
        return false;
    }

    $player_items =  $player->items();
    $tables = array(
       array(
         "ItemRequirement" => "ItemRequirement.IDItem",
         "Item" => "Item.IDItem"
       )
    );

    $itemsQuery = Database::instance()->query($tables,array("ItemRequirement.IDChoice"=>"$this->id","ItemRequirement.quantity" => "","Item.Name"=>""));
    $requiried_items = $this->arrayMap($itemsQuery, 'Name', 'quantity');

    //var_dump($player_items);
    //var_dump($requiried_items);

    foreach ($requiried_items as $key => $value) {
      if(!isset($player_items["$key"]) || $value>$player_items["$key"])
        return false;
    }
    return true;
  }
}
 ?>
