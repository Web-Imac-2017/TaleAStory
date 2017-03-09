<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Step;

class stepController {

  public static function stepCount() {
    return step::countSteps();
  }


  public static function getStepsList($start, $count) {
    $stepArray = [];
    $case=0;

    for ($i=$start; $i<$start + $count; $i++, $case++) {
      $stepParam = Database::instance()->query("Type", Array("IDStep"=> $i, "ImgPath"=>"","Body"=>"", "Question"=>"", "IDType"=>""));
      $stepArray[$case] = New Step($stepParam["ImgPath"],$stepParam["Body"],$stepParam["Question"],$stepParam["IDType"]);
    }
    return $stepArray;
  }


  public static function getTenStepsList($start) {
    $stepArray = [];
    $case=0;
    for ($i=$start; $i<$start + 10; $i++, $case++) {
      $stepParam = Database::instance()->query("Type", Array("IDStep"=> $i, "ImgPath"=>"","Body"=>"", "Question"=>"", "IDType"=>""));
      $stepArray[$case] = New Step($stepParam["ImgPath"],$stepParam["Body"],$stepParam["Question"],$stepParam["IDType"]);
    }
    return $stepArray;
  }


  public static function getStep() { //récuperer les options grâce au module global : Form.php ->
    Form::getFormPost( );
  }


  public static function stepResponse(){
    $answer = Form::getField( );
    $id = Session::getCurrentUser;
    $player = Player::getPlayer($id);
    $CurrentStep_TMP = $player->currentStep();
    $Step_TMP = New Step;

    if ($player == NULL) {
      echo "Joueur introuvable, IDPlayer incorrect.";
    }
    else {
      $array = $Step_TMP->processAnswer($player,$answer);
      return Response::jsonResponse($array);
    }
  }


  public static function addStep() {
    $imgpath = Form::uploadFile("stepImg");
    $body = Form::getField("body");
    $question = Form::getField("question");
    $accepted = Form::getField("accepted");
    $idType = Form::getField("idType");
<<<<<<< HEAD
    $item = new Step($imgpath, $body, $question, $accepted, $idType);
    //$item->save();
  //  Response::jsonResponse(???);
    Response::jsonResponse($_POST);
=======
    if ($imgpath == NULL || $body == NULL || $question == NULL || $accepted == NULL || $idType == NULL)
    return Response::jsonResponse(array(
      'status' => "error",
      'message' => "ta race tu ne peux pas ajouter ce step !"
    ));
    $step = new Step($imgpath, $body, $question, $accepted, $idType);
    $step->save();
    Response::jsonResponse($step);
>>>>>>> 402e841861e9e8a1c47c15e4aace1e517667bb0e
  }
}

<<<<<<< HEAD
/*  public static function updateStep() {
=======
  public static function updateStep() {
    $tmp = $imgpath;
>>>>>>> 402e841861e9e8a1c47c15e4aace1e517667bb0e
    $imgpath = Form::uploadFile("stepImg");
    // récupérer 1 obj avec soit statut "error" ou "ok", selon le statut : message d'erreur ou nom de l'image (faire des if)
    $body = Form::getField("body");
    $question = Form::getField("question");
    $accepted = Form::getField("accepted");
    $idType = Form::getField("idType");
    $item = new Step($imgpath, $body, $question, $accepted, $idType);
    $item->id = $id;
    $entries = array();
    if ($imgpath != NULL)
      array_push($entries, $name);
    if ($body != NULL)
      array_push($entries, $name);
    if ($question != NULL)
      array_push($entries, $name);
    if ($accepted != NULL)
      array_push($entries, $name);
    if ($idType != NULL)
      array_push($entries, $name);
    $item->update($entries);
  }

  public static function deleteStep() {
    $id = Form::getField("id");
    $step = new Step("", "", "", "","");
    $step->id = $id;
    $step->delete();
  }

<<<<<<< HEAD
  //echo var_dump($_POST);

}*/
=======
}
>>>>>>> 402e841861e9e8a1c47c15e4aace1e517667bb0e

?>
