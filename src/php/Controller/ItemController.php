<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Item;
use \Model\Player;
use \Server\Response;
use \View\Success;
use \View\Error;
use \Controller\CurrentUserController;

class ItemController {

  public static function addItem() {
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
    $item = new Item($name, $imgpath, $brief);
    $item = $item->save();
    if($item)
      $e = new Success("L'item a été ajouté.");
    else
      $e = new Error(array("all"=>"Tu ne peux pas ajouter cet item !"));
    Response::jsonResponse($e);
    }

  public static function updateItem() {
    CurrentUserController::isAdmin();
    $isError =false;
    $data = Form::getFullForm();
    if(!isset($data["iditem"]) || $data["iditem"]== null ){
      $errors["iditem"]="id invalide !";
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
        $oldimg =  Item::getItemImg($data["iditem"]);
        if($oldimg != '../assets/images/default_image_tiny.png' && file_exists ($oldimg)){
          try {
            unlink($oldimg);
          } catch(Exception $e) { }
        }
      }
    }
    if($isError){
      $e = new Error($errors);
      Response::jsonResponse($e);
    }
    $item = new Item ($entries["name"],$entries["brief"],$entries["brief"]);
    $item->id = $data["iditem"];
    $item->update($entries);
    $e = new Success("Item modifié !");
    Response::jsonResponse($e);
  }

  public static function deleteItem() {
    CurrentUserController::isAdmin();

    $id = Form::getField("iditem");
    if(!$id){
      $e = new Error(array("iditem"=>"Impossible de supprimer l'item !"));
      Response::jsonResponse($e);
    }
    else{
      $oldimg =  Item::getItemImg($id);
      $item = new Item("", "", "");
      $item->id = $id;
      $item=$item->delete();
      if($item){
        if($oldimg != '../assets/images/default_image_tiny.png' && file_exists ($oldimg)){
          try {
            unlink($oldimg);
          } catch(Exception $e) { }
          $e = new Success("Item supprimé !");
        }
      }
      else
        $e = new Error(array("all"=>"Impossible de supprimer l'item !"));
      Response::jsonResponse($e);
    }
  }

  public static function getItemList($start, $count) {
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
      if($search){
        $like = array("LIKE","Name",$search);
        $like2 = array("LIKE","Brief",$search);
    		$items = Database::instance()->query("Item", Array("*"=>""),  array($like, " OR ", $like2, $limit));
      } else {
        $items = Database::instance()->query("Item", Array("*"=>""),  array($limit));
      }
      $items = Database::instance()->dataClean($items, true);
  		$success = new Success($items);
  		Response::jsonResponse($success);
  	}
  }
  
  public static function getItem() {
    $id = Form::getField("id");
    $res =  Database::instance()->query("Item", Array(        "IDItem"=> $id,
                                                                    "Name"=>"",
                                                                    "ImgPath"=>"",
                                                                    "Brief"=>""));
    if ($res != null) {
      $success = New Success($res);
      Response::jsonResponse($success);
    }

    else {
      $error = new Error("Aucun item correspondant n'a été trouvé");
      Response::jsonResponse($error);
    }
  }

}

 ?>
