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
    if(is_object($imgpath)){ //error
      $isError = true;
      $errors["image"]=$imgpath->message;
    }
    else
      $entries['imgpath'] = $imgpath;
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
    //var_dump($data);
    if(!isset($data["idstep"]) || $data["idstep"]== null ){
      $errors["idstep"]="ID Invalide";
      $isError = true;
    }
    //on prepare le tableau $entries pour la requete ("champ"=>"valeur" avec valeur = "" si le champ n'est pas modifié)
    $entries = array();
    $data_fields = array("idstep","body","question","idtype","title");
    foreach ($data_fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        if(substr($field,0,2) != "id") //les champs ID inchangés (donc initialisés à null dans data) ne doivent pas être précisés dans entries
          $entries[$field]="";
      }
      else {
          $entries[$field]=$data[$field];
      }
      $errors[$field]="";
    }
    //on essaye d'uploader l'image si besoin et s'il n'y a pas eu d'erreurs avant
    //var_dump($oldimg);
    if(!$isError){
      $imgpath=Form::uploadFile("image");
      if(is_object($imgpath)){
        $isError = true;
        $errors["image"]=$imgpath->message;
      }
      else{
        $entries["imgpath"] = $imgpath;
        $oldimg =  Step::getStepImg($data["idstep"]);
        if($oldimg != '../assets/images/default_image_tiny.png')
          unlink($oldimg);
      }
    }
    //s'il y a des erreurs on n'update pas et on arrete, maintenant ça suffit hein !
    if($isError){
      $e = new Error($errors);
      Response::jsonResponse($e);
    }
    $step = new Step($entries["imgpath"], $entries["body"], $entries["question"], 0,$entries["title"]);
    $step->id = $entries["idstep"];
    $step->update($entries);
    $e = new Success("Péripéthie modifiée !");
    Response::jsonResponse($e);
  }

  public static function deleteStep() {
    //CurrentUserController::isAdmin();
    $id = Form::getField("idstep");
    if(!$id){
      $e = new Error(array("idstep"=>"Impossible de supprimer la péripéthie !"));
      Response::jsonResponse($e);
    }
    else{
      $oldimg =  Step::getStepImg($id);
      $step = new Step("", "", "", 0,"");
      $step->id = $id;
      $step = $step->delete();
      if($step){
        var_dump($oldimg);
        if($oldimg != '../assets/images/default_image_tiny.png')
          unlink($oldimg);
        $e = new Success("Péripéthie supprimée !");
      }
      else
        $e = new Error(array("all"=>"Impossible de supprimer la péripéthie !"));
      Response::jsonResponse($e);
    }
  }
}
?>
