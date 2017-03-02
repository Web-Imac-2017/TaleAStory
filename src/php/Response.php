<?php

class Response
{
  public static function generateIndex($param){
    require"./header.php";
    require"../body.html";
    exit();
  }

  public static function jsonResponse($requestParam){
    header('Content-Type: application/json');
    echo (json_encode($requestParam));
    exit();
  }

  public static function redirect($urlGoal){
    header('Location: '.$urlGoal.'');
    exit();
  }
}
 ?>
