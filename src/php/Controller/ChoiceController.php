<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Choice;
use \Server\Response;

class ChoiceController {
  public static function addChoice() {
    $answer = Form::getField("Answer");
    $idStep = Form::getField("IDStep");
    $transitionText = Form::getField("TransitionText");
    $idNextStep = Form::getField("IDNextStep");
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
    $data = Form::getFullForm(); //si l'id n'est pas présent, on retourne null
    if(!isset($data["IDChoice"]) || $data["IDChoice"]== null ){
      Response::jsonResponse(array(
        'status' => "error",
        'message' => "Choix invalide"
      ));
      return null;
    }

    $entries = array();
    $fields = array("IDChoice","Answer","IDStep","TransitionText","IDNextStep");
    foreach ($fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        $entries[$field]="";
      }
      else {
        $entries[$field]=$data[$field];
      }
    }
    $choice = new Choice($entries["Answer"], $entries["IDStep"], $entries["TransitionText"],$entries["IDNextStep"]);
    $choice->id = $entries["IDChoice"];

    $choice->update($entries);
    return 1;
  }

  public static function deleteChoice() {
    $id = Form::getField("IDChoice");
    if(!$id){
      return Response::jsonResponse(array(
        'status' => "error",
        'message' => "Suppression de choix invalide"
      ));
    }
    else{
      $choice = new Choice("", 0, "", "",0);
      $choice->id = $id;
      $choice->delete();
      return Response::jsonResponse(array(
        'status' => "ok",
        'message' => "Le choix a été supprimé"
      ));
    }
  }

}

 ?>
