<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Step;
use \Server\Response;

class StepController {
  public static function addStep() {
    //$imgpath = Form::uploadFile("stepImg");
    $imgpath = "bidon";
    $body = Form::getField("Body");
    $question = Form::getField("Question");
    $accepted = 1;
    $idType = Form::getField("IDType");
    if ($imgpath == NULL || $body == NULL || $question == NULL || $accepted == NULL || $idType == NULL)
       Response::jsonResponse(array(
        'status' => "error",
        'message' => "ta race tu ne peux pas ajouter ce step !"
      ));
    $step = new Step($imgpath, $body, $question, $accepted, $idType);
    $step->save();
    Response::jsonResponse($step);
  }

  public static function updateStep() {
    //$tmp = $imgpath;
    //$imgpath = Form::uploadFile("stepImg");
    //unlink ($tem);

    $data = Form::getFullForm(); //si l'id n'est pas présent, on retourne null
    var_dump($data);
    if(!isset($data["IDStep"]) || $data["IDStep"]== null ){
      Response::jsonResponse(array(
        'status' => "error",
        'message' => "Péripéthie invalide"
      ));
      return null;
    }

    $entries = array();
    $fields = array("IDStep","ImgPath","Body","Question","IDType");
    foreach ($fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        $entries[$field]="";
      }
      else {
        $entries[$field]=$data[$field];
      }
    }
    $step = new Step("bidon", $entries["Body"], $entries["Question"],1, $entries["IDType"]);
    $step->id = $entries["IDStep"];

    $step->update($entries);

    return 1;
  }

  public static function deleteStep() {
    $id = Form::getField("IDStep");
    if(!$id){
      return Response::jsonResponse(array(
        'status' => "error",
        'message' => "Suppression de péripéthie invalide"
      ));
    }
    else{
      $step = new Step("", "", "", 0,0);
      $step->id = $id;
      $step->delete();
      return Response::jsonResponse(array(
        'status' => "ok",
        'message' => "La péripéthie a été supprimée"
      ));
    }
  }

}

?>
