<?php

Class Step {
  public $imgpath;
  public $body;
  public $question;
  public $accepted;
  public $idType;

  public function __construct($imgpath, $body, $question, $accepted, $idType) {
    $this->imgpath=$imgpath;
    $this->body=$body;
    $this->question=$question;
    $this->accepted=$accepted;
    $this->idType=$idType;
  }

  public function save() {
   $table = "Step";
   $entries = array(
     "IDStep" => "",
     'imgpath' => $this->imgpath,
     'body' => $this->body,
     'question' => $this->question,
     'accepted' => $this->accepted,
     'idType' => $this->idType,
   );
   Database::instance()->insert($table, $entries);
   }

   public function processAnswer($player, $answer) {
     $table = array("Step","Choice");
     $entries = array(
       'Choice.Answer' => $answer,
       'Choice.IDStep' => $this->idStep
     );
     $choice = Database::instance()->query($table, $entries);
     if(checkAnswer($answer) && checkPlayerRequirements($player)) {
       $choice->alterPlayer($player);
       $player->passStep($nextStep);
       return $player;
     }
     else {
       return Response::jsonResponse(array(
         'status' => "error",
         'message' => "ta race tu ne peux pas faire ce choix !"
       ));
     }
   }

   public function update($imgpath = NULL, $body = NULL, $question = NULL, $accepted = NULL, $idType = NULL) {
     $table = "Step";
     $entries = array(
       "IDStep" => $this->idStep,
       'imgpath' => $imgpath,
       'body' => $body,
       'question' => $question,
       'accepted' => $accepted,
       'IDType' => $idType
     );
     Database::instance()->update($table, $entries);
   } // attendre Lou

   public function delete() {
     $table = "AdminWriting";
     $entries = array(
       "IDStep" => $this->id
     );
     Database::instance()->delete($table, $entries);
     $table = "Player";
     Database::instance()->delete($table, $entries);
     $table = "Choice";
     Database::instance()->delete($table, $entries);
     $table = "PastStep";
     Database::instance()->delete($table, $entries);
   }

}
?>
