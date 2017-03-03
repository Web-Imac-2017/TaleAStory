<?php
require_once 'Choice.php';
require_once 'Response.php';

Class Step {
  public $id;
  public $imgpath;
  public $body;
  public $question;
  public $accepted;
  public $idType;
  public static $table = "Step";

  public function __construct($imgpath, $body, $question, $accepted, $idType) {
    $this->imgpath=$imgpath;
    $this->body=$body;
    $this->question=$question;
    $this->accepted=$accepted;
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
     Database::instance()->insert(self::$table, $entries);
     //SOLUTION TEMP POUR LES TEST - A MODIFIER lorque insert renverra directement l'ID
     $id = Database::instance()->query(self::$table, array("ImgPath" =>"$this->imgpath", "Body" =>$this->body,"Question"=>$this->question, "IDType"=>$this->idType, "IDStep"=>""));
     //var_dump($id);
     if($id != null){
       $this->id = $id[0]['IDStep'];
       return $id[0]['IDStep'];
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
     //AFFICHE UN MESSAGE D'ERREUR MAIS FONCTIONNE QUAND MEME ??
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
         echo $e->getMessage();
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
     $choiceArray = Database::instance()->query("Choice", array('Answer' => "$answer", 'IDStep' => $this->id, 'TransitionText'=>'', 'IDNextStep'=>'', 'IDChoice'=>''));
     //var_dump($choiceArray);
     $choice = new Choice($choiceArray[0]['Answer'],$choiceArray[0]['IDStep'],$choiceArray[0]['TransitionText'],$choiceArray[0]['IDNextStep']);
     $choice->id = $choiceArray[0]['IDChoice'];
     //var_dump($choice);
     if($choice && $choice->checkAnswer($answer) && $choice->checkPlayerRequirements($player)) {
       $choice->alterPlayer($player);
       //A MODIFIER - passer l'objet step à passStep()
       $player->passStep($choice->IDNextStep);
       return $player;
     }
     else {
       return Response::jsonResponse(array(
         'status' => "error",
         'message' => "ta race tu ne peux pas faire ce choix !"
       ));
     }
   }

}
?>
