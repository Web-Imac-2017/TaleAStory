<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Item;

class ItemController {

  public static function addItem() {
    $name = Form::getField("name");
    $imgpath = Form::uploadFile("itemImg");
    $brief = Form::getField("brief");
    if ($name == NULL || $imgpath == NULL || $brief == NULL)
    return Response::jsonResponse(array(
      'status' => "error",
      'message' => "ta race tu ne peux pas ajouter cet item !"
    ));
    $item = new Item($name, $imgpath, $brief);
    $item->save();
    Response::jsonResponse($item);
    }

  public static function updateItem() {
    $tmp = $imgpath;
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

}

 ?>
