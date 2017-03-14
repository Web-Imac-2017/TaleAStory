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
    $isError = false;
    $entries['answer'] = Form::getField("answer");
    $entries['idstep'] = intval(Form::getField("idstep"));
    $entries['transitiontext'] = Form::getField("transitiontext");
    $entries['idnextstep'] = intval(Form::getField("idnextstep"));
    foreach ($entries as $key => $value) {
      if($value==NULL){
        $errors[$key]="Champ invalide";
        $isError = true;
      }
      else
        $errors[$key]="";
    }
    $entries['statsalteration'] = Form::getField('statsalteration');
    if(!empty($entries['statsalteration'])){
      $errors['statsalteration']=ChoiceController::setStats($entries['statsalteration'],"StatAlteration");
    }
    $entries['statsrequierements'] = Form::getField('statsrequierements');
    if(!empty($entries['statsrequierements'])){
      $errors['statsrequierements']=ChoiceController::setStats($entries['statsrequierements'],'StatRequierement');
    }
    if($isError){
      $e = new Error($errors);
      Response::jsonResponse($e);
    }
    $choice = new Choice($entries['answer'], $entries['idstep'], $entries['transitiontext'], $entries['idnextstep']);
    $choice  = $choice->save();
    if($choice)
      $e = new Success("Le choix a bien été ajouté !");
    else
      $e = new Error(array("all"=>"Tu ne peux pas ajouter ce choix !"));
    Response::jsonResponse($e);
  }

  public static function updateChoice() {
    CurrentUserController::isAdmin();
    $isError =false;
    $data = Form::getFullForm(); //si l'id n'est pas présent, on retourne null
    //var_dump($data);
    if(!isset($data["idchoice"]) || $data["idchoice"]== null ){
      $errors["idchoice"]="ID Invalide";
      $isError = true;
    }
    //on prepare le tableau $entries pour la requete ("champ"=>"valeur" avec valeur = "" si le champ n'est pas modifié)
    $entries = array();
    $data_fields = array("idchoice","answer","idstep","transitiontext","idnextstep");
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
    if($isError){
      $e = new Error($errors);
      Response::jsonResponse($e);
    }
    $choice = new Choice($entries["answer"],$entries["idstep"],$entries["transitiontext"],$entries["idnextstep"]);
    $choice->id = $entries["idchoice"];
    $choice->update($entries);
    $e = new Success("Choix modifié !");
    Response::jsonResponse($e);
  }

  public static function deleteChoice() {
    CurrentUserController::isAdmin();
    $id = Form::getField("idchoice");
    if(!$id){
      $e = new Error(array("idchoice"=>"ID Invalide, impossible de supprimer le choix !"));
      Response::jsonResponse($e);
    }
    else{
      $choice = new Choice("", 0, "", 0);
      $choice->id = $id;
      $choice = $choice->delete();
      if($choice)
        $e = new Success("Choix supprimé !");
      else
        $e = new Error(array("all"=>"Impossible de supprimer ce choix !"));
      Response::jsonResponse($e);
    }
  }

  public static function setStats($entries,$table){
    foreach ($entries as $key => $value) {
        if($value==NULL){
          return "valeur de stat altérée invalide";
        }
        else {
          //$insert = Database::insert();
          if( $insert==null) return "impossible d'ajouter les stats altérées";
        }
    }
    return "";
  }

  public static function getChoiceList($start, $count) {
  	$start--;
  	if ($start < 0) {
  	  $error = new Error("Variable de départ incorrecte");
  	  Response::jsonResponse($error);
  	}
  	else if ($count <= 0){
  	  $empty = array();
  	  $success = new Success($empty);
  	  Response::jsonResponse($success);
  	}
  	else {
      $limit = "LIMIT ".$count." OFFSET ".$start;
  		$choices = Database::instance()->query("Choice", Array("*"=>""), $limit);
      $choices = Database::instance()->dataClean($choices, true);
  		$success = new Success($choices);
  		Response::jsonResponse($success);
  	}
  }

}

?>
