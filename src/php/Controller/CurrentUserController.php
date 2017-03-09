<?php
const ERR_NOT_CONNECTED = -4;
require_once('Player.php');
require_once('Response.php');
class CurrentUserController{


  static public function stats(){
    $player = Player::connectSession();
    if(!$player) return ERR_NOT_CONNECTED;}
    else {
      $stats = $player->stats();
      $stats = Database::instance()->arrayMap($stats, 'Name', 'Value');
      return Response::jsonResponse($stats);
    }
  }

  static public function items(){
    $player = Player::connectSession();
    if(!$player) {return ERR_NOT_CONNECTED;}
    else {
      $items = $player->items();
      $items = Database::instance()->dataClean($items, true);
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


}
?>
