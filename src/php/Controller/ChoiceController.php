<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Choice;
use \Model\Player;
use \Server\Response;
use \View\Success;
use \View\Error;
use \Controller\CurrentUserController;

class ChoiceController {
  public static function addChoice() {

    CurrentUserController::isAdmin();

    $answer = Form::getField("Answer");
    $idStep = intval(Form::getField("IDStep"));
    $transitionText = Form::getField("TransitionText");
    $idNextStep = intval(Form::getField("IDNextStep"));
    if ($answer == NULL || $idStep == NULL || $transitionText == NULL || $idNextStep == NULL){
      $e = new Error(array("all"=>"Tu ne peux pas ajouter ce choix !"));
      Response::jsonResponse($e);
    }
    $choice = new Choice($answer, $idStep, $transitionText, $idNextStep);
    $choice  = $choice->save();
    if($choice)
      $e = new Success("Le choix a bien été ajouté !");
    else
      $e = new Error(array("all"=>"Tu ne peux pas ajouter ce choix !"));
    Response::jsonResponse($e);
  }

  public static function updateChoice() {
    CurrentUserController::isAdmin();
    $data = Form::getFullForm(); //si l'id n'est pas présent, on retourne null
    if(!isset($data["IDChoice"]) || $data["IDChoice"]== null ){
      $e = new Error(array("all"=>"Tu ne peux pas modifer ce choix !"));
      Response::jsonResponse($e);
    }

    $entries = array();
    $fields = array("IDChoice","Answer","IDStep","TransitionText","IDNextStep");
    foreach ($fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        if(substr($field,0,2) != "ID") //les champs ID inchangés (donc initialisés à null dans data) ne doivent pas être précisés dans entries
          $entries[$field]="";
      }
      else {
        $entries[$field]=$data[$field];
      }
    }
    //var_dump($entries);
    $choice = new Choice($entries["Answer"], 0, $entries["TransitionText"],0);
    $choice->id = $entries["IDChoice"];
    $choice->update($entries);
    $e = new Success("Choix modifié !");
    Response::jsonResponse($e);
  }

  public static function deleteChoice() {
    CurrentUserController::isAdmin();
    $id = Form::getField("IDChoice");
    if(!$id){
      $e = new Error("Impossible de supprimer le choix !");
      Response::jsonResponse($e);
    }
    else{
      $choice = new Choice("", 0, "", "",0);
      $choice->id = $id;
      $choice = $choice->delete();
      if($choice)
        $e = new Success("Choix supprimé !");
      else
        $e = new Error(array("all"=>"Impossible de supprimer ce choix !"));
      Response::jsonResponse($e);
    }
  }

}

?>
