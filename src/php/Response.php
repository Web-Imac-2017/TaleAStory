<?php

class Response
{

/**
* Fonction permettant de générer la page d'index du site
* Attend en paramètre un objet contenant les infos sur l'utilisateur courant
*/
  public static function generateIndex($param){
    require"./header.php";
    require"../body.html";
    exit();
  }

/**
* Permet de transformer le paramètre d'entrée en string JSON
*/
  public static function jsonResponse($requestParam){
    header('Content-Type: application/json');
    echo (json_encode($requestParam));
    exit();
  }

/**
* Permet de réaliser une redirection vers la page passée en paramètre*
*/
  public static function redirect($urlGoal){
    header('Location: '.$urlGoal.'');
    exit();
  }
}
 ?>
