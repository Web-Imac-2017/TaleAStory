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

    $name = Form::getField("Name");
    //$imgpath = Form::uploadFile("ItemImg");
    $imgpath="bidon";
    $brief = Form::getField("Brief");
    if ($name == NULL || $imgpath == NULL || $brief == NULL){
      $e = new Error("Tu ne peux pas ajouter cet item !");
      Response::jsonResponse($e);
    }
    $item = new Item($name, $imgpath, $brief);
    $item = $item->save();
    if($item)
      $e = new Success("L'item a été ajouté.");
    else{
      $e = new Error("Tu ne peux pas ajouter cet item !");
      }
    Response::jsonResponse($e);
    }

  public static function updateItem() {
    CurrentUserController::isAdmin();

    //$tmp = $imgpath;
    //$imgpath = Form::uploadFile("itemImg");
    $data = Form::getFullForm();
    if(!isset($data["IDItem"]) || $data["IDItem"]== null ){
      $e = new Error("Item invalide");
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
    $item = new Item ($entries["Name"],"bidon",$entries["Brief"]);
    $item->id = $data["IDItem"];
    $item->update($entries);
    $e = new Success("Item modifié !");
    Response::jsonResponse($e);
  }

  public static function deleteItem() {
    CurrentUserController::isAdmin();

    $id = Form::getField("IDItem");
    if(!$id){
      $e = new Error("Impossible de supprimer l'item !");
      Response::jsonResponse($e);
    }
    else{
      $item = new Item("", "", "");
      $item->id = $id;
      $item=$item->delete();
      if($item)
        $e = new Success("Item supprimé !");
      else
        $e = new Error("Impossible de supprimer l'item !");
      Response::jsonResponse($e);
    }
  }

}

 ?>
