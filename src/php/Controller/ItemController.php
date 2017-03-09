<?php

require_once 'Item.php';
require_once 'Form.php';
require_once 'module_database.php';

class ItemController {

  public static function addItem() {
    echo var_dump($_POST, $_SERVER, $_GET);
    $name = Form::getField("name");
    /*var_dump($name);
    exit();*/
    //$imgpath = Form::uploadFile($file_input);
    $imgpath = "truc";
    $brief = Form::getField("brief");
    $item = new Item($name, $imgpath, $brief);
    //$item->save();
    Response::jsonResponse($name);
    Response::jsonResponse($_POST);
  }

  public static function updateItem() {
    $name = Form::getField("name");
    $imgpath = Form::uploadFile("itemImg");
    $brief = Form::getField("brief");
    $id = Form::getField("id");
    $item = new Item($name, $imgpath, $brief);
    $item->id = $id;
    $entries = array();
    if ($name != NULL)
      array_push($entries, $name);
    if ($imgpath != NULL)
      array_push($entries, $name);
    if ($brief != NULL)
      array_push($entries, $name);
    $item->update($entries);
  }

  public static function deleteItem() {
    $id = Form::getField("id");
    $item = new Item("", "", "");
    $item->id = $id;
    $item->delete();
  }

  echo var_dump($_POST);

}

 ?>
