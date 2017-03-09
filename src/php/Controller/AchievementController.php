<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Achievement;

class AchievementController {

  public static function addAchievement() {
    $name = Form::getField("name");
    $imgpath = Form::uploadFile("achievementImg");
    $brief = Form::getField("brief");
    if ($name == NULL || $imgpath == NULL || $brief == NULL)
    return Response::jsonResponse(array(
      'status' => "error",
      'message' => "ta race tu ne peux pas ajouter cet achievement !"
    ));
    $achievement = new Achievement($name, $imgpath, $brief);
    $achievement->save();
    Response::jsonResponse($achievement);
  }

  public static function updateAchievement() {
    $tmp = $imgpath;
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
    $achievement = new Achievement("", "", "");
    $achievement->id = $id;
    $achievement->delete();
  }

}

?>
