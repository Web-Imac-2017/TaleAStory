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
  /*
  @function addChoice
  @param  answer, idstep, transitiontext, idnextstep
  @return Success si ok, Error avec array 'champ'=>'erreur' sinon
  Ajoute un choix
  */
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
    $entries['statalteration'] = Form::getField('statalteration');
    if(!empty($entries['statalteration'])){
      $errors['statalteration']=ChoiceController::setStats($entries['statalteration'],"StatAlteration");
    }
    $entries['statrequirement'] = Form::getField('statrequirement');
    if(!empty($entries['statrequirement'])){
      $errors['statrequirement']=ChoiceController::setStats($entries['statrequirement'],'StatRequierement');
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
  /*
  @function updateChoice
  @param  idchoice, answer, idstep, transitiontext, idnextstep
  @return Success si ok, Error avec array 'champ'=>'erreur' sinon
  Modifie un choix
  */
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
    //STATS LIEES AU CHOIX - updatées si non vide
    $entries['statalteration'] = Form::getField('statalteration');
    if(!empty($entries['statalteration'])){
      $errors['statalteration']=ChoiceController::setStats($entries['statalteration'],"StatAlteration");
    }
    $entries['statrequirement'] = Form::getField('statrequirement');
    if(!empty($entries['statrequirement'])){
      $errors['statrequirement']=ChoiceController::setStats($entries['statrequirement'],'StatRequierement');
    }
    //si erreur, on arrête
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
  /*
  @function deleteChoice
  @param  idchoice
  @return Success si ok, Error avec array 'champ'=>'erreur' sinon
  Supprime un choix
  */
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
  /*
  @function setStats
  @param  $entries = array "stat"=>"value" NON VIDE, $table "StatAlteration" ou "StatRequierement"
  @return message de success ou erreur
  Insère les stats correspondantes à un choix (requises et gagnées)
  */
  public static function setStats($entries,$table){
    foreach ($entries as $key => $value) {
        if($value==NULL){
          return "valeur de stat altérée invalide";
        }
        else if( Database::instance()->insert($table, $entries[$key])==null)
          return "impossible d'ajouter les stats altérées";
    }
    return "";
  }
  /*
  @function getChoiceList
  @param  search, start = id de début, count = nombre de ligne à parcourir
  @return Success avec array d'objets achivement ou array vide, Error sinon
  Cherche des choix par rapport à une answer ou un texte de transition
  */
  public static function getChoiceList($start, $count) {
    $search = Form::getField('search');
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
      if($search) {
        $like = array("LIKE","Answer",$search);
        $like2 = array("LIKE","TransitionText",$search);
    		$choices = Database::instance()->query("Choice", Array("*"=>""), array($like, " OR ", $like2, $limit));
      } else {
        $choices = Database::instance()->query("Choice", Array("*"=>""), array($limit);
      }
      $choices = Database::instance()->dataClean($choices, true);
  		$success = new Success($choices);
  		Response::jsonResponse($success);
  	}
  }

}

?>
