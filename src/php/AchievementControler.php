<?php

require_once 'Achievement.php';
require_once 'Form.php';
require_once 'module_database.php';

class AchievementControler {

  public static function addAchievement() {
    echo var_dump($_POST, $_SERVER, $_GET);
    $name = Form::getField("name");
    /*var_dump($name);
    exit();*/
    $imgpath = "truc";
    $brief = Form::getField("brief");
    $item = new Achievement($name, $imgpath, $brief);
    //$item->save();
    Response::jsonResponse($name);
    Response::jsonResponse($_POST);
  }

  public static function updateAchievement() {
    $name = Form::getField("name");
    $imgpath = Form::uploadFile("achievementImg");
    $brief = Form::getField("brief");
    $id = Form::getField("id");
    $item = new Achievement($name, $imgpath, $brief);
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

  public static function deleteAchievement() {
    $id = Form::getField("id");
    $item = new Achievement("", "", "");
    $item->id = $id;
    $item->delete();
  }

}

echo var_dump($_POST);

//

 ?>
