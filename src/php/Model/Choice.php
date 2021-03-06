<?php
namespace Model;
use \Server\Database;

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
     $id=Database::instance()->insert(self::$table, $entries);
     if($id != null){
       $this->id = $id;
       return $id;
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
            "StatAlteration" => "StatAlteration.IDStat",
            "Stat" => "Stat.IDStat"
          )
       );
       $statsQuery = Database::instance()->query($tables,array("StatAlteration.IDChoice"=>"$this->id","StatAlteration.Value" => "", "Stat.IDStat"=>""));
       $requiried_stats = Database::instance()->arrayMap($statsQuery, 'IDStat', 'Value');

       //on récupère les items gagnés
       $tables = array(
          array(
            "Earn" => "Earn.IDItem",
            "Item" => "Item.IDItem"
          )
       );
       $itemsQuery = Database::instance()->query($tables,array("Earn.IDChoice"=>"$this->id","Earn.quantity" => "","Item.Name"=>"","Item.IDItem"=>""));
       $earned_items =Database::instance()->arrayMap($itemsQuery, 'IDItem', 'quantity');

       //on récupère les items perdus
       $tables = array(
          array(
            "ItemRequirement" => "ItemRequirement.IDItem",
            "Item" => "Item.IDItem"
          )
       );
       $itemsQuery = Database::instance()->query($tables,array("ItemRequirement.IDChoice"=>"$this->id","ItemRequirement.quantity" => "","Item.Name"=>"", "Item.IDItem"=>""));
       $requiried_items = Database::instance()->arrayMap($itemsQuery, 'IDItem', 'quantity');

      /* var_dump($requiried_stats);
       var_dump($requiried_items);
       var_dump($earned_items);*/

       //on modifie le joueur
       if(!empty($requiried_stats))
          $player->alterStats($requiried_stats);
       if(!empty($requiried_items))
          $player->removeItems($requiried_items);
       if(!empty($earned_items))
          $player->addItems($earned_items);

    }catch (RuntimeException $e) {
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
    //var_dump($choiceAnswer);
    return $choiceAnswer[0]['Answer'] == $answer ? true:false;
  }
  /*
  @function checkPlayerRequirements
  @param  $player joueur courant
  @return $bool false si le choix est impossible, vrai sinon
  Check si un joueur a le droit de faire un choix (items recquis, stats recquises par le choix)
  */
  public function checkPlayerRequirements($player){
    $pStatsQuery =  $player->stats();
    $player_stats = Database::instance()->arrayMap($pStatsQuery, 'IDStat', 'Value');

    $tables = array(
       array(
         "StatRequirement" => "StatRequirement.IDStat",
         "Stat" => "Stat.IDStat"
       )
    );
    $statsQuery = Database::instance()->query($tables,array("StatRequirement.IDChoice"=>"$this->id","StatRequirement.Value" => "", "StatRequirement.IDStat"=>""));
    $requiried_stats = Database::instance()->arrayMap($statsQuery, 'IDStat', 'Value');

	if (!empty($requiried_stats)) {
		foreach ($requiried_stats as $key => $value) {
		  if(!isset($player_stats["$key"]) || $value>$player_stats["$key"])
			return "Tu n'a pas les stats recquises ahahaha ";
		}
	}

    $pItemsQuery =  $player->items();
    $player_items = Database::instance()->arrayMap($pItemsQuery, 'IDItem', 'quantity');

    $tables = array(
       array(
         "ItemRequirement" => "ItemRequirement.IDItem",
         "Item" => "Item.IDItem"
       )
    );

    $itemsQuery = Database::instance()->query($tables,array("ItemRequirement.IDChoice"=>"$this->id","ItemRequirement.quantity" => "","ItemRequirement.IDItem"=>""));
    $requiried_items = Database::instance()->arrayMap($itemsQuery, 'IDItem', 'quantity');

    /*var_dump($player_stats);
    var_dump($requiried_stats);
    var_dump($player_items);
    var_dump($requiried_items);*/

	if (!empty($requiried_items)) {
		foreach ($requiried_items as $key => $value) {
		  if(!isset($player_items["$key"]) || $value>$player_items["$key"])
			   return "Tu n'as pas les items recquis ahahaha !";
		}
	}
    return true;
  }
  /*
  @function getChoiceByStep
  @param  $id id de la step
  @return null si rien, tableau de Choice sinon
  Retourne tous les choix d'une step donnée
  */
  public static function getChoiceByStep($id){
    $choiceQuery = Database::instance()->query(self::$table,array("IDStep"=>$id,"*"=>""));
    $choices = array();
    if($choiceQuery){
      foreach ($choiceQuery as $key => $value) {
        $c = new Choice($choiceQuery[$key]["Answer"],$id,$choiceQuery[$key]["TransitionText"],$choiceQuery[$key]["IDNextStep"]);
        array_push($choices,$c);
      }
    }
    return $choices;
  }
  /*
  @function getChoiceByNextStep
  @param  $id id de la step
  @return null si rien, tableau de Choice sinon
  Retourne tous les choix d'une step donnée
  */
  public static function getChoiceByNextStep($id){
    $choiceQuery = Database::instance()->query(self::$table,array("IDNextStep"=>$id,"*"=>""));
    $choices = array();
    if($choiceQuery){
      foreach ($choiceQuery as $key => $value) {
        $c = new Choice($choiceQuery[$key]["Answer"],$choiceQuery[$key]["IDStep"],$choiceQuery[$key]["TransitionText"],$id);
        array_push($choices,$c);
      }
    }
    return $choices;
  }

}
 ?>
