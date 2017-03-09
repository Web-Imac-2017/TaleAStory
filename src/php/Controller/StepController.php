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
    echo var_dump($_POST, $_SERVER, $_GET);
    $imgpath = "truc";
    $body = Form::getField("body");
    $question = Form::getField("question");
    $accepted = Form::getField("accepted");
    $idType = Form::getField("idType");
    $item = new Step($imgpath, $body, $question, $accepted, $idType);
    //$item->save();
  //  Response::jsonResponse(???);
    Response::jsonResponse($_POST);
  }
}

/*  public static function updateStep() {
    $imgpath = Form::uploadFile("stepImg");
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
    $item = new Step("", "", "", "","");
    $item->id = $id;
    $item->delete();
  }

  //echo var_dump($_POST);

}*/

 ?>
