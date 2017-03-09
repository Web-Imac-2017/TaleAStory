<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Choice;

class ChoiceController {
  public static function addChoice() {
    $answer = Form::getField("answer");
    var_dump($answer);
    $idStep = Form::getField("idStep");
    $transitionText = Form::getField("transitionText");
    $idNextStep = Form::getField("idNextStep");
    $item = new Choice($answer, $idStep, $transitionText, $idNextStep);
    //$item->save();
    Response::jsonResponse($answer);
    Response::jsonResponse($_POST);
  }

  public static function updateChoice() {
    $answer = Form::getField("answer");
    $idStep = Form::getField("idStep");
    $transitionText = Form::getField("transitionText");
    $idNextStep = Form::getField("idNextStep");
    $item = new Choice($answer, $idStep, $transitionText, $idNextStep);
    $item->id = $id;
    $entries = array();
    if ($answer != NULL)
      array_push($entries, $answer);
    if ($idStep != NULL)
      array_push($entries, $answer);
    if ($transitionText != NULL)
      array_push($entries, $answer);
    if ($idNextStep != NULL)
      array_push($entries, $answer);
    $item->update($entries);
  }

  public static function deleteChoice() {
    $id = Form::getField("id");
    $item = new Choice("", "", "", "");
    $item->id = $id;
    $item->delete();
  }

}

 ?>
