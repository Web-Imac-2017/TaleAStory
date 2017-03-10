<?php
namespace Model;

use \Model\Choice;
use \Server\Response;
use \Model\Player;
use \Server\Database;
use \Server\RouterException;

Class Step {
  public $id;
  public $imgpath;
  public $body;
  public $question;
  public $accepted;
  public $idType;
  public static $table = "Step";

  public function __construct($imgpath, $body, $question,  $idType) {
    $this->imgpath=$imgpath;
    $this->body=$body;
    $this->question=$question;
    $this->idType=$idType;
  }
  /*
  @function save
  @return $id du step créé, null si erreur
  Insère un nouveau step en base de données
  */
  public function save() {
     $entries = array(
       'ImgPath' => $this->imgpath,
       'Body' => $this->body,
       'Question' => $this->question,
       'IDType' => $this->idType
     );
     $id = Database::instance()->insert(self::$table, $entries);
     //var_dump($id);
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
   Maj une step
   */
   public function update($entries) {
     Database::instance()->update(self::$table, $entries, array("IDStep"=>$this->id));
   }
   /*
   @function delete
   @return $bool faux si erreur, vrai si ok
   Supprime une step, ainsi que ses "mentions" dans les tables qui sont liées
   */
   public function delete() {
     $entries = array(
       "IDStep" => $this->id
     );
    try {
       $table = "AdminWriting";
       Database::instance()->delete($table, array("IDStep"=>$this->id));
       $table = "Player";
       Database::instance()->delete($table, array("IDCurrentStep"=>$this->id));
       $table = "Choice";
       Database::instance()->delete($table, array("IDStep"=>$this->id));
       $table = "PastStep";
       Database::instance()->delete($table, array("IDStep"=>$this->id));
       Database::instance()->delete(self::$table, array("IDStep"=>$this->id));

     }catch (RuntimeException $e) {
         return false;
     }
     return true;
   }
   /*
   @function processAnswer
   @param  $player courant
   @param  $answer à vérifier
   @return JSON Response 'error' si erreur, $player mis à jour sinon
   Vérifie la réponse donnée, regarde si le joueur peut faire le choix correspondant, met à jour le joueur si oui
   */
   public function processAnswer($player, $answer) {
     /*pour chaque choix du step, vérifier checkanswer et prendre celle qui est vrai */
     $choiceArray = Database::instance()->query("Choice", array( 'IDStep' => $this->id, 'TransitionText'=>'', 'IDNextStep'=>'', 'IDChoice'=>'', 'Answer'=>''));
     var_dump($choicesArray);
     $true_choice = null;
     foreach ($choicesArray as $c) {
       $choice = new Choice($c['Answer'],$c['IDStep'],$c['TransitionText'],$c['IDNextStep']);
       $choice->id = $c['IDChoice'];
       if( $choice->checkAnswer($answer)){
         $true_choice = $choice;
         break;
       }
     }
     if($true_choice && $true_choice->checkPlayerRequirements($player)) {
       $true_choice->alterPlayer($player);
       $nextStepArray = Database::instance()->query("Step", array('IDStep' => "$true_choice->IDNextStep", 'ImgPath' => '', 'Question'=>'', 'Body'=>'', 'IDType'=>''));
       $nextStep = new Step($nextStepArray[0]['ImgPath'],$nextStepArray[0]['Body'],$nextStepArray[0]['Question'],1,$nextStepArray[0]['IDType']);
       $nextStep->id = $choice->iDNextStep;
       $player->passStep($nextStep);
       return true;
     }
     else {
       return false;
     }
   }


   public static function countSteps(){
     return Database::instance()->count('Step','IDStep');
   }



}
?>
