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
    $player = Player::connectSession();
    //$player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      Response::jsonResponse($error);
    }
    else {
      $stats = $player->stats();
      $stats = Database::instance()->arrayMap($stats, 'Name', 'Value');
      $success = new Success($stats);
      Response::jsonResponse($success);
    }
  }

  static public function items(){
    $player = Player::connectSession();
    //$player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      Response::jsonResponse($error);
    }
    else {
      $items = $player->items();
      $items = Database::instance()->dataClean($items, true, array('Brief', 'ImgPath', 'Name', 'quantity'));
      $success = new Success($items);
      Response::jsonResponse($success);
    }
  }

  static public function currentStep(){
    $player = Player::connectSession();
    //$player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      Response::jsonResponse($error);
    }
    else {
      $current_step = $player->currentStep();
      $current_step = Database::instance()->dataClean($current_step, true);
      $success = new Success($current_step);
      Response::jsonResponse($success);
    }
  }

  static public function story(){
    $player = Player::connectSession();
    //$player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      Response::jsonResponse($error);
    }
    else {
      $story = $player->pastSteps();
      $story = Database::instance()->dataClean($story, true);
      $success = new Success($story);
      Response::jsonResponse($success);
    }
  }

  static public function currentUser() {
      $player = Player::connectSession();
      if($player){
        $e = new Success($player);
        Response::jsonResponse($e);
      }
      else
        return null;
  }

  static public function achievements(){
    $player = Player::connectSession();
	//echo $player;
    //$player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      Response::jsonResponse($error);
    }
    $achievements = $player->achievements();
    $achievements = Database::instance()->dataClean($achievements, true);
    $success = new Success($achievements);
    Response::jsonResponse($success);
  }

  static public function unreadAchievements(){
    $player = Player::connectSession();
    //$player = Player::connect("login","pwd");
    if(!$player) {
      $error = new Error("Vous n'êtes pas connectés");
      Response::jsonResponse($error);
    }
    $achievements = $player->unreadAchievements();
    $achievements = Database::instance()->dataClean($achievements, true);
    $success = new Success($achievements);
    Response::jsonResponse($success);
  }

  static public function isAdmin(){
    $admin = Player::connectSession();
    if(!$admin || $admin->admin == 0 || $admin->isAdmin() == 0){
      $e = new Error("Tu n'as pas le droit d'effectuer cette action !");
      Response::jsonResponse($e);
    }
  }
}
?>
