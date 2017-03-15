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
      $imgpath=Form::uploadFile("image");
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
          } catch(Exception $e) { }
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
      $a=$a->delete();
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

  public static function getAchievementList($start, $count, $search) {
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
      $like = array("LIKE","Name",$search);
      $like2 = array("LIKE","Brief",$search);
  		$achievements = Database::instance()->query("Achievement", Array("*"=>""), array($like, " OR ", $like2, $limit));
      $achievements = Database::instance()->dataClean($achievements, true);
  		$success = new Success($achievements);
  		Response::jsonResponse($success);
  	}
  }

}

?>
