<?php
namespace Controller;
use \Server\Database;
use \Server\Form;
use \Model\Step;
use \Model\Player;
use \Server\Response;
use \View\Success;
use \View\Error;
use \Controller\CurrentUserController;

class StepController {

  public static function stepCount() {
    $count = Step::countSteps();
    $success = new Success($count);
    Response::jsonResponse($success);

  }

  public static function getStepsList($start, $count) {
      if ($start-1 < 0) {
          $error = new Error("Variable de départ incorrecte");
          Response::jsonResponse($error);

      }

      else if ($count <= 0){
          $empty = array();
          $sucess = New Success($empty);
          Response::jsonResponse($success);

      }

      else {
      $stepParam = Database::instance()->query("Step", Array("IDStep"=> "",
                                                            "ImgPath"=>"",
                                                            "Body"=>"",
                                                            "Question"=>"",
                                                            "IDType"=>"",
                                                            "Title"=>""),
                                                          "LIMIT ".$count." OFFSET ".$start-1);

                                                          $sucess = New Success($stepParam);
                                                          Response::jsonResponse($success);
      }

  public static function getTenStepsList($start) {
    if ($start-1 < 0) {
      $error = new Error("Variable de départ incorrecte");
      Response::jsonResponse($error);
    }
    else {
      $stepParam = Database::instance()->query("Step", Array("IDStep"=> "",
                                                            "ImgPath"=>"",
                                                            "Body"=>"",
                                                            "Question"=>"",
                                                            "IDType"=>"",
                                                            "Title"=>""),
                                                          "LIMIT 10 OFFSET ".$start-1);

                                                          $sucess = New Success($stepParam);
                                                          Response::jsonResponse($success);
    }
  }

  public static function getSteps() {
    $option = Form::getField("nameFilter");
    $stepArray =  Database::instance()->query("Step", Array("Title"=> $option,
                                                                    "IDStep"=> "",
                                                                    "ImgPath"=>"",
                                                                    "Body"=>"",
                                                                    "Question"=>"",
                                                                    "IDType"=>"",
                                                                    "Title"=>""));
    if ($stepArray != null) {
      $sucess = New Success($stepArray);
      Response::jsonResponse($success);
    }

    else {
      $error = new Error("Aucun step correspondant n'a été trouvé");
      Response::jsonResponse($error);
    }
}

  public static function stepResponse(){
    $answer = Form::getField("answer");
    $id = Session::getCurrentUser();
    $player = Player::getPlayer($id);

    if ($player == NULL) {
      $error = new Error("Le joueur n'a pas pu être trouvé");
      Response::jsonResponse($error);
    }
    else {
        $Step = $player->currentStep();
        $CurrentStep = new Step($Step[0]['ImgPath'], $Step[0]['Body'], $Step[0]['Question'], $Step[0]['IDType'], $Step[0]['Title']);
        $result = $CurrentStep->processAnswer($player,$answer);
        if ($result == true) {
          $success = new Success("Le joueur a bien été modifié");
          Response::jsonResponse($success);
        }

        else {
          $error = new Error("Le joueur n'a pas pu être modifié");
          Response::jsonResponse($error);
        }
    }
  }


  public static function addStep() {
    CurrentUserController::isAdmin();
    $imgpath = Form::uploadFile("ImgPath");
    if($imgpath->status == "error")
      Response::jsonResponse($imgpath); //on retourne l'erreur
    else
      $entries['imgpath'] = "bidon";
    $entries['body'] = Form::getField("Body");
    $entries['question'] = Form::getField("Question");
    $entries['title'] = Form::getField("Title");
    $entries['accepted'] = 1;
    $entries['idType'] = intval(Form::getField("IDType"));
    foreach ($entries as $key => $value) {
      if($value==NULL){
        $e = new Error(array("$key"=>"Champs invalide ! Tu ne peux pas ajouter cette péripéthie !"));
        Response::jsonResponse($e);
      }
    }
    $step = new Step($imgpath, $body, $question, $accepted, $idType,$title);
    $step = $step->save();
    if($step)
      $e = new Success("La péripéthie a été ajoutée.");
    else
      $e = new Error("Tu ne peux pas ajouter cette péripéthie !");
    Response::jsonResponse($e);
  }

  public static function updateStep() {
      CurrentUserController::isAdmin();
    //$tmp = $imgpath;
    //$imgpath = Form::uploadFile("stepImg");
    //unlink ($tem);

    $data = Form::getFullForm(); //si l'id n'est pas présent, on retourne null
    if(!isset($data["IDStep"]) || $data["IDStep"]== null ){
      $e = new Error(array("IDStep"=>"Id invalide ! Péripéthie invalide"));
      Response::jsonResponse($e);
    }

    $entries = array();
    $fields = array("IDStep","ImgPath","Body","Question","IDType","Title");
    foreach ($fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        if(substr($field,0,2) != "ID") //les champs ID inchangés (donc initialisés à null dans data) ne doivent pas être précisés dans entries
          $entries[$field]="";
      }
      else {
        $entries[$field]=$data[$field];
      }
    }
    $step = new Step("bidon", $entries["Body"], $entries["Question"],1, 0,$entries["Title"]);
    $step->id = $entries["IDStep"];
    $step->update($entries);
    $e = new Success("Péripéthie modifiée !");
    Response::jsonResponse($e);
  }

  public static function deleteStep() {
      CurrentUserController::isAdmin();
    $id = Form::getField("IDStep");
    if(!$id){
      $e = new Error(array("IDStep"=>"Impossible de supprimer la péripéthie !"));
      Response::jsonResponse($e);
    }
    else{
      $step = new Step("", "", "", 0,0,"");
      $step->id = $id;
      $step = $step->delete();
      if($step)
        $e = new Success("Péripéthie supprimée !");
      else
        $e = new Error(array("all"=>"Impossible de supprimer la péripéthie !"));
      Response::jsonResponse($e);
    }
  }
}
?>
