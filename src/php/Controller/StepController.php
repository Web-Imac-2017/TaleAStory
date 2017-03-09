<?php

namespace Controller;

use \Server\Database;
use \Server\Form;
use \Model\Step;

class StepController {
  public static function addStep() {
    echo var_dump($_POST, $_SERVER, $_GET);
    $imgpath = "truc";
    $body = Form::getField("body");
    $question = Form::getField("question");
    $accepted = Form::getField("accepted");
    $idType = Form::getField("idType");
    $item = new Step($imgpath, $body, $question, $accepted, $idType);
    //$item->save();
    Response::jsonResponse(???);
    Response::jsonResponse($_POST);
  }

  public static function updateStep() {
    $imgpath = Form::uploadFile("stepImg");
    $body = Form::getField("body");
    $question = Form::getField("question");
    $accepted = Form::getField("accepted");
    $idType = Form::getField("idType");
    $item = new Step($imgpath, $body, $question, $accepted, $idType);
    $item->id = $id;
    $entries = array();
    if ($imgpath != NULL)
      array_push($entries, $name);
    if ($body != NULL)
      array_push($entries, $name);
    if ($question != NULL)
      array_push($entries, $name);
    if ($accepted != NULL)
      array_push($entries, $name);
    if ($idType != NULL)
      array_push($entries, $name);
    $item->update($entries);
  }

  public static function deleteStep() {
    $id = Form::getField("id");
    $item = new Step("", "", "", "","");
    $item->id = $id;
    $item->delete();
  }

  echo var_dump($_POST);

  }

 ?>
