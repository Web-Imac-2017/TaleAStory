<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Step;

class StepController {
  public static function addStep() {
    $imgpath = Form::uploadFile("stepImg");
    $body = Form::getField("body");
    $question = Form::getField("question");
    $accepted = Form::getField("accepted");
    $idType = Form::getField("idType");
    if ($imgpath == NULL || $body == NULL || $question == NULL || $accepted == NULL || $idType == NULL)
    return Response::jsonResponse(array(
      'status' => "error",
      'message' => "ta race tu ne peux pas ajouter ce step !"
    ));
    $step = new Step($imgpath, $body, $question, $accepted, $idType);
    $step->save();
    Response::jsonResponse($step);
  }

  public static function updateStep() {
    $tmp = $imgpath;
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
    $step = new Step("", "", "", "","");
    $step->id = $id;
    $step->delete();
  }

}

?>
