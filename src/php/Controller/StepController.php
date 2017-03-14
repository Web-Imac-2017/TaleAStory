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
  }
  /*public static function getTenStepsList($start) {
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
                                                            "Title"=>"",
                                                          "LIMIT 10 OFFSET ".$start-1);

                                                          $sucess = New Success($stepParam);
                                                          Response::jsonResponse($success);
    }
  }*/

  public static function getSteps() {
    $option = Form::getField("nameFilter");
    $stepArray =  Database::instance()->query("Step", Array("Title"=> $option,
                                                                    "IDStep"=> "",
                                                                    "ImgPath"=>"",
                                                                    "Body"=>"",
                                                                    "Question"=>"",
                                                                    "IDType"=>""));
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
    $isError = false;
    $imgpath = Form::uploadFile("image");
    if($imgpath->status == "error"){
      $isError = true;
      $errors["image"]=$imgpath->message;
    }
    else
      $entries['imgpath'] = str_replace('\\','',$imgpath->message);
    //var_dump($_POST);
    $entries['body'] = Form::getField("body");
    $entries['question'] = Form::getField("question");
    $entries['title'] = Form::getField("title");
    $entries['accepted'] = 1;
    $entries['idType'] = intval(Form::getField("idtype"));
    //var_dump($entries);
    foreach ($entries as $key => $value) {
      if($value==NULL){
        $errors[$key]="Champ invalide";
        $isError = true;
      }
      else
        $errors[$key]="";
    }
    if($isError){
      $e = new Error($errors);
      Response::jsonResponse($e);
    }
    $step = new Step($entries['imgpath'], $entries['body'], $entries['question'], $entries['idType'],$entries['title']);
    $step = $step->save();
    if($step)
      $e = new Success("La péripéthie a été ajoutée.");
    else
      $e = new Error("Tu ne peux pas ajouter cette péripéthie !");
    Response::jsonResponse($e);
  }

  public static function updateStep() {
    CurrentUserController::isAdmin();
    $isError =false;
    $data = Form::getFullForm(); //si l'id n'est pas présent, on retourne null
    if(!isset($data["IDStep"]) || $data["IDStep"]== null ){
      $errors["IDStep"]="ID Invalide";
      $isError = true;
    }
    //on prepare le tableau $entries pour la requete ("champ"=>"valeur" avec valeur = "" si le champ n'est pas modifié)
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
      $errors[$field]="";
    }
    //on essaye d'uploader l'image si besoin et s'il n'y a pas eu d'erreurs avant
    if(!$isError && $entries["ImgPath"]!=""){
      $imgpath = Form::uploadFile("ImgPath");
      if($imgpath->status == "error"){
        $isError = true;
        $errors["ImgPath"]=$imgpath->message;
      }
      else
        $entries["ImgPath"] = str_replace('\\','',$imgpath->message);
    }
    //s'il y a des erreurs on n'update pas et on arrete, maintenant ça suffit hein !
    if($isError){
      $e = new Error($errors);
      Response::jsonResponse($e);
    }
    $step = new Step($entries["ImgPath"], $entries["Body"], $entries["Question"], 0,$entries["Title"]);
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
      $step = new Step("", "", "", 0,"");
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
