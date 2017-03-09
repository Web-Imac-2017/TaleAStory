<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Choice;
use \Server\Response;

class ChoiceController {
  public static function addChoice() {
    $answer = Form::getField("answer");
    $idStep = Form::getField("idStep");
    $transitionText = Form::getField("transitionText");
    $idNextStep = Form::getField("idNextStep");
    if ($answer == NULL || $idStep == NULL || $transitionText == NULL || $idNextStep == NULL)
    return Response::jsonResponse(array(
      'status' => "error",
      'message' => "ta race tu ne peux pas ajouter ce choix !"
    ));
    $choice = new Choice($answer, $idStep, $transitionText, $idNextStep);
    $choice->save();
    Response::jsonResponse($choice);
  }

  public static function updateChoice() {
    $data = Form::getForm();
    $entries = array();
    $fields = array_keys($data);
    foreach ($fields as $field) {
      if($data[$field] == NULL) {
        $entries[$field]=" ";
      }
    }
    $choice = new Choice($answer, $idStep, $transitionText, $idNextStep);
    $choice->id = "id";
    $choice->update($entries);
  }

  public static function deleteChoice() {
    $id = Form::getField("id");
    $choice = new Choice("", "", "", "");
    $choice->id = $id;
    $choice->delete();
  }

}

 ?>
