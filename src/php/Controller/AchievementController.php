<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Achievement;
use \Model\Player;
use \Server\Response;
use \View\Success;
use \View\Error;

class AchievementController {

  public static function addAchievement() {
    $admin = Player::connectSession();
    if(!$admin || $admin->admin == 0 || $admin->isAdmin() == 0){
      $e = new Error("Tu n'as pas le droit d'effectuer cette action !");
      Response::jsonResponse($e);
    }
    $name = Form::getField("Name");
    //$imgpath = Form::uploadFile("AchievementImg");
    $imgpath="bidon";
    $brief = Form::getField("Brief");
    if ($name == NULL || $imgpath == NULL || $brief == NULL){
      $e = new Error("Tu ne peux pas ajouter cet achievement !");
      Response::jsonResponse($e);
    }
    $a = new Achievement($name, $imgpath, $brief);
    $a = $a->save();
    if($a)
      $e = new Success("L'achievement a été ajouté.");
    else{
      $e = new Error("Tu ne peux pas ajouter cet achievement !");
      }
    Response::jsonResponse($e);
  }

  public static function updateAchievement() {
    $admin = Player::connectSession();
    if(!$admin || $admin->admin == 0 || $admin->isAdmin() == 0){
      $e = new Error("Tu n'as pas le droit d'effectuer cette action !");
      Response::jsonResponse($e);
    }
    //$tmp = $imgpath;
    //$imgpath = Form::uploadFile("");
    $data = Form::getFullForm();
    if(!isset($data["IDAchievement"]) || $data["IDAchievement"]== null ){
      $e = new Error("Achievement invalide");
      Response::jsonResponse($e);
    }

    $entries = array();
    $fields = array("Name","ImgPath","Brief");
    foreach ($fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        if(substr($field,0,2) != "ID") //les champs ID inchangés (donc initialisés à null dans data) ne doivent pas être précisés dans entries
          $entries[$field]="";
      }
      else {
        $entries[$field]=$data[$field];
      }
    }
    $a = new Achievement ($entries["Name"],"bidon",$entries["Brief"]);
    $a->id = $data["IDAchievement"];
    $a->update($entries);
    $e = new Success("Achievement modifié !");
    Response::jsonResponse($e);
  }

  public static function deleteAchievement() {
    $admin = Player::connectSession();
    if(!$admin || $admin->admin == 0 || $admin->isAdmin() == 0){
      $e = new Error("Tu n'as pas le droit d'effectuer cette action !");
      Response::jsonResponse($e);
    }
    $id = Form::getField("IDAchievement");
    if(!$id){
      $e = new Error("Impossible de supprimer l'achievement !");
      Response::jsonResponse($e);
    }
    else{
      $a = new Achievement("", "", "");
      $a->id = $id;
      $a=$a->delete();
      if($a)
        $e = new Success("Achievement supprimé !");
      else
        $e = new Error("Impossible de supprimer l'achievement !");
      Response::jsonResponse($e);
    }
  }

}

?>
