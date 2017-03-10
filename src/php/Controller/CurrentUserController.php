<?php
namespace Controller;

use \Model\Player;
use \Server\Database;
use \Server\Response;
use \Server\Session;
const ERR_NOT_CONNECTED = -4;

class CurrentUserController{



  static public function stats(){
    $player = Player::connectSession();
    if(!$player) return ERR_NOT_CONNECTED;
    else {
      $stats = $player->stats();
      $stats = Database::instance()->arrayMap($stats, 'Name', 'Value');
      return Response::jsonResponse($stats);
    }
  }

  static public function items(){
    //$player = Player::connectSession();
    $player = Player::connect("login","pwd");
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $items = $player->items();
      $items = Database::instance()->dataClean($items, true, array('Brief', 'ImgPath', 'Name', 'quantity'));
      return Response::jsonResponse($items);
    }
  }

  static public function currentStep(){
    $player = Player::connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $current_step = $player->currentStep();
      $current_step = Database::instance()->dataClean($current_step, true);
      return Response::jsonResponse($current_step);
    }
  }

  static public function story(){
    $player = Player::connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $story = $player->pastStep();
      $story = Database::instance()->dataClean($story, true);
      return Response::jsonResponse($story);
    }
  }


  static public function currentUser() {
    $id = Session::getCurrentUser();
    if ($id == NULL)
    return Response::jsonResponse(array(
      'status' => "error",
      'message' => "ta race il n'y a aucun utilisateur connecté !"
    ));
    else{
      //demander à Lou
    }
  }
  static public function achievements(){
    $userId = Session::getCurrentUser();
    $achievements = Database::instance()->query("PlayerAchievement",array("IDPlayer"=>$userId, "*" => ""));
    return Response::jsonResponse($achievements);
  }

  static public function unreadachievements(){
    $userId = Session::getCurrentUser();
    $achievements = Database::instance()->query("PlayerAchievement",array("IDPlayer"=>$userId, "isRead"=>0, "*" => ""));
    return Response::jsonResponse($achievements);
  }
}
?>
