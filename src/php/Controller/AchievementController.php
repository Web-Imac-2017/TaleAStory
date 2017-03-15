<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Achievement;
use \Model\Player;
use \Server\Response;
use \View\Success;
use \View\Error;
use \Controller\CurrentUserController;

class AchievementController {
  /*
  @function addAchievement
  @param  image,name,brief
  @return Success si ok, Error avec array 'champ'=>'erreur' sinon
  Ajoute un trophée
  */
  public static function addAchievement() {
    CurrentUserController::isAdmin();
    $isError = false;
    $imgpath = Form::uploadFile("image");
    if(is_object($imgpath)){ //error
      $isError = true;
      $errors["image"]=$imgpath->message;
    }
    else
      $entries['imgpath'] = $imgpath;
    $entries['name'] = Form::getField("name");
    $entries['brief'] = Form::getField("brief");
    foreach ($entries as $key => $value) {
      if($value==NULL){
        $errors[$key]="Champ invalide";
        $isError = true;
      }
      else
        $errors[$key]="";
    }
    if($isError){
      $e = new Error($errors);
      Response::jsonResponse($e);
    }
    $a = new Achievement($name, $imgpath, $brief);
    $a = $a->save();
    if($a)
      $e = new Success("Le trophé a été ajouté.");
    else
      $e = new Error(array("all"=>"Tu ne peux pas ajouter ce trophé!"));
    Response::jsonResponse($e);
  }
  /*
  @function updateAchievement
  @param  image,name,brief,idachievement
  @return Success si ok, Error avec array 'champ'=>'erreur' sinon
  Modifie un trophée
  */
  public static function updateAchievement() {
    CurrentUserController::isAdmin();
    $isError =false;
    $data = Form::getFullForm();
    if(!isset($data["idachievement"]) || $data["idachievement"]== null ){
      $errors["idachievement"]="id invalide !";
      $isError = true;
    }

    $entries = array();
    $fields = array("name","brief");
    foreach ($fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        if(substr($field,0,2) != "ID") //les champs ID inchangés (donc initialisés à null dans data) ne doivent pas être précisés dans entries
          $entries[$field]="";
      }
      else {
        $entries[$field]=$data[$field];
      }
      $errors[$field]="";
    }
    if(!$isError){
      $imgpath = Form::uploadFile("image");
      if(is_object($imgpath)){
        $isError = true;
        $errors["image"]=$imgpath->message;
      }
      else{
        $entries["imgpath"] = $imgpath;
        $oldimg =  Achievement::getAchievementImg($data["idachievement"]);
        if($oldimg != '../assets/images/default_image_tiny.png' && file_exists ($oldimg)){
          try{
            unlink($oldimg);
        }
      }
    }
    if($isError){
      $e = new Error($errors);
      Response::jsonResponse($e);
    }
    $a = new Achievement ($entries["name"],$entries["brief"],$entries["brief"]);
    $a->id = $data["idachievement"];
    $a->update($entries);
    $e = new Success("Trophé modifié !");
    Response::jsonResponse($e);
  }
  /*
  @function deleteAchievement
  @param  idachievement
  @return Success si ok, Error avec array 'champ'=>'erreur' sinon
  Supprime un trophée
  */
  public static function deleteAchievement() {
    CurrentUserController::isAdmin();
    $id = Form::getField("idachievement");
    if(!$id){
      $e = new Error(array("all"=>"Impossible de supprimer le trophé !"));
      Response::jsonResponse($e);
    }
    else{
      $oldimg =  Achievement::getAchievementImg($data["idachievement"]);
      $a = new Achievement("", "", "");
      $a->id = $id;
      $a = $a->delete();
      if($a){
        if($oldimg != '../assets/images/default_image_tiny.png' && file_exists ($oldimg)){
          try {
            unlink($oldimg);
          } catch(Exception $e) { }
          $e = new Success("Trophé supprimé !");
        }
      }
      else
        $e = new Error(array("all"=>"Impossible de supprimer le trophé !"));
      Response::jsonResponse($e);
    }
  }
  /*
  @function getAchievementList
  @param  string search, $start id de départ, $count nombre de lignes à chercher
  @return Success avec array d'objets achivement ou array vide, Error sinon
  Cherche des trophées par rapport à un nom ou un brief
  */
  public static function getAchievementList($start, $count) {
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
      if ($search) {
        $like = array("LIKE","Name",$search);
        $like2 = array("LIKE","Brief",$search);
    		$achievements = Database::instance()->query("Achievement", Array("*"=>""), array($like, " OR ", $like2, $limit));
      } else {
        $achievements = Database::instance()->query("Achievement", Array("*"=>""), array($limit));
      }
      $achievements = Database::instance()->dataClean($achievements, true);
      if(!$achievements) {$achievements = array();}
  		$success = new Success($achievements);
  		Response::jsonResponse($success);
  	}
  }

}

?>
