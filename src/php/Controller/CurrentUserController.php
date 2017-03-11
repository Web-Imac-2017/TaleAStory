<?php
namespace Controller;

use \Model\Player;
use \Server\Database;
use \Server\Response;
use \Server\Session;
use \View\Error;
use \View\Success;

const ERR_NOT_CONNECTED = -4;

class CurrentUserController{



  static public function stats(){
    //$player = Player::connectSession();
    $player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      return Response::jsonResponse($error);
    }
    else {
      $stats = $player->stats();
      $stats = Database::instance()->arrayMap($stats, 'Name', 'Value');
      $success = new Success($stats);
      return Response::jsonResponse($success);
    }
  }

  static public function items(){
    //$player = Player::connectSession();
    $player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      return Response::jsonResponse($error);
    }
    else {
      $items = $player->items();
      $items = Database::instance()->dataClean($items, true, array('Brief', 'ImgPath', 'Name', 'quantity'));
      $success = new Success($items);
      return Response::jsonResponse($success);
    }
  }

  static public function currentStep(){
    //$player = Player::connectSession();
    $player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      return Response::jsonResponse($error);
    }
    else {
      $current_step = $player->currentStep();
      $current_step = Database::instance()->dataClean($current_step, true);
      $success = new Success($current_step);
      return Response::jsonResponse($success);
    }
  }

  static public function story(){
    //$player = Player::connectSession();
    $player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      return Response::jsonResponse($error);
    }
    else {
      $story = $player->pastSteps();
      $story = Database::instance()->dataClean($story, true);
      $success = new Success($story);
      return Response::jsonResponse($success);
    }
  }

  static public function currentUser() {
    $id = Session::getCurrentUser();
    if ($id == NULL){
      $e = new Success("Pas d'utilisateur courant");
      return Response::jsonResponse($e);
    }
    else{
      $player = Player::connectSession();
      if($player)
          $e = new Succes($player);
      else
        $e = new Succes("Pas d'utilisateur courant");
      return Response::jsonResponse($e);
    }
  }
  static public function achievements(){
    //$player = Session::getCurrentUser();
    $player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      return Response::jsonResponse($error);
    }
    $achievements = $player->achievements();
    $achievements = Database::instance()->dataClean($achievements, true);
    $success = new Success($achievements);
    return Response::jsonResponse($success);
  }

  static public function unreadAchievements(){
    //$player = Session::getCurrentUser();
    $player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      return Response::jsonResponse($error);
    }
    $achievements = $player->unreadAchievements();
    $achievements = Database::instance()->dataClean($achievements, true);
    $success = new Success($achievements);
    return Response::jsonResponse($success);
  }
}
?>
